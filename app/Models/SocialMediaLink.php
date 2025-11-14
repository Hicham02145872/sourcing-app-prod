<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    protected $fillable = [
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'twitter_url',
        'whatsapp_number',
        'whatsapp_message',
    ];
}
