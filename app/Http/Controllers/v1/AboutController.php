<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        $date = date('Y-m-d H:i:s');
        return date('Y-m-d H:i:s', strtotime($date . ' +1 day'));
        return $this->sendSuccessResponse([
            'about'=>About::first()
        ]);
    }
}
