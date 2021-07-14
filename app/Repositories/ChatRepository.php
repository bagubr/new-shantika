<?php

namespace App\Repositories;

use App\Models\Chat;

class ChatRepository
{
    public static function all()
    {
        return Chat::orderBy('id', 'desc')->get();
    }
}
