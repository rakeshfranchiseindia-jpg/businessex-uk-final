<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTag extends Model
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
