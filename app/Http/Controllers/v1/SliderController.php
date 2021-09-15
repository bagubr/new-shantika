<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SliderRepository;

class SliderController extends Controller
{
    public function index() {
        $data = SliderRepository::getSliderCust();

        $this->sendSuccessResponse([
            'slider'=>$data
        ]);
    }
    public function sliderDetail($id)
    {
        $data = SliderRepository::findById($id);
        
        $this->sendSuccessResponse([
            'slider'=>$data
        ]);
    }
}
