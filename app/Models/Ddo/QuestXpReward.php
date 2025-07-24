<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestXpReward extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'quest_xp_rewards';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'quest_id',
        'difficulty_id',
        'is_epic',
        'is_legendary',
        'base_xp',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_epic' => 'boolean',
        'is_legendary' => 'boolean',
        'base_xp' => 'integer',
    ];

    /**
     * Get the quest that this XP reward belongs to.
     */
    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class, 'quest_id');
    }

    /**
     * Get the difficulty that this XP reward belongs to.
     */
    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class, 'difficulty_id');
    }

    /**
     * Scope to filter by quest type.
     */
    public function scopeHeroic($query)
    {
        return $query->where('is_epic', false)->where('is_legendary', false);
    }

    /**
     * Scope to filter by epic quest type.
     */
    public function scopeEpic($query)
    {
        return $query->where('is_epic', true)->where('is_legendary', false);
    }

    /**
     * Scope to filter by legendary quest type.
     */
    public function scopeLegendary($query)
    {
        return $query->where('is_legendary', true);
    }

    /**
     * Scope to filter by difficulty.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        if (is_string($difficulty)) {
            return $query->whereHas('difficulty', function ($q) use ($difficulty) {
                $q->where('name', $difficulty);
            });
        }

        return $query->where('difficulty_id', $difficulty);
    }

    /**
     * Calculate the actual XP with difficulty multiplier.
     */
    public function getCalculatedXpAttribute(): int
    {
        return $this->difficulty->calculateXp($this->base_xp);
    }

    /**
     * Calculate XP with first time bonus.
     */
    public function getXpWithFirstTimeBonusAttribute(): int
    {
        return $this->difficulty->calculateXp($this->base_xp, true);
    }

    /**
     * Get the quest type as a string.
     */
    public function getQuestTypeAttribute(): string
    {
        if ($this->is_legendary) {
            return 'Legendary';
        }
        
        if ($this->is_epic) {
            return 'Epic';
        }
        
        return 'Heroic';
    }

    /**
     * Get a human-readable description of this XP reward.
     */
    public function getDescriptionAttribute(): string
    {
        $type = $this->quest_type;
        $difficulty = $this->difficulty->name;
        $xp = number_format($this->calculated_xp);
        
        return "{$type} {$difficulty}: {$xp} XP";
    }
}
