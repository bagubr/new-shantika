<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Repositories\ArticleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        $testis = Testimonial::get();
        $unread_notifs = NotificationRepository::getUnreadNotificationByUserToken($request->bearerToken())->count();
        $articles = ArticleRepository::getAll();

        UserService::updateFcmToken(UserRepository::findByToken($request->bearerToken()), $request->token);

        $this->sendSuccessResponse([
            'testimonials'=>$testis,
            'unread_notifs'=>$unread_notifs,
            'articles'=>$articles
        ]);
    }
}
