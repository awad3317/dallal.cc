<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bid model representing user bids on advertisements.
 */
class Bid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ad_id', 'user_id', 'amount'];

    /**
     * Get the advertisement associated with this bid.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * Get the user who made this bid.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
