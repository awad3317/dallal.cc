<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Ad extends Model
{
    use FilterQueryString;
    protected $filters = ['like','sort'];
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function region()
    {
        return $this->belongsTo(region::class);
    }

    public function parentRegion()
    {
        return $this->hasOneThrough(
            Region::class, 
            Region::class, 
            'id', 
            'id', 
            'region_id', 
            'parent_id' 
        )->join('regions as parent_regions', 'parent_regions.id', '=', 'regions.parent_id')
        ->select('parent_regions.*', 'regions.id as child_region_id');
    }

    public function saleOption()
    {
        return $this->belongsTo(SaleOption::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'ad_id', 'user_id')->withTimestamps();
    }

}
