<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SliderRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\TestimonialRepository;
use App\Repositories\CustomerMenuRepository;
<<<<<<< HEAD
=======
use App\Repositories\MembershipRepository;
>>>>>>> rilisv1
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\PromoRepository;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $data['slider'] = SliderRepository::getSliderCust();
        $data['artikel'] = ArticleRepository::getAll();
        $data['testimonial'] = TestimonialRepository::getAll();
        $data['customer_menu'] = CustomerMenuRepository::getAll();
        $data['promo'] = PromoRepository::get($request);
        if(!empty($request->token)){
            $user = UserRepository::findByToken($request->bearerToken());
            if(!$user->is_active) {
                return $this->sendFailedResponse([], 'Maaf, akun anda tidak aktif');
            }       
<<<<<<< HEAD
=======
            $data['membership'] = MembershipRepository::getByUserId($user->id);
>>>>>>> rilisv1
            UserService::updateFcmToken($user, $request->token);
        }
        $this->sendSuccessResponse($data);
    }
}
