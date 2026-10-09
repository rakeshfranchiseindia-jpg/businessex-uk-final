<?php

namespace App\Models;

class BxIndustryReport extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_industryreports';

    protected $primaryKey = 'industryreport_id';

    protected $fillable = [
        'industryreport_title',
        'industryreport_home_title',
        'industry_sector',
        'short_desc',
        'industryreport_content',
        'author_id',
        'image_path',
        'listing_image_path',
        'industryreport_pdf_path',
        'industryreport_tags',
        'industryreport_status',
        'seo_title',
        'seo_keywords',
        'seo_desc',
        'industryreport_views',
        'industryreport_comments',
        'created_by',
    ];


    public function author()
    {
        return $this->belongsTo(BxAuthor::class, 'author_id', 'author_id');
    }

}
