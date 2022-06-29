<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderDetailUpdateRequest;
use App\Http\Resources\OrderDetail\DetailTodayPossibleCustomerResource;
use App\Http\Resources\OrderDetail\TodayPossibleCustomerResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function possibleCustomer(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        $date = $request->date ?? date('Y-m-d');
        $order_details = OrderDetailRepository::getAllByAgencyId($user, $date);

        return $this->sendSuccessResponse([
            'order_details'=>TodayPossibleCustomerResource::collection($order_details)
        ]);
    }
    
    public function detailPossibleCustomer(Request $request, $id) {
        $order_detail = OrderDetailRepository::firstForPossibleCustomer($id);

        return $this->sendSuccessResponse([
            'order_detail'=>new DetailTodayPossibleCustomerResource($order_detail)
        ]);
    }

    public function editDataPenumpang(ApiOrderDetailUpdateRequest $request, OrderDetail $order_detail)
    {
        $data = $request->all();
        $order_detail->update($data);
        $order_detail->refresh();
        return $this->sendSuccessResponse([
            'order_detail' => $order_detail
        ], 'Data Berhasil di ubah');
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        
    }
}
