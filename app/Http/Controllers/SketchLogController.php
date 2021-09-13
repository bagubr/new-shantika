<?php

namespace App\Http\Controllers;

use App\Models\SketchLog;
use Illuminate\Http\Request;

class SketchLogController extends Controller
{
    public function index(Request $request) {
        return view('sketch.log', [
            'logs'=>SketchLog::get()
        ]);
    }
}
