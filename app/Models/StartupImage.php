<?php

namespace App\Models;

class StartupImage extends \Illuminate\Database\Eloquent\Model
{
    public const TYPE_IMAGE = 1;
    public const TYPE_DOCUMENT = 2;

    protected $table = 'startup_images';

    protected $primaryKey = 'startup_image_id';

    protected $fillable = [
        'startup_id',
        'type',
        'startup_img_path',
        'startup_img_name',
        'is_active',
    ];


    public function startup()
    {
        return $this->belongsTo(ProfileStartup::class, 'startup_id', 'startup_id');
    }

}
