<?php

namespace App\Repositories;
use App\Models\Article;
class ArticleRepository {

    public static function getAll()
    {
        return Article::orderBy('id', 'desc')->get();
    }
}
        