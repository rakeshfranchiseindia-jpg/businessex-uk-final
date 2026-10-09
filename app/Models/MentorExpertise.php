<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MentorExpertise extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    protected $table = 'mentor_expertise';

    protected $primaryKey = 'mentor_expert_id';

    protected $fillable = [
        'mentor_id',
        'user_id',
        'exp_years',
        'exp_industry',
    ];

}
