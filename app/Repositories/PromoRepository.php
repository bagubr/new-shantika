<?php

namespace App\Repositories;

use App\Models\Promo;

class PromoRepository {

    public static function getById($promo_id = null)
    {
        $date = date('Y-m-d');
        $promo = Promo::has('promo_histories','<',Promo::find($promo_id)->quota)
        ->where('id', $promo_id)
        ->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date)
        ->first();
        if($promo){
          return $promo;  
        }
        return false;
    }
}
        