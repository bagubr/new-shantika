<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository
{
    public static function all()
    {
        return Faq::all();
    }
}
