<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiCalculateDiscountRequest;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Http\Resources\Order\OrderDetailAgentResource;
use App\Http\Resources\Order\OrderListAgentResource;
use App\Http\Resources\OrderDetailSetoranAgentResource;
use App\Http\Resources\OrderListSetoranAgentResource;
use App\Http\Resources\OrderSetoranDetailAgentResource;
use App\Models\Order;
use App\Models\OrderPriceDistribution;
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
            'route_id'=>$request->route_id,
            'id_member'=>$request->id_member,
            'reserve_at'=>$request->reserve_at,
            'status'=>Order::STATUS3
        ]);
        $order = OrderService::create($order, $request);
        DB::commit();
        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }

    public function index(Request $request) {
        $user_id = UserRepository::findByToken($request->bearerToken())?->id;
        $order = OrderRepository::unionBookingByUserIdAndDate($user_id, $request->date);
        $deposit = OrderPriceDistributionRepository::getSumDepositOfAgencyByDate($request->bearerToken(), $request->date);
        return $this->sendSuccessResponse([
            'order'=> OrderListAgentResource::collection($order),
            'deposit'=>$deposit
        ]);
    }

    public function show($id, Request $request) {
        if($request->status == 'BOOKING'){
            $order = BookingRepository::findByCodeBookingWithRouteWithLayoutChair($id);
        } else {
            $order = OrderRepository::findWithDetailWithPayment($id);
        }

        return $this->sendSuccessResponse([
            'order' => $order instanceof Order ? new OrderDetailAgentResource($order) : OrderDetailAgentResource::collection($order) 
        ]);
    }

    public function exchange(Request $request) {
        $order = OrderRepository::findByCodeOrder($request->code_order)
            ?? $this->sendFailedResponse([], "Kode ticket tidak ditemukan");
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
            'buses' => OrderRepository::countBoughtRouteByAgencyByDate($request->bearerToken(), $request->date)
        ]);
    }

    public function showListSetoran(Request $request) {
        $orders = OrderRepository::getBoughtRouteByAgencyByDate($request->bearerToken(), $request->date);
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
        return $this->sendSuccessResponse([
            'chairs'=> OrderDetailSetoranAgentResource::collection($chairs),
            'order'=>new OrderSetoranDetailAgentResource($order)
        ]);
    }

    public function calculateDiscount(ApiCalculateDiscountRequest $request) {
        $data = [
            'total_food'=>$request->is_food ? 20000 * $request->seat_count : 0,
            'total_travel'=>$request->is_travel ? 20000 * $request->seat_count : 0,
            'total_member'=>$request->is_member ? 20000 * $request->seat_count : 0
        ];
        $data = array_merge($data, [
            'price_ticket'=>(int) $request->price_ticket,
            'total_price'=>($request->price_ticket * $request->seat_count + $data['total_food'] + $data['total_travel'] - $data['total_member'])
        ]);
        return $this->successResponse($data);
    }
}