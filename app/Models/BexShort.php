<?php

namespace App\Models;

class BexShort extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bex_shorts';

    protected $primaryKey = 'bex_id';

    protected $fillable = [
        'bex_title',
        'bex_description',
        'author_id',
        'image_path',
        'reference_page_name',
        'reference_page_link',
        'associated_tag',
        'seo_title',
        'seo_keywords',
        'seo_desc',
        'status',
        'created_by',
    ];


    public function author()
    {
        return $this->belongsTo(BxAuthor::class, 'author_id', 'author_id');
    }

}
