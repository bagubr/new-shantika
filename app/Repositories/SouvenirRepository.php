<?php

namespace App\Repositories;

use App\Models\Membership;
use App\Models\Souvenir;
use App\Models\SouvenirRedeem;

class SouvenirRepository {

    private $id;

    
    public static function getListSouvenir()
    {
        return Souvenir::where('quantity', '>', 0)->take(10)->orderBy('id', 'desc')->get();
    }

    public static function getFullListSouvenir()
    {
        return Souvenir::where('quantity', '>', 0)->orderBy('id', 'desc')->get();
    }
}