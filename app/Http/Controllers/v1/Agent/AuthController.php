<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\AuthController as BaseAuthController;
use App\Http\Requests\Api\ApiRegisterCustomerRequest;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends BaseAuthController
{
    
}