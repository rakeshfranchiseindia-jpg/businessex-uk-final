<?php

namespace App\Models;

class BusinessImage extends \Illuminate\Database\Eloquent\Model
{
    // The 'type' column is a tinyint, not a string — must match StartupImage's convention.
    public const TYPE_IMAGE = 1;
    public const TYPE_DOCUMENT = 2;

    protected $table = 'business_images';

    protected $primaryKey = 'business_image_id';

    protected $fillable = [
        'business_id',
        'type',
        'business_img_path',
        'business_img_name',
        'is_active',
    ];


    public function business()
    {
        return $this->belongsTo(ProfileBusiness::class, 'business_id', 'business_id');
    }

}
