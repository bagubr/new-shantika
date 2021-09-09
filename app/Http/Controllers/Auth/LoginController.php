<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PrivacyPolicy;
use App\Providers\RouteServiceProvider;
use Google\Service\Docs\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';


    public function storeFcmToken(Request $request) {
        Admin::find(Auth::user()->id)->update([
            'fcm_token'=>$request->fcm_token
        ]);

        return response([], 200);
    }

    public function privacyPolicy() {
        $privacy_policy = PrivacyPolicy::first();
        return view('privacy_policy', compact('privacy_policy'));
    }
}
