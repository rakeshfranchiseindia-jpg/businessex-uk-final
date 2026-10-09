<?php

namespace App\Models;

class ArticleNewsImage extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'article_news_images';


    protected $fillable = [
        'content_id',
        'type',
        'img_path',
        'is_active',
    ];

}
