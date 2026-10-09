<?php

namespace App\Models;

class ContactComment extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'contact_comment';


    protected $fillable = [
        'contact_id',
        'contact_type',
        'commented_by',
        'contact_status',
        'reminder',
        'reminder_sent',
        'contact_response',
        'comment',
        'comment_date',
        'updated_date',
        'status',
    ];

    protected $casts = [
        'reminder' => 'datetime',
        'comment_date' => 'datetime',
        'updated_date' => 'datetime',
    ];


    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by', 'user_id');
    }

}
