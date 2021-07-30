<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;
use App\Utils\Response;
class ReviewService {
    use Response;
    public static function sumByUserIdForAgent($user_id) : float {
        return (float) rand(0, 100) / 10;
    }

    public static function create($data)
    {
        if(Review::whereOrderId($data->order_id)->first()){
            (new self)->sendFailedResponse([], "Sepertinya anda sudah review");
        }else{
            $data->save();
            OrderService::updateStatus($data->order_id, Order::STATUS8);
            return $data;
        }
            
    }
}
        