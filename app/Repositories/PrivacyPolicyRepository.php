<?php

namespace App\Repositories;

use App\Models\PrivacyPolicy;

class PrivacyPolicyRepository
{
    public static function all()
    {
        return PrivacyPolicy::all();
    }
}
