<?php

namespace App\Repositories;
use App\Models\Slider;
class SliderRepository {

    public static function getSliderCust()
    {
        return Slider::whereType('CUST')->orderBy('id', 'desc')->get();
    }

    public static function getSliderAgent()
    {
        return Slider::whereType('AGENT')->orderBy('id', 'desc')->get();
    }

    public static function findById($id)
    {
        return Slider::find($id);
    }

}
        