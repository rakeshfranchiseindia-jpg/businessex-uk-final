<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ChatbotLead extends Model
{
    protected $table = 'chatbot_leads';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];
}
