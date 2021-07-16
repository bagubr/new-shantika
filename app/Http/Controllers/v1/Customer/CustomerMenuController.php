<?php

namespace App\Http\Controllers\v1\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerMenu;
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'customer_menus'=>CustomerMenu::all()
        ]);
    }
}
