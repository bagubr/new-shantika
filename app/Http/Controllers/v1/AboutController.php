<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Route;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        $route = Route::all()->random();
        return $route->fleet->layout->chairs;
        return $this->sendSuccessResponse([
            'about'=>About::first()
        ]);
    }
}
