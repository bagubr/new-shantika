<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderCreateRequest;
use App\Models\Order;
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
        $order = OrderService::create($order, $request->details);

        return $this->sendSuccessResponse([
            'order'=>$order
        ]);
    }
}