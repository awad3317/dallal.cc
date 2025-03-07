<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class region extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }
    
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
