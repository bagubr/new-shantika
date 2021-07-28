<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestimonialRequest;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Service\TestimonialService;
class TestimonialController extends Controller
{
    public function testimonialDetail($id)
    {
        $data = Testimonial::find($id);
        $this->sendSuccessResponse([
            'data'=>$data
        ]);
    }
}
