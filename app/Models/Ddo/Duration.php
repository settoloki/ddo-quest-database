<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Database\Factories\Ddo\DurationFactory;

class Duration extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return DurationFactory::new();
    }

    /**
     * The table associated with the model.
     */
    protected $table = 'durations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'estimated_minutes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'estimated_minutes' => 'integer',
    ];

    /**
     * Get the quests that belong to this duration.
     */
    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class, 'duration_id');
    }

    /**
     * Scope to get durations ordered by estimated time.
     */
    public function scopeOrderByTime($query)
    {
        return $query->orderBy('estimated_minutes');
    }

    /**
     * Get duration display name with estimated time.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->estimated_minutes 
            ? "{$this->name} ({$this->estimated_minutes} min)" 
            : $this->name;
    }
}
