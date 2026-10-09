<?php

namespace App\Models;

class FiInvestor extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'fi_investors';

    protected $primaryKey = 'fi_inv_id';

    protected $fillable = [
        'name',
        'email',
    ];

}
