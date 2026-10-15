<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];
}
