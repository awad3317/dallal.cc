<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

/**
 * Region model representing geographical regions with hierarchical relationships.
 */
class region extends Model
{
    use FilterQueryString;

    /**
     * Filterable fields for query string filtering.
     *
     * @var array
     */
    protected $filters = ['like'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id','latitude', 'longitude'];

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
