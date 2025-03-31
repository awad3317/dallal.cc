<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Comment model representing user comments on advertisements.
 */
class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ad_id', 'user_id', 'comment_text'];

    /**
     * Get the advertisement that this comment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * Get the user who authored this comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
