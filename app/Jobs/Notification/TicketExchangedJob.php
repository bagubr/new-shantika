<?php

namespace App\Jobs\Notification;

use App\Events\SendingNotification;
use App\Http\Resources\CheckpointResource;
use App\Http\Resources\Mail\ExchangedTicketResource;
use App\Models\Notification;
use App\Models\Order;
use App\Utils\NotificationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TicketExchangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Order $order) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = NotificationMessage::successfullySendingTicket();
        $notification = Notification::build($payload[0], $payload[1], $this->order->id, $this->order->user_id);
        SendingNotification::dispatch($notification, $this->order->user->fcm_token, true, [
            'reference_id'=>$this->order->id,
            'type'=>Notification::TYPE1
        ]);

        $checkpoint_start = $this->order->route->checkpoints()->where('agency_id', $this->order->departure_agency_id)->first();
        $checkpoint_end = $this->order->route->checkpoints()->where('agency_id', $this->order->destination_agency_id)->first();
        $data = [
            "fleet_name"=>$this->order->route?->fleet?->name,
            "fleet_class"=>$this->order->route?->fleet?->fleetclass?->name,
            "checkpoint_start"=>(object) [
                "agency_id"=>$checkpoint_start->agency?->id ?? "",
                "agency_name"=>$checkpoint_start->agency?->name ?? "",
                "agency_address"=>$checkpoint_start->agency?->address ?? "",
                "agency_phone"=>$checkpoint_start->agency?->phone ?? "",
                "agency_lat"=>$checkpoint_start->agency?->lat,
                "agency_lng"=>$checkpoint_start->agency?->lng,
                "city_name"=>$checkpoint_start->agency?->city?->name ?? "",
                "arrived_at"=>$checkpoint_start->arrived_at,
            ],
            "checkpoint_destination"=>(object) [
                "agency_id"=>$checkpoint_end->agency?->id ?? "",
                "agency_name"=>$checkpoint_end->agency?->name ?? "",
                "agency_address"=>$checkpoint_end->agency?->address ?? "",
                "agency_phone"=>$checkpoint_end->agency?->phone ?? "",
                "agency_lat"=>$checkpoint_end->agency?->lat,
                "agency_lng"=>$checkpoint_end->agency?->lng,
                "city_name"=>$checkpoint_end->agency?->city?->name ?? "",
                "arrived_at"=>$checkpoint_end->arrived_at,
            ],
            "code_order"=>$this->order->code_order,
            "order_detail"=>(object) [
                "customer_name"=>$this->order->order_detail[0]->name,
                "is_feed"=>$this->order->order_detail[0]->is_feed ? "Ya" : "Tidak",
                "is_travel"=>$this->order->order_detail[0]->is_travel ? "Ya" : "Tidak",
                "id_member"=>$this->order->id_member ? $this->order->id_member : "-"
            ],
            "distribution"=>$this->order->distribution,
            "seats"=>implode(", ", $this->order_detail->pluck('chair.name')),
            "seats_count"=>$this->order_detail->count(),
            "total_price"=>$this->order->price
        ];
        Mail::send('_emails.exchange', $data, function($message) use ($payload) {
            $message->to($this->order_detail[0]->email, $this->order_detail[0]->name)->subject('Konfirmasi Pembelian');
            $message->from(env('MAIL_USERNAME'), $payload[0]);
        });    
    }
}
