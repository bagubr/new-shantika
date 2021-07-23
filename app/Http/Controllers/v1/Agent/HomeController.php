<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Repositories\ArticleRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        $testis = Testimonial::get();
        $unread_notifs = NotificationRepository::getUnreadNotificationByUserToken($request->bearerToken())->count();
        $articles = ArticleRepository::getAll();
        $this->sendSuccessResponse([
            'testimonials'=>$testis,
            'unread_notifs'=>$unread_notifs,
            'articles'=>$articles
        ]);
    }
}
