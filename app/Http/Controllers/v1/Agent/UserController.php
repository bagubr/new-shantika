<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\ReviewService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request) {
        $user = UserService::getAuthenticatedUser($request->bearerToken());
        $review = ReviewService::sumByAgencyId($user->agencies->agency_id);

        return $this->sendSuccessResponse([
            'user'=>$user,
            'review'=>$review
        ]);
    }

    public function update(Request $request) {
        $user = UserService::getAuthenticatedUser($request->bearerToken());
        $user = UserService::updateProfile($user, $request->toArray());

        return $this->sendSuccessResponse([
            'user'=>$user
        ]);
    }
}