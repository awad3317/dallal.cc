<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * Region model representing geographical regions with hierarchical relationships.
 */
class region extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id','latitude', 'longitude'];

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
     * Get the parent region of this region (for hierarchical structures).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(region::class, 'parent_id');
    }

    /**
     * Get the child regions of this region (for hierarchical structures).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(region::class, 'parent_id');
    }

    /**
     * Get all ads associated with this region.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
