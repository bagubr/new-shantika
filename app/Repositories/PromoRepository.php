<?php

namespace App\Repositories;

use App\Models\Promo;

class PromoRepository {

    public static function isAvailable($promo_id, $user_id = null)
    {
        $date = date('Y-m-d');
        return Promo::has('promo_histories','<',Promo::find($promo_id)->quota)
        ->where('id', $promo_id)
        ->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date)
        ->when($user_id, function ($query) use ($user_id)
        {
          $query->where('user_id', $user_id)->orWhereNull('user_id');
        })
        ->exists();
    }
    
    public static function get($request = null){
        $route_id = $request->route_id;
        $code = $request->code;
        $is_public = $request->is_public??true;
        $date = date('Y-m-d');
        $user = UserRepository::findByToken($request->bearerToken());
        
        return Promo::withCount('promo_histories')
        ->when($route_id, function ($query) use ($route_id) {
            return $query->where('route_id', $route_id);
        })
        ->when($code, function ($query) use ($code) {
            return $query->where('code', 'ilike', '%'.$code.'%');
        })
        ->where('is_public', $is_public)
        ->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date)
        ->where(function ($query) use ($user)
        {
            $query->whereNull('user_id');
            $query->when($user, function ($query) use ($user) {
                $query->orWhere('user_id', $user->id);
            });
        })
        ->orderBy('id', 'desc')
        ->get();
    }

    public static function getWithNominalDiscount($price = null, $promo_id)
    {
      $promo = Promo::find($promo_id);
      if($price){
        $percentage = $promo->percentage_discount/100;
        $discount = ceil($percentage * $price);
        $promo->nominal_discount = ($discount > $promo->maximum_discount)?$promo->maximum_discount:$discount;
      }
      return $promo;
    }
}
        