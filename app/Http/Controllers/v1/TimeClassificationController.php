<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeClassification;
class TimeClassificationController extends Controller
{
    public function index()
    {
        $this->sendSuccessResponse([
            'time'=>TimeClassification::orderBy('id')->get(),
            'd'=> implode(", ",TimeClassification::name()->toArray()),
        ]);
    }
}
