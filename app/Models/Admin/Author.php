<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'bx_author';

    protected $primaryKey = 'author_id';

    protected $guarded = ['author_id'];
}
