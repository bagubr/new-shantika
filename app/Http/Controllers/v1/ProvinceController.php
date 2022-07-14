<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        $data = Province::get();

        $this->sendSuccessResponse([
            'provincies'=>$data
        ]);
    }
}
