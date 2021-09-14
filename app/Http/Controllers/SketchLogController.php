<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Agency;
use App\Models\Fleet;
use App\Models\SketchLog;
use Illuminate\Http\Request;

class SketchLogController extends Controller
{
    public function index(Request $request) {
        return view('sketch.log', [
            'admins'=>Admin::select('id', 'name')->get(),
            'fleets'=>Fleet::select('id', 'name')->orderBy('name')->get(),
            'agencies'=>Agency::select('id', 'name')->get(),
            'logs'=>SketchLog::orderBy('created_at', 'desc')->get()
        ]);
    }
}
