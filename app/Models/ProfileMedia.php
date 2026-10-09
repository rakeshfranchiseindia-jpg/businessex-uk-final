<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileMedia extends Model
{
    protected $table = 'profile_media';

    protected $fillable = [
        'user_id',
        'profile_type',
        'profile_id',
        'field_name',
        'file_index',
        'media_type',
        'disk',
        'object_key',
        'url',
        'original_name',
        'mime_type',
        'file_size',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'file_index' => 'integer',
            'file_size' => 'integer',
            'is_public' => 'boolean',
        ];
    }
}
