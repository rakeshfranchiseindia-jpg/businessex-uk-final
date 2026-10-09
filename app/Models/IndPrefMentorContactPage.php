<?php

namespace App\Models;

class IndPrefMentorContactPage extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'ind_pref_mentor_contact_page';

    protected $primaryKey = 'contact_ind_pref_id';

    protected $fillable = [
        'contact_id',
        'user_id',
        'profile_id',
        'parent_category_id',
        'sub_category_id',
        'profile_status',
        'contact_type',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
