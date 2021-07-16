<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SliderRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\TestimonialRepository;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $data['slider'] = SliderRepository::getSliderCust();
        $data['artikel'] = ArticleRepository::getAll();
        $data['testimonial'] = TestimonialRepository::getAll();

        $this->sendSuccessResponse([
            'data'=>$data
        ]);
    }
}
