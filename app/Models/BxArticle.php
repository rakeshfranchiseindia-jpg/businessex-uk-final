<?php

namespace App\Models;

class BxArticle extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_articles';

    protected $primaryKey = 'article_id';

    protected $fillable = [
        'article_title',
        'short_desc',
        'article_content',
        'author_id',
        'image_path',
        'listing_image_path',
        'article_tags',
        'article_status',
        'seo_title',
        'seo_keywords',
        'seo_desc',
        'article_views',
        'article_comments',
        'created_by',
    ];


    public function author()
    {
        return $this->belongsTo(BxAuthor::class, 'author_id', 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class, 'article_id', 'article_id');
    }

    public function images()
    {
        return $this->hasMany(ArticleNewsImage::class, 'content_id', 'article_id');
    }

    public function tagAssignments()
    {
        return $this->hasMany(ContentTagAssigned::class, 'content_id', 'article_id');
    }


    public function scopePublished($query)
    {
        return $query->where('article_status', 1);
    }

    public function scopeMostRead($query)
    {
        return $query->orderBy('article_views', 'desc');
    }

    public function category()
    {
        return $this->belongsTo(IndustryCategory::class, 'category_id', 'cat_id');
    }

}
