<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Http\Resources\Order\OrderDetailAgentResource;
use App\Http\Resources\Order\OrderListAgentResource;
use App\Models\Order;
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
            'reserve_at'=>$request->reserve_at
        ]);
        $order = OrderService::create($order, $request);
        DB::commit();
        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }

    public function index(Request $request) {
        $user_id = UserRepository::findByToken($request->bearerToken())?->id;
        $order = OrderRepository::getByUserIdAndDate($user_id, $request->date);
        
        return $this->sendSuccessResponse([
            'order'=> OrderListAgentResource::collection($order),
        ]);
    }

    public function show($id, Request $request) {
        $order = OrderRepository::findWithDetailWithPayment($id);

        return $this->sendSuccessResponse([
            'order' => new OrderDetailAgentResource($order),
            'payment' => OrderService::getInvoice($order->payment()->first())
        ]);
    }

    public function exchange(Request $request) {
        $order = OrderRepository::findByCodeOrder($request->code_order);
        $order = OrderRepository::findWithDetailWithPayment($order->id);

        return $this->sendSuccessResponse([
            'order'=> new OrderDetailAgentResource($order)
        ]);
    }

    public function exchangeConfirm(Request $request) {
        $order = OrderRepository::findWithDetailWithPayment($request->id);
        $order = OrderService::exchangeTicket($order);

        return $this->sendSuccessResponse([
            'order' => new OrderDetailAgentResource($order)
        ]);
    }
}