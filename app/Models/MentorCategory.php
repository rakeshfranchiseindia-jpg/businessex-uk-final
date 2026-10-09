<?php

namespace App\Models;

class MentorCategory extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'mentor_categories';

    protected $primaryKey = 'mentor_category_id';

    protected $fillable = [
        'mentor_category_name',
        'category_slug',
        'mentor_parent_id',
        'mentor_category_status',
    ];


    public function children()
    {
        return $this->hasMany(MentorCategory::class, 'mentor_parent_id', 'mentor_category_id');
    }

}
