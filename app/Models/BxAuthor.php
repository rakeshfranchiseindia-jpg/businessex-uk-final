<?php

namespace App\Models;

class BxAuthor extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'bx_author';

    protected $primaryKey = 'author_id';

    protected $fillable = [
        'author_name',
        'author_email',
        'author_desig',
        'author_dept',
        'is_active',
    ];


    public function articles()
    {
        return $this->hasMany(BxArticle::class, 'author_id', 'author_id');
    }

    public function news()
    {
        return $this->hasMany(BxNews::class, 'author_id', 'author_id');
    }

    public function industryReports()
    {
        return $this->hasMany(BxIndustryReport::class, 'author_id', 'author_id');
    }

    public function shorts()
    {
        return $this->hasMany(BexShort::class, 'author_id', 'author_id');
    }

}
