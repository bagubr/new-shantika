<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index() {
        return $this->sendSuccessResponse([
            'privacy_policy'=>PrivacyPolicy::first()
        ]);
    }
}
