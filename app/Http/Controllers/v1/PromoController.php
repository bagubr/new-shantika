<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $route_id = $request->route_id;
        $date = date('Y-m-d');
        $user = UserRepository::findByToken($request->bearerToken());
        $promos = Promo::withCount('promo_histories')
        ->when($route_id, function ($query) use ($route_id) {
            return $query->where('route_id', $route_id);
        })
        ->where('is_public', true)
        ->whereDate('start_at', '<=', $date)->whereDate('end_at', '>=', $date)
        ->where(function ($query) use ($user)
        {
            $query->whereNull('user_id');
            $query->orWhere('user_id', $user->id);
        })
        ->orderBy('id', 'desc')
        ->get();

        $this->sendSuccessResponse([
            'promos' => $promos
        ]);
    }
}
