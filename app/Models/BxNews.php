<?php

namespace App\Models;

class BxNews extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_news';

    protected $primaryKey = 'news_id';

    protected $fillable = [
        'news_title',
        'short_desc',
        'news_content',
        'author_id',
        'image_path',
        'listing_image_path',
        'news_tags',
        'news_status',
        'seo_title',
        'seo_keywords',
        'seo_desc',
        'news_views',
        'news_comments',
        'created_by',
    ];


    public function author()
    {
        return $this->belongsTo(BxAuthor::class, 'author_id', 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id', 'news_id');
    }

    public function images()
    {
        return $this->hasMany(ArticleNewsImage::class, 'content_id', 'news_id');
    }

    public function tagAssignments()
    {
        return $this->hasMany(ContentTagAssigned::class, 'content_id', 'news_id');
    }

}
