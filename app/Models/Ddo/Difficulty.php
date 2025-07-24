<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Difficulty extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'difficulties';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'multiplier',
        'first_time_bonus_percent',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'multiplier' => 'decimal:2',
        'first_time_bonus_percent' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the XP rewards for this difficulty.
     */
    public function xpRewards(): HasMany
    {
        return $this->hasMany(QuestXpReward::class, 'difficulty_id');
    }

    /**
     * Scope to get difficulties ordered by their sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope to get difficulties that provide first time bonuses.
     */
    public function scopeWithFirstTimeBonus($query)
    {
        return $query->whereNotNull('first_time_bonus_percent');
    }

    /**
     * Calculate XP with difficulty multiplier.
     */
    public function calculateXp(int $baseXp, bool $includeFirstTimeBonus = false): int
    {
        $xp = $baseXp * $this->multiplier;
        
        if ($includeFirstTimeBonus && $this->first_time_bonus_percent) {
            $xp *= (1 + ($this->first_time_bonus_percent / 100));
        }
        
        return (int) round($xp);
    }

    /**
     * Get display name with multiplier.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->multiplier}x)";
    }

    /**
     * Check if this difficulty provides a first time bonus.
     */
    public function getHasFirstTimeBonusAttribute(): bool
    {
        return !is_null($this->first_time_bonus_percent);
    }
}
