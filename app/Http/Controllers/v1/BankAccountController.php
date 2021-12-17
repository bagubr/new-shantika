<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'banks'=>BankAccount::all()
        ]);
    }
}
