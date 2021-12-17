<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        $about = About::first();
        $about->description = '';
        return $this->sendSuccessResponse([
            'about'=> $about
        ]);
    }
}
