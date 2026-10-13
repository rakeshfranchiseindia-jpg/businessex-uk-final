<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiInvestor extends Model
{
    protected $table = 'fi_investors';

    protected $primaryKey = 'fi_inv_id';

    protected $fillable = [
        'name',
        'email',
    ];
}
