<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Repositories\PromoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $route_id = $request->route_id;
        $code = $request->code;
        $is_public = $request->is_public;
        $date = date('Y-m-d');
        $user = UserRepository::findByToken($request->bearerToken());
        $promos = Promo::withCount('promo_histories')
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

        $this->sendSuccessResponse([
            'promos' => $promos
        ]);
    }

    public function getById(Request $request, $id)
    {
        $promo_exists = PromoRepository::isAvailable($id);
        if(!$promo_exists){
            (new self)->sendFailedResponse([], 'Maaf, promo tidak di temukan atau sudah habis');
        }
        $price = $request->price;
        $this->sendSuccessResponse([
            'promo' => PromoRepository::getWithNominalDiscount($price, $id)
        ]);
    }
}
