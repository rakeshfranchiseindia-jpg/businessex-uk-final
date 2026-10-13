<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleNewsImage extends Model
{
    protected $table = 'article_news_images';

    protected $fillable = [
        'content_id',
        'type',
        'img_path',
        'is_active',
    ];
}
