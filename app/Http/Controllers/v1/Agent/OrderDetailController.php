<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiOrderDetailUpdateRequest;
use App\Http\Resources\OrderDetail\DetailTodayPossibleCustomerResource;
use App\Http\Resources\OrderDetail\TodayPossibleCustomerResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\UserRepository;
use App\Services\OrderPriceDistributionService;
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
                $price -= Setting::first()->member;
                $order_detail->order->distribution->update([
                    'for_member' => $order_detail->order->distribution->for_member - $price
                ]);
            }elseif($is_member == 1){
                $price += Setting::first()->member;
                $order_detail->order->distribution->update([
                    'for_member' => $order_detail->order->distribution->for_member + $price
                ]);
            }
        }

        if(isset($is_travel)){
            if($is_travel == 0){
                $price -= Setting::first()->travel;
                $order_detail->order->distribution->update([
                    'for_travel' => $order_detail->order->distribution->for_travel - $price
                ]);
            }elseif($is_travel == 1){
                $price += Setting::first()->travel;
                $order_detail->order->distribution->update([
                    'for_travel' => $order_detail->order->distribution->for_travel + $price
                ]);
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
            'ticket_only' => $order_detail->order->distribution->ticket_only + $price
        ]);

        
        // $data['price'] = $order_detail->order->price;
        // $data['chairs'] = count($order_detail->order->order_detail);
        
        // $price_food = ($order_detail->order->distribution->for_food == 0)?$order_detail->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food:$order_detail->order->distribution->for_food;
        // $data['price_food_ticket'] = ($price_food / $data['chairs']);
        // if(isset($is_feed)){
        //     if($is_feed == 1){
        //         $data['price'] +=  $data['price_food_ticket'];
        //     }else{
        //         $data['price'] -=  $data['price_food_ticket'];
        //     }
        // }
        // $data['price_food'] = $data['price_food_ticket'] * ($order_detail->order->order_detail->where('is_feed', 1)->count());


        // $price_member = ($order_detail->order->distribution->for_member == 0)?Setting::first()->member:$order_detail->order->distribution->for_member;
        // $data['price_member_ticket'] = ($price_member / $data['chairs']);
        // if(isset($is_member)){
        //     if($is_member == 1){
        //         $data['price'] -= $data['price_member_ticket'];
        //     }else{
        //         $data['price'] += $data['price_member_ticket'];
        //     }
        // }
        // $data['price_member'] = $data['price_member_ticket'] * ($order_detail->order->order_detail->where('is_member', 1)->count());

        // $price_travel = ($order_detail->order->distribution->for_travel == 0)?Setting::first()->travel:$order_detail->order->distribution->for_travel;
        // $data['price_travel_ticket'] = ($price_travel / $data['chairs']);
        // if(isset($is_travel)){
        //     if($is_travel == 1){
        //         $data['price'] += $data['price_travel_ticket'];
        //     }else{
        //         $data['price'] -= $data['price_travel_ticket'];
        //     }
        // }
        // $data['price_travel'] = $data['price_travel_ticket'] * ($order_detail->order->order_detail->where('is_travel', 1)->count());
        // $order_detail->order->update([
        //     'price' => $data['price']
        // ]);
        // $order_detail->order->distribution->update([
        //     'for_food' => $data['price_food'],
        //     'for_travel' => $data['price_travel'],
        //     'for_member' => $data['price_member'],
        // ]);
        // OrderPriceDistributionService::calculateDistribution($order_detail->order, $order_detail, $data['price'], $data['price_food'], $data['price_travel'], $data['price_member']);
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
