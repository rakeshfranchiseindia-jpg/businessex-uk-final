<?php

namespace App\Models;

class ContentTag extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'content_tags';

    protected $primaryKey = 'tag_id';

    protected $fillable = [
        'tag_name',
        'tag_slug',
        'tag_status',
    ];


    public function assignments()
    {
        return $this->hasMany(ContentTagAssigned::class, 'tag_id', 'tag_id');
    }

}
