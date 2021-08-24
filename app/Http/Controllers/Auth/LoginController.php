<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function storeFcmToken(Request $request) {
        Admin::where('id', $request->id)->update([
            'fcm_token'=>$request->fcm_token
        ]);

        return response([], 200);
    }
}
