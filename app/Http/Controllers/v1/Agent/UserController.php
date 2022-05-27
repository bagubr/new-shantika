<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\ReviewService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function show(Request $request) {
        $user = UserService::getAuthenticatedUser($request->bearerToken());
        if(empty($user)) {
            $this->sendFailedResponse([], 'Oops sepertinya anda harus login ulang');
        }
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