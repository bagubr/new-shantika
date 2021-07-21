<?php

namespace App\Repositories;
use App\Models\CustomerMenu;
class CustomerMenuRepository {

    public static function getAll()
    {
        return CustomerMenu::get();
    }
}
        