<?php

namespace App\Repositories;

use App\Models\Promo;

class PromoRepository {

    public static function isExist($promo_id = null)
    {
        $date = date('Y-m-d');
        return Promo::withCount('promo_histories')
        ->where('id', $promo_id)
        ->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date)
        ->when((!Promo::find($promo_id)->is_quotaless), function ($query) use ($promo_id)
        {
            $query->having('promo_histories_count', '<', Promo::find($promo_id)->quota);
        })
        ->exists();
    }
}
        