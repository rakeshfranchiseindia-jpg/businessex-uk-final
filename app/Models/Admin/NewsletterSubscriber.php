<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $table = 'businessex_newsletter';

    protected $primaryKey = 'newsletter_id';

    protected $guarded = ['newsletter_id'];
}
