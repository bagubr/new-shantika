<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'social_medias'=>SocialMedia::all()
        ]);
    }
}
