<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\TermAndCondition;
use Illuminate\Http\Request;

class TermAndConditionController extends Controller
{
    public function index() {
        return $this->sendSuccessResponse([
            'term_and_condition'=>TermAndCondition::first()
        ]);
    }
}
