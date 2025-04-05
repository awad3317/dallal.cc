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

    public function getImageUrlAttribute($value)
    {
        if (!str_starts_with($value, 'http')) {
            return config('app.url') . '/' . $value;
        }
        return $value;
    }
}
