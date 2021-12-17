<?php

namespace App\Http\Controllers\v1\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChatRepository;
class ChatController extends Controller
{
    public function index()
    {
        $data = ChatRepository::getAllAgent();
        
        $this->sendSuccessResponse([
            'data'=>$data
        ]);
    }
}
