<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Category model representing a hierarchical classification system for ads.
 */
class Category extends Model
{
    use Sluggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'icon'];

     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
                'separator' => '-',
                'method' => function ($string, $separator) {
                $slug = preg_replace('/\s+/', '-', trim($string));
                
                $slug = preg_replace('/[^\p{Arabic}\d\-_]/u', '', $slug);
                
                $slug = preg_replace('/\-+/', '-', $slug);
                
                return trim($slug, '-');
            }
            ]
        ];
    }

    /**
     * Get the parent category of this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    /**
     * Get the child categories of this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all ads belonging to this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
