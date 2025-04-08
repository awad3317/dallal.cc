<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOption extends Model
{
    protected $fillable = ['name','description'];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
