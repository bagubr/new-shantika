<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Review\ReviewResource;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\ReviewService;
use App\Repositories\UserRepository;

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
    
    public function index(Request $request)
    {
        if($request->order_id && !empty($request->order_id)){
            $data = Review::whereHas('order', function ($query) use ($request)
            {
                $query->whereIn('id', $request->order_id);
            })->get();
        }else{
            $user = UserRepository::findByToken($request->bearerToken());
            $data = Review::whereHas('order', function ($query) use ($user)
            {
                $query->where('user_id', $user->user_id);
            })->get();
        }
        $this->sendSuccessResponse([
            'data_review'=>ReviewResource::collection($data)
        ]);
    }
}
