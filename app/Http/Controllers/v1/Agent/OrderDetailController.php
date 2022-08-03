<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderDetailUpdateRequest;
use App\Http\Resources\OrderDetail\DetailTodayPossibleCustomerResource;
use App\Http\Resources\OrderDetail\TodayPossibleCustomerResource;
use App\Models\Membership;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\UserRepository;
use App\Services\MembershipService;
use App\Services\OrderPriceDistributionService;
use App\Utils\CodeMember;
use App\Utils\FoodPrice;
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
        if($order_detail->is_feed != $data['is_feed']){
            $is_feed = $data['is_feed'];
        }
        if($order_detail->is_member != $data['is_member']){
            $is_member = $data['is_member'];
        }
        if($order_detail->is_travel != $data['is_travel']){
            $is_travel = $data['is_travel'];
        }
        $order_detail->update($data);
        $order_detail->refresh();
        $price = 0;
        if(isset($is_member)){
            if($is_member == 0){
                $order_detail->order->distribution->update([
                    'for_member' => $order_detail->order->distribution->for_member - Setting::first()->member
                ]);
                $price += Setting::first()->member;
            }elseif($is_member == 1){
                $order_detail->order->distribution->update([
                    'for_member' => $order_detail->order->distribution->for_member + Setting::first()->member
                ]);
                $membership = Membership::where('code_member', CodeMember::code($order_detail->order->id_member))->first();
                if($membership){
                    MembershipService::increment($membership, Setting::find(1)->point_purchase, 'Pembelian Tiket');
                }
                $price -= Setting::first()->member;
            }
        }

        if(isset($is_travel)){
            if($is_travel == 0){
                $order_detail->order->distribution->update([
                    'for_travel' => $order_detail->order->distribution->for_travel - Setting::first()->travel
                ]);
                $price -= Setting::first()->travel;
            }elseif($is_travel == 1){
                $order_detail->order->distribution->update([
                    'for_travel' => $order_detail->order->distribution->for_travel + Setting::first()->travel
                ]);
                $price += Setting::first()->travel;
            }
        }
        
        if(isset($is_feed)){
            if($is_feed == 1){
                $price -= FoodPrice::foodPrice($order_detail->order->fleet_route, false);
            }else{
                $price += FoodPrice::foodPrice($order_detail->order->fleet_route, false);
            }
        }

        $order_detail->order->distribution->update([
            'ticket_only' => $order_detail->order->distribution->ticket_only + $price,
            'total_deposit' => $order_detail->order->distribution->total_deposit + $price
        ]);
        
        return $this->sendSuccessResponse([
            'order_detail' => $order_detail
        ], 'Data Berhasil di ubah');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $order = Order::find($id);
        $order = OrderPriceDistributionService::calculateDistribution($order, $order->order_detail, $data['price']);
        
    }
}
