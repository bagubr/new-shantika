<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'chats'=>Chat::all()
        ]);
    }
}
