<?php

namespace App\Models;

class IndustryCategory extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'industry_categories';

    protected $primaryKey = 'cat_id';

    protected $fillable = [
        'category_name',
        'category_slug',
        'parent_id',
        'category_status',
    ];


    public function parent()
    {
        return $this->belongsTo(IndustryCategory::class, 'parent_id', 'cat_id');
    }

    public function children()
    {
        return $this->hasMany(IndustryCategory::class, 'parent_id', 'cat_id');
    }

    public function scopeActive($query)
    {
        return $query->where('category_status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('category_name', 'asc');
    }

}
