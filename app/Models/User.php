<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email', 
        'password',
        'phone_number', 
        'image', 
        'last_login', 
        'email_verified',
        'role_id',
        'is_banned',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'email_verified',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
    * Get all ads created by the user.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    /**
    * Get all bids made by the user.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
    * Get all comments posted by the user.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
    * Get all view records associated with the user (e.g., for tracking ad views).
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function views()
    {
        return $this->hasMany(View::class);
    }

    /**
    * Get all ads liked by the user (many-to-many relationship via likes pivot table).
    * Includes timestamps to track when likes were created/updated.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function likes()
    {
        return $this->belongsToMany(Ad::class, 'likes', 'user_id', 'ad_id')->withTimestamps();
    }

    /**
    * Get all conversations initiated by the user (as a sender).
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    /**
    * Get all ads favorited by the user (many-to-many relationship via favorites pivot table).
    * Includes timestamps to track when favorites were added.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function favorites()
    {
        return $this->belongsToMany(Ad::class, 'favorites', 'user_id', 'ad_id')->withTimestamps();
    }

    /**
    * Get the role assigned to the user (belongs-to relationship).
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
    * Check if the user has a specific role.
    *
    * @param string $roleName
    * @return bool
    */
    public function has_role(string $roleName): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->name === $roleName;
    }

}
