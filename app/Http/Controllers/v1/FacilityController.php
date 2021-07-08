<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'facilities'=>Facility::all()
        ]);
    }
}
