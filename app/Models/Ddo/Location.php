<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'area_type',
        'parent_location_id',
    ];

    /**
     * Get the parent location.
     */
    public function parentLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_location_id');
    }

    /**
     * Get the child locations.
     */
    public function childLocations(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_location_id');
    }

    /**
     * Get the quests that belong to this location.
     */
    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class, 'location_id');
    }

    /**
     * Scope to get root locations (no parent).
     */
    public function scopeRootLocations($query)
    {
        return $query->whereNull('parent_location_id');
    }

    /**
     * Scope to filter by area type.
     */
    public function scopeByAreaType($query, string $areaType)
    {
        return $query->where('area_type', $areaType);
    }

    /**
     * Get all descendant locations recursively.
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->childLocations as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Get the full path of the location (including parents).
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $current = $this;
        
        while ($current->parentLocation) {
            $current = $current->parentLocation;
            array_unshift($path, $current->name);
        }
        
        return implode(' > ', $path);
    }

    /**
     * Check if this location is a root location.
     */
    public function getIsRootAttribute(): bool
    {
        return is_null($this->parent_location_id);
    }
}
