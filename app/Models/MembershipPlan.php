<?php

namespace App\Models;

class MembershipPlan extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'membership_plans';

    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'plan_type',
        'plan_name',
        'plan_desc',
        'profile_type',
        'profile_name',
        'validity_in_days',
        'plan_amount',
        'interaction_credits',
        'instant_responses',
        'is_active',
        'deactivated_at',
    ];

    protected $casts = [
        'deactivated_at' => 'datetime',
    ];

}
