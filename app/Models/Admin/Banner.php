<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'bx_dfp_banner';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];
}
