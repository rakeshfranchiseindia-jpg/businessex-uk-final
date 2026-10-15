<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustryReport extends Model
{
    protected $table = 'bx_industryreports';

    protected $primaryKey = 'industryreport_id';

    protected $guarded = ['industryreport_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(IndustryCategory::class, 'industry_sector', 'cat_id');
    }
}
