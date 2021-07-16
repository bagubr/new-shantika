<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
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
