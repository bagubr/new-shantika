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
        $promos = PromoRepository::get($request);

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
