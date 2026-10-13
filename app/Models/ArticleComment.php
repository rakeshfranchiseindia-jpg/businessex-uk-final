<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
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
