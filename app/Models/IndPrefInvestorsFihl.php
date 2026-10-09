<?php

namespace App\Models;

class IndPrefInvestorsFihl extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'ind_pref_investors_fihl';

    protected $primaryKey = 'inv_ind_pref_id';

    protected $fillable = [
        'investor_profile_id',
        'user_id',
        'parent_category_id',
        'sub_category_id',
        'parent_category_id2',
        'sub_category_id2',
        'parent_category_id3',
        'sub_category_id3',
        'email',
        'invest_min',
        'invest_max',
    ];

    protected $casts = [
        'invest_min' => 'decimal:2',
        'invest_max' => 'decimal:2',
    ];

}
