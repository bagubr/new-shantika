<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Http\Resources\Order\OrderDetailAgentResource;
use App\Http\Resources\Order\OrderListAgentResource;
use App\Http\Resources\OrderDetailSetoranAgentResource;
use App\Http\Resources\OrderListSetoranAgentResource;
use App\Http\Resources\OrderSetoranDetailAgentResource;
use App\Models\Order;
use App\Repositories\BookingRepository;
use App\Repositories\LayoutChairRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderPriceDistributionRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function order(ApiOrderCreateRequest $request) {
        DB::beginTransaction();
        $order = new Order([
            'user_id'=>UserRepository::findByToken($request->bearerToken())?->id,
            'fleet_route_id'=>$request->fleet_route_id,
            'id_member'=>$request->id_member,
            'reserve_at'=>$request->reserve_at,
            'status'=>Order::STATUS3,
            'time_classification_id'=>$request->time_classification_id,
            'departure_agency_id'=>$request->departure_agency_id,
            'destination_agency_id'=>$request->destination_agency_id,
            'promo_id' => $request->promo_id,
            'note' => $request->note,
        ]);
        $order = OrderService::create($order, $request);
        DB::commit();
        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }

    public function index(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $order = OrderRepository::unionBookingByUserIdAndDate($user, $request->date);
        $deposit = OrderPriceDistributionRepository::getSumDepositOfAgencyByDate($request->bearerToken(), $request->date);
        return $this->sendSuccessResponse([
            'order'=> OrderListAgentResource::collection($order),
            'deposit'=>$deposit
        ]);
    }

    public function show($id, Request $request) {
        if($request->status == 'BOOKING'){
            $order = BookingRepository::findByCodeBookingWithRouteWithLayoutChair($request->code_booking);
        } else {
            $order = OrderRepository::findWithDetailWithPayment($id);
        }
        if($order){
            return $this->sendSuccessResponse([
                'order' => $order instanceof Order ? new OrderDetailAgentResource($order) : OrderDetailAgentResource::collection($order) 
            ]);
        }else{
            return $this->sendFailedResponse([], 'Data tidak di temukan');
        }
    }

    public function exchange(Request $request) {
        $order = OrderRepository::findByCodeOrder($request->code_order)
            ?? $this->sendFailedResponse([], "Kode tiket tidak ditemukan, periksa ulang kode anda");
        $order = OrderRepository::findWithDetailWithPayment($order->id);

        return $this->sendSuccessResponse([
            'order'=> new OrderDetailAgentResource($order)
        ]);
    }

    public function exchangeConfirm(Request $request) {
        $order = OrderRepository::findWithDetailWithPayment($request->id);
        $order = OrderService::exchangeTicket($order, $request->agency_id);

        return $this->sendSuccessResponse([
            'order' => new OrderDetailAgentResource($order)
        ]);
    }

    public function setoran(Request $request) {
        return $this->sendSuccessResponse([
            'deposit' => OrderPriceDistributionRepository::getSumDepositOfAgencyByDate($request->bearerToken(), $request->date),
            'commision' => OrderPriceDistributionRepository::getSumCommisionOfAgencyByDate($request->bearerToken(), $request->date),
            'chairs' => LayoutChairRepository::countBoughtByAgencyByDate($request->bearerToken(), $request->date),
            'buses' => OrderRepository::countBoughtRouteByAgencyByDate($request->bearerToken(), $request->date, true)
        ]);
    }

    public function showListSetoran(Request $request) {
        $orders = OrderRepository::getBoughtRouteByAgencyByDate($request->bearerToken(), $request->date);
        $orders = $orders->unique('fleet_route_id')->all();
        return $this->sendSuccessResponse([
            'orders'=> $orders ? OrderListSetoranAgentResource::collection($orders) : []
        ]);
    }

    public function showSetoran(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $chairs = OrderDetailRepository::findForPriceDistributionByUserAndDateAndFleet($user->id,$request->date, $request->fleet_id);
        $order = OrderRepository::findForPriceDistributionByDateAndFleet($user->id,$request->date, $request->fleet_id);
        if($chairs->isEmpty() && $order->isEmpty()) {
            $this->sendSuccessResponse([
                'chairs'=>[],
            ], 'Data riwayat tidak ditemukan');
        }
        $data = OrderDetailSetoranAgentResource::collection($chairs)->toArray(request());
        $food = 0;
        $price = 0;
        $non_food = 0;
        foreach($data as $values) {
            $food += $values[ 'food' ];
            $price += $values[ 'price' ];
            $non_food += $values[ 'non_food' ];
        }
        $total_price = $price - $food;
        if($user->agencies->agent->city->area_id == 2){
            $total_price = $price;
        }
        return $this->sendSuccessResponse([
            'chairs'=> OrderDetailSetoranAgentResource::collection($chairs),
            'order'=>new OrderSetoranDetailAgentResource($order, count($chairs), $total_price, $food, $non_food)
        ]);
    }
}