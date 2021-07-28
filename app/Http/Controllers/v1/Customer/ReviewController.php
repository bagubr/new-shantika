<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\Api\TestimonialRequest;
use App\Models\Review;
use App\Services\TestimonialService;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $user = UserRepository::findByToken($request->bearerToken())
            ?? $this->sendFailedResponse([], 'Anda sepertinya perlu login ulang / anda perlu regis ulang');
        $testimonial = new Review([
            'order_id'=>$request->order_id,
            'review'=>$request->review,
            'rating'=>$request->rating,
        ]);
        $data = ReviewService::create($testimonial);
        $this->sendSuccessResponse([
            'data'=>$data
        ]);
    }
}
