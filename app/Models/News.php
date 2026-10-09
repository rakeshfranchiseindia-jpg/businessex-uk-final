<?php

namespace App\Models;

class News extends \Illuminate\Database\Eloquent\Model
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
