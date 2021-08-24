<?php

namespace App\Services;

use App\Events\SendingNotificationToTopic;
use App\Models\Article;
use App\Models\Notification;
use App\Utils\NotificationMessage;

class ArticleService {
    public static function create($data) {
        $data['image'] = $data['image']->store('article', 'public');

        $article = Article::create($data);

        $notification = new Notification([
            'title'=>NotificationMessage::newArticle($article->name, $article->description)[0],
            'body'=>NotificationMessage::newArticle($article->name, $article->description)[1],
            'reference_id'=>$article->id,
            'type'=>Notification::TYPE4
        ]);
        SendingNotificationToTopic::dispatch($notification, Notification::TOPIC1, false, [
            'reference_id'=>(string) $article->id,
            'type'=>Notification::TYPE4
        ]);

        return $article;
    }
}
        