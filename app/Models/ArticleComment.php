<?php

namespace App\Models;

class ArticleComment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'articles_comments';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'article_id',
        'comment_name',
        'comment_email',
        'comment_detail',
        'comment_status',
    ];


    public function article()
    {
        return $this->belongsTo(BxArticle::class, 'article_id', 'article_id');
    }

}
