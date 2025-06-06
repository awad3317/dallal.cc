<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;


/**
* Ad model representing classified advertisements.
*/
class Ad extends Model
{
    use FilterQueryString;
    use HasFactory;
    use Sluggable;
    

    /**
     * Filterable fields for query string filtering.
     *
     * @var array
     */
    protected $filters = ['like','sort'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'region_id',
        'title',
        'description',
        'price',
        'primary_image',
        'status',
        'sale_option_id',
        'views',
        'likes',
        'verified'
    ];

     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
                'separator' => '-',
                'method' => function ($string, $separator) {
                $slug = preg_replace('/\s+/', '-', trim($string));
                
                $slug = preg_replace('/[^\p{Arabic}\p{Latin}\d\-_]/u', '', $slug);
                
                $slug = preg_replace('/\-+/', '-', $slug);
                
                return trim($slug, '-');
            }
            ]
        ];
    }

    public function getPrimaryImageAttribute($value)
    {
        if (!str_starts_with($value, 'http')) {
            return config('app.url') . '/' . $value;
        }
        return $value;
    }

    /**
     * Get the user who created the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the region where the ad is located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(region::class);
    }

    /**
     * Get the sale option for the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function saleOption()
    {
        return $this->belongsTo(SaleOption::class);
    }

    /**
     * Get all bids placed on this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get all images associated with the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get all comments on the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all view records for the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function views()
    {
        return $this->hasMany(View::class);
    }

    /**
     * Get all likes for the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get all conversations related to this ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

}
