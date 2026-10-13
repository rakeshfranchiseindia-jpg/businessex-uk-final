<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $primaryKey = 'newsID';

    protected $fillable = [
        'homeTitle',
        'title',
        'shortDesc',
        'content',
        'image',
        'tags',
        'newskeywords',
        'totalComment',
        'totalVotes',
        'views',
        'status',
        'seoTitle',
        'seoKeywords',
        'seoDescription',
        'news_date',
    ];

    protected $casts = [
        'news_date' => 'datetime',
    ];
}
