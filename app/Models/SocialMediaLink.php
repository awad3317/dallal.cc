<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    protected $fillable = [
        'platform',
        'url',
        'icon',
        'is_active',
        'order'
    ];
}
