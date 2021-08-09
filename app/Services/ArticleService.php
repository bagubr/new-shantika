<?php

namespace App\Services;

use App\Events\SendingNotificationToTopic;
use App\Models\Article;
use App\Models\Notification;

class ArticleService {
    public static function create($data) {
        $data['image'] = $data['image']->store('article', 'public');

        $article = Article::create($data);

        $notification = new Notification($article->name, $article->description, 'ARTICLE');
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false);

        return $article;
    }
}
        