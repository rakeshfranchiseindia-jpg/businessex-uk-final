<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';

    protected $fillable = [
        'profile_type',
        'cat_id',
        'title',
        'keyword',
        'description',
        'meta_description',
    ];

    public static function getSeoContent($industrymainType, $industrysubType, $industries, $profileType)
    {
        $seo = [];

        if (is_array($industrymainType) && !empty($industrymainType) && count($industrymainType) === 1) {
            $catId = (count($industrysubType) === 1) ? $industrysubType[0] : $industrymainType[0];

            $seoContent = self::query()
                ->select('title', 'keyword', 'description', 'meta_description')
                ->where('profile_type', $profileType)
                ->where('cat_id', $catId)
                ->first();

            $seo = $seoContent ? $seoContent->toArray() : [];
        }

        return $seo;
    }
}