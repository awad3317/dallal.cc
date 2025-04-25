<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'meta_description',
        'meta_keywords',
        'email',
        'phone',
        'address',
        'is_maintenance',
        'maintenance_message',
        'working_hours'
    ];
    public function getLogoPathAttribute($value)
    {
        if (!str_starts_with($value, 'http')) {
            return config('app.url') . '/' . $value;
        }
        return $value;
    }
    public function getFaviconPathAttribute($value)
    {
        if (!str_starts_with($value, 'http')) {
            return config('app.url') . '/' . $value;
        }
        return $value;
    }
}
