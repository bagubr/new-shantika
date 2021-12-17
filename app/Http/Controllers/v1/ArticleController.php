<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ArticleRepository;
class ArticleController extends Controller
{   
    public function articleDetail($id)
    {
        $data = ArticleRepository::findById($id);
        
        $this->sendSuccessResponse([
            'article'=>$data
        ]);
    }
}
