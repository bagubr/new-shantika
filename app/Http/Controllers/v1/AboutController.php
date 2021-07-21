<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Route;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        return $this->sendSuccessResponse([
            'about'=>About::first()
        ]);
    }
}
