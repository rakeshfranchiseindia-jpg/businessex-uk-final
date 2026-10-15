<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'content_tags';

    protected $primaryKey = 'tag_id';

    protected $guarded = ['tag_id'];
}
