<?php

namespace App\Models;

class NewsList extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'news_list';

    protected $primaryKey = 'news_id';

    protected $fillable = [
        'prev_id',
        'news_type',
        'kicker',
        'title',
        'homeTitle',
        'shortDesc',
        'content',
        'image',
        'slug',
        'related_brand',
        'time',
        'views',
        'totalComment',
        'totalVotes',
        'facebook_shared',
        'status',
    ];

    protected $casts = [
        'time' => 'datetime',
    ];

}
