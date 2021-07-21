<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Repositories\LayoutRepository;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index(Request $request)
    {
        $data = LayoutRepository::findWithChairs($request->id);
        
        $this->sendSuccessResponse([
            'data'=> $data
        ]);
    }
}
