<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Models\Order;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Http\Resources\Order\OrderListCustomerResource;
use App\Http\Resources\Order\OrderDetailCustomerResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    
    public function index(Request $request)
    {
        $user_id = UserRepository::findByToken($request->bearerToken())?->id;
        $order = OrderRepository::getByUserId($user_id);
        
        return $this->sendSuccessResponse([
            'order'=> OrderListCustomerResource::collection($order),
        ]);
    }
    
    public function show($id)
    {
        $order = OrderRepository::findWithDetailWithPayment($id);
        return $this->sendSuccessResponse([
            'order' => new OrderDetailCustomerResource($order),
            'payment' => OrderService::getInvoice($order->payment()->first())
        ]);
    }

    public function store(ApiOrderCreateRequest $request)
    {
        DB::beginTransaction();
        $order = new Order([
            'user_id'=>UserRepository::findByToken($request->bearerToken())?->id,
            'route_id'=>$request->route_id,
            'reserve_at'=>$request->reserve_at
        ]);
        $request['is_travel'] = false;
        $request['is_feed'] = true;
        $order = OrderService::create($order, $request, $request->payment_type_id);
        DB::commit();
        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }
}