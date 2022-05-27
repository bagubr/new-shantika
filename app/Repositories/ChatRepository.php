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
        return Chat::whereType('AGENT')->get()
        ->map(function ($item)
        {
            $item->value = $item->link.$item->value;
            return $item;
        });
    }
    
    public static function getAllCust()
    {
        return Chat::whereType('CUST')->get()
        ->map(function ($item)
        {
            $item->value = $item->link.$item->value;
            return $item;
        });
    }
}
