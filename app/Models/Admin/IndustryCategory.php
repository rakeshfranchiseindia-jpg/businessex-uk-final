<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class IndustryCategory extends Model
{
    protected $table = 'industry_categories';

    protected $primaryKey = 'cat_id';

    protected $guarded = ['cat_id'];
}
