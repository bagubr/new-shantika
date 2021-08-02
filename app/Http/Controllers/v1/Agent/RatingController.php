<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());

        $rating = ReviewRepository::getHistoryOfAgent($user->id);

        $this->sendSuccessResponse([
            'rating'=>ReviewResource::collection($rating)
        ]);
    }

    public function show($id) {
        $rating = Review::find($id) ?? $this->sendFailedResponse([], 'Review tidak ditemukan');

        $this->sendSuccessResponse([
            'rating'=>new ReviewResource($rating)
        ]);
    }
}
