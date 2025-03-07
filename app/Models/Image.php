<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['ad_id', 'image_url'];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
