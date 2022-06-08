<?php

namespace App\Services;

use App\Events\SendingNotification;
use App\Http\Controllers\v1\OrderController;
use App\Jobs\Admin\NewOrderNotification;
use App\Jobs\Notification\TicketExchangedJob;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Agency;
use App\Models\BlockedChair;
use App\Models\FleetRoute;
use App\Models\Membership;
use App\Models\MembershipHistory;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\PromoHistory;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\BookingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PromoRepository;
use App\Utils\CodeMember;
use App\Utils\Response;
use App\Utils\NotificationMessage;
use App\Utils\PriceTiket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    use Response;

    public static function create(Order $data, $detail)
    {
        FleetRoute::find($data->fleet_route_id) ?? (new self)->sendFailedResponse([], 'Rute perjalanan tidak ditemukan');
        $order_exists = OrderRepository::isOrderUnavailable($data->fleet_route_id, $data->reserve_at, $detail->layout_chair_id, $data->time_classification_id);
        $booking_exists = BookingRepository::isBooked($data->fleet_route_id, $data->user_id, $detail->layout_chair_id, $data->reserve_at, $data->time_classification_id);
        if ($order_exists) {
            (new self)->sendFailedResponse([], 'Maaf, kursi sudah dibeli oleh orang lain, silahkan pilih kursi lain');
        }
        if ($booking_exists) {
            (new self)->sendFailedResponse([], "Maaf, kursi anda telah dibooking terlebih dahulu oleh orang lain");
        }

        $price = $detail->total_price;
        

        if (isset($data->promo_id) && $data->promo_id) {
            $promo_exists = PromoRepository::isAvailable($data->promo_id, $data->user_id);
            if(!$promo_exists){
                (new self)->sendFailedResponse([], 'Maaf, promo tidak di temukan atau sudah habis');
            }
            $promo = PromoRepository::getWithNominalDiscount($price, $data->promo_id);
            PromoHistory::create($data->only('user_id', 'promo_id'));
            $data->nominal_discount = $promo->nominal_discount;
        }
        $for_deposit = $price;
        $data->price = $price;
        if ($detail->is_member) {
            self::createHistory($data->user_id, $detail->id_member);
        }
        if (!$data->code_order) $data->code_order = self::generateCodeOrder($data->id);
        if (!$data->expired_at) $data->expired_at = self::getExpiredAt();

        $order = Order::create($data->toArray());
        $code_order = self::generateCodeOrder($order->id);
        $order->update([
            'code_order' => $code_order
        ]);
        $order->refresh();

        if ($detail->code_booking) BookingService::deleteByCodeBooking($detail->code_booking);

        self::createDetail($order, $detail->layout_chair_id, $detail, $for_deposit);
        self::sendNotification($order);

        return $order;
    }

    private static function sendNotification($order)
    {
        $message = NotificationMessage::successfullySendingTicket();
        $notification = Notification::build(
            $message[0],
            $message[1],
            Notification::TYPE1,
            $order->id,
            $order->user_id
        );
        $message = NotificationMessage::newTicketOrder($order);
        $admin_notification = AdminNotification::build(
            $message[0],
            $message[1],
            Notification::TYPE1,
            $order->id
        );
        AdminNotification::create([
            'title' => $message[0],
            'body' => $message[1],
            'type' => Notification::TYPE1,
            "reference_id" => $order->id,
        ]);
        if (empty($order->user?->agencies)) {
            SendingNotification::dispatch($notification, $order->user?->fcm_token, false);
        }
        NewOrderNotification::dispatch($admin_notification, Admin::whereNotNull('fcm_token')->pluck('fcm_token'), true);
    }

    public static function createDetail($order, $layout_chairs, $detail, $price)
    {
        $order_details = [];
        $blocked_chairs = BlockedChair::where('fleet_route_id', $order->fleet_route_id)->pluck('layout_chair_id')->toArray();
        foreach ($layout_chairs as $layout_chair_id) {
            if (in_array($layout_chair_id, $blocked_chairs)) {
                (new self)->sendFailedResponse([], 'Kursi ada yang sudah diblokir, silahkan refresh dan pilih kembali');
            }
            $order_details[] = OrderDetail::create([
                'order_id'          => $order->id,
                'layout_chair_id'   => $layout_chair_id,
                'name'              => $detail->name,
                'phone'             => $detail->phone,
                'email'             => $detail->email ?? $order->user?->email ?? $order->agency->users()->whereNotNull('email')->pluck('email')[0],
                'is_feed'           => $detail->is_feed,
                'is_travel'         => $detail->is_travel,
                'is_member'         => $detail->is_member
            ]);
        }
        OrderPriceDistributionService::createByOrderDetail($order, $order_details, $price);
    }

    public static function getInvoice(Payment|int|null $payment = null)
    {
        if (empty($payment)) {
            return '';
        }
        if ($payment instanceof int) {
            $payment = Payment::find($payment);
        }

        return @PaymentService::getSecretAttribute($payment) ?? "";
    }

    public static function exchangeTicket(Order &$order, $agency_id)
    {
        if (@$order->departure_agency_id != $agency_id) {
            (new self)->sendFailedResponse([], 'Maaf, anda hanya dapat menukarkan tiket di agen keberangkatan tiket');
        }
        if ($order->status != Order::STATUS3) {
            (new self)->sendFailedResponse([], 'Maaf, penumpang / customer harus membayar terlebih dahulu');
        }
        DB::beginTransaction();
        try {
            $order->update([
                'status' => Order::STATUS5,
                'exchanged_at' => date('Y-m-d H:i:s')
            ]);
            $for_deposit = PriceTiket::priceTiket(FleetRoute::find($order->fleet_route_id), Agency::find($order->departure_agency_id), Agency::find($order->destination_agency_id), $order->reserve_at);
            $total_price = OrderPriceDistributionService::calculateDistribution($order, $order->order_detail, $for_deposit);
            $order->distribution()->update([
                'for_agent' => $total_price['for_agent'],
                'for_owner' => $total_price['for_owner'],
                'for_owner_with_food' => $total_price['for_owner_with_food'],
                'for_owner_gross' => $total_price['for_owner_gross'],
                'total_deposit' => $total_price['total_deposit']
            ]);
            DB::commit();
            $order->refresh();
        } catch (\Throwable $th) {
            DB::rollBack();
            (new self)->sendFailedResponse([], 'Penukaran gagal di lakukan silahkan coba kembali');
        }

        TicketExchangedJob::dispatch($order);
        return $order;
    }

    public static function updateStatus(Order|int $order, $status)
    {
        if (is_int($order)) {
            $order = Order::find($order);
        }

        $order->update([
            'status' => $status,
        ]);

        $order->payment()->update([
            'status' => $status
        ]);
        $order->refresh();

        return $order;
    }

    public static function revertPrice(OrderDetail $order_detail)
    {
        $order_detail->load(['order.distribution']);
        $order = $order_detail->order;
        $order_details = $order->order_detail;

        try {
            $order_detail_count = count($order_details);
            $order_detail_div = $order->order_detail()->where('id', '!=', $order_detail->id)->get();

            $reverted_price = OrderPriceDistributionService::calculateDistribution($order, $order_detail_div, $order->distribution->ticket_only);
            $order->distribution()->update($reverted_price);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function createHistory($user_id, $id_member)
    {
        $user = User::find($user_id);
        if($id_member){
            $code_member = CodeMember::code($id_member);
            $membership = Membership::where('code_member', $code_member)->where('user_id', '!=', null)->first();
            if(!$membership){
                (new self)->sendFailedResponse([], 'Maaf user member tidak tersedia');
            }
            if(@$user->agencies){
                MembershipService::increment($membership, Setting::find(1)->point_purchase, 'Pembelian Tiket');
                MembershipHistory::create(['agency_id'=> $user->id,'customer_id'=> $membership->user_id]);
            }
        }
    }

    public static function generateCodeOrder($id)
    {
        return 'NS' . str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public static function getExpiredAt()
    {
        return date('Y-m-d H:i:s', strtotime("+1 day"));
    }
}
