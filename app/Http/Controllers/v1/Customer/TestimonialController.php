<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\Api\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\TestimonialService;

class TestimonialController extends Controller
{
    public function createTestimonial(TestimonialRequest $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $testimonial = new Testimonial([
            'user_id'=>UserRepository::findByToken($request->bearerToken())?->id,
            'title'=>$request->title,
            'review'=>$request->review,
            'rating'=>$request->rating,
            'image'=>$request->image,
            'is_show'=>false,
        ]);
        $data = TestimonialService::create($testimonial);
        $this->sendSuccessResponse([
            'data'=>$data
        ]);
    }
}
