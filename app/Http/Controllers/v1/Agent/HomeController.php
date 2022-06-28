<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Repositories\ArticleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        $user = UserRepository::findByToken($request->bearerToken());
        if(!$user->is_active) {
            return $this->sendFailedResponse([], 'Maaf, akun anda tidak aktif');
        }
        $testis = TestimonialRepository::getAll();
        $unread_notifs = NotificationRepository::getUnreadNotificationByUserId($user->id)->count();
        $articles = ArticleRepository::getAll();

        UserService::updateFcmToken($user, $request->token);

        $this->sendSuccessResponse([
            'testimonials'=>$testis,
            'unread_notifs'=>$unread_notifs,
            'articles'=>$articles,
            'user' => $user
        ]);
    }
}
