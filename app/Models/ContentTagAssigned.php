<?php

namespace App\Models;

class ContentTagAssigned extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'content_tags_assigned';

    protected $primaryKey = 'assigned_id';

    protected $fillable = [
        'content_type',
        'content_id',
        'tag_id',
    ];


    public function tag()
    {
        return $this->belongsTo(ContentTag::class, 'tag_id', 'tag_id');
    }

}
