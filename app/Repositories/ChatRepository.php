<?php

namespace App\Repositories;

use App\Models\Chat;

class ChatRepository
{
    public static function all()
    {
        return Chat::orderBy('id', 'desc')->get();
    }

    public static function getAllAgent()
    {
        return Chat::whereType('AGENT')->get();
    }
    
    public static function getAllCust()
    {
        return Chat::where('type', 'CUST')->get();
    }
}
