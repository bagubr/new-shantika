<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index() {
        $this->sendSuccessResponse([
            'faqs'=>Faq::all()
        ]);
    }
}
