<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request) {
        $this->sendSuccessResponse([
            'cities'=>City::orderBy('name')->get()
        ]);
    }
}
