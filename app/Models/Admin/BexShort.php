<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BexShort extends Model
{
    protected $table = 'bex_shorts';

    protected $primaryKey = 'bex_id';

    protected $guarded = ['bex_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }
}
