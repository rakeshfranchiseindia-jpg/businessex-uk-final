<?php

namespace App\Models;

class NewsComment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'news_comments';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'news_id',
        'comment_name',
        'comment_email',
        'comment_detail',
        'comment_status',
    ];


    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'newsID');
    }

}
