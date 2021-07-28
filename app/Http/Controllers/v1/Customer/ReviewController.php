<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
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
