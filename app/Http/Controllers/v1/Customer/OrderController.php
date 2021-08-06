<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Models\Order;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Http\Resources\Order\OrderDetailCustomerResource;
use App\Http\Resources\Order\OrderTiketResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Utils\Image;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Node\Query\OrExpr;

class OrderController extends Controller
{
    
    public function index(Request $request)
    {
        $user_id = UserRepository::findByToken($request->bearerToken())?->id;
        if($user_id){
            $order = OrderRepository::getByUserId($user_id);
        }else{
            
            $order = OrderRepository::getByArrayId($request->order_id);
        }
        
        return $this->sendSuccessResponse([
            'order'=> $order,
        ]);
    }
    
    public function show($id)
    {
        $order = OrderRepository::findWithDetailWithPayment($id);
        return $this->sendSuccessResponse([
            'data_order' => new OrderDetailCustomerResource($order),
            'payment' => OrderService::getInvoice($order->payment()->first())
        ]);
    }

    public function store(ApiOrderCreateRequest $request)
    {
        DB::beginTransaction();
        $order = new Order([
            'user_id'=>UserRepository::findByToken($request->bearerToken())?->id,
            'route_id'=>$request->route_id,
            'id_member'=>$request->id_member,
            'reserve_at'=>$request->reserve_at,
            'status'=>Order::STATUS1,
            'destination_agency_id'=>$request->destination_agency_id
        ]);
        $request['is_travel'] = false;
        $request['is_feed'] = true;
        $order = OrderService::create($order, $request, $request->payment_type_id);

        $this->createPayment($order->id, $request->payment_type_id);
        DB::commit();
        return $this->sendSuccessResponse([
            'order' => new OrderDetailCustomerResource($order),
            'payment' => OrderService::getInvoice($order->payment()->first())
        ]);
    }
    
    public function createPayment($order_id, $payment_type_id)
    {
        $order = Order::find($order_id);
        // Jika customer
        $payment = PaymentService::createOrderPayment($order, $payment_type_id);
    }
    
    public function tiket(Request $request)
    {
        $order = Order::find($request->order_id);
        return $this->sendSuccessResponse([
            'order_tiket'=> New OrderTiketResource($order)
        ]);
    }

    public function upload(Request $request) {
        $order = OrderRepository::findWithDetailWithPayment($request->order_id);
        $order = PaymentService::uploadProof($order, $request->file);

        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }
}
