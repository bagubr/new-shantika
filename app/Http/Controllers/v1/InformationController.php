<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'informations'=>Information::all()
        ]);
    }
}
