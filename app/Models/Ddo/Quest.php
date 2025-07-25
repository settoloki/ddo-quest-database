<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Database\Factories\Ddo\QuestFactory;

class Quest extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return QuestFactory::new();
    }

    /**
     * The table associated with the model.
     */
    protected $table = 'quests';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'heroic_level',
        'epic_level',
        'legendary_level',
        'duration_id',
        'patron_id',
        'adventure_pack_id',
        'location_id',
        'base_favor',
        'extreme_challenge',
        'overview',
        'objectives',
        'tips',
        'wiki_url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'heroic_level' => 'integer',
        'epic_level' => 'integer',
        'legendary_level' => 'integer',
        'base_favor' => 'integer',
        'extreme_challenge' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quest) {
            if (!$quest->slug) {
                $quest->slug = Str::slug($quest->name);
            }
        });

        static::updating(function ($quest) {
            if ($quest->isDirty('name') && !$quest->isDirty('slug')) {
                $quest->slug = Str::slug($quest->name);
            }
        });
    }

    /**
     * Get the duration that this quest belongs to.
     */
    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class, 'duration_id');
    }

    /**
     * Get the patron that this quest belongs to.
     */
    public function patron(): BelongsTo
    {
        return $this->belongsTo(Patron::class, 'patron_id');
    }

    /**
     * Get the adventure pack that this quest belongs to.
     */
    public function adventurePack(): BelongsTo
    {
        return $this->belongsTo(AdventurePack::class, 'adventure_pack_id');
    }

    /**
     * Get the location that this quest belongs to.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * Get the XP rewards for this quest.
     */
    public function xpRewards(): HasMany
    {
        return $this->hasMany(QuestXpReward::class, 'quest_id');
    }

    /**
     * Get the difficulties available for this quest.
     */
    public function difficulties(): BelongsToMany
    {
        return $this->belongsToMany(Difficulty::class, 'quest_difficulties', 'quest_id', 'difficulty_id')
                    ->withTimestamps();
    }

    /**
     * Scope to filter by heroic level.
     */
    public function scopeByHeroicLevel($query, int $level)
    {
        return $query->where('heroic_level', $level);
    }

    /**
     * Scope to filter by heroic level range.
     */
    public function scopeByHeroicLevelRange($query, int $minLevel, int $maxLevel)
    {
        return $query->whereBetween('heroic_level', [$minLevel, $maxLevel]);
    }

    /**
     * Scope to filter by epic level.
     */
    public function scopeByEpicLevel($query, int $level)
    {
        return $query->where('epic_level', $level);
    }

    /**
     * Scope to filter by legendary level.
     */
    public function scopeByLegendaryLevel($query, int $level)
    {
        return $query->where('legendary_level', $level);
    }

    /**
     * Scope to filter by duration.
     */
    public function scopeByDuration($query, $duration)
    {
        if (is_string($duration)) {
            return $query->whereHas('duration', function ($q) use ($duration) {
                $q->where('name', $duration);
            });
        }

        return $query->where('duration_id', $duration);
    }

    /**
     * Scope to filter by patron.
     */
    public function scopeByPatron($query, $patron)
    {
        if (is_string($patron)) {
            return $query->whereHas('patron', function ($q) use ($patron) {
                $q->where('name', $patron);
            });
        }

        return $query->where('patron_id', $patron);
    }

    /**
     * Scope to filter by adventure pack.
     */
    public function scopeByAdventurePack($query, $adventurePack)
    {
        if (is_string($adventurePack)) {
            return $query->whereHas('adventurePack', function ($q) use ($adventurePack) {
                $q->where('name', $adventurePack);
            });
        }

        return $query->where('adventure_pack_id', $adventurePack);
    }

    /**
     * Scope to get only free-to-play quests.
     */
    public function scopeFreeToPlay($query)
    {
        return $query->whereHas('adventurePack', function ($q) {
            $q->where('purchase_type', 'Free to Play');
        });
    }

    /**
     * Scope to get only premium quests.
     */
    public function scopePremium($query)
    {
        return $query->whereHas('adventurePack', function ($q) {
            $q->whereIn('purchase_type', ['Premium', 'VIP', 'Expansion']);
        });
    }

    /**
     * Scope to search by name.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope to get extreme challenge quests.
     */
    public function scopeExtremeChallenge($query)
    {
        return $query->where('extreme_challenge', true);
    }

    /**
     * Get the primary level for this quest.
     */
    public function getPrimaryLevelAttribute(): ?int
    {
        return $this->heroic_level ?? $this->epic_level ?? $this->legendary_level;
    }

    /**
     * Get the quest type based on available levels.
     */
    public function getQuestTypeAttribute(): string
    {
        if ($this->legendary_level) {
            return 'Legendary';
        }
        
        if ($this->epic_level) {
            return 'Epic';
        }
        
        if ($this->heroic_level) {
            return 'Heroic';
        }

        return 'Unknown';
    }

    /**
     * Check if quest is free to play.
     */
    public function getIsFreeToPlayAttribute(): bool
    {
        return $this->adventurePack?->is_free_to_play ?? false;
    }

    /**
     * Get XP for a specific difficulty.
     */
    public function getXpForDifficulty(int $difficultyId, bool $isEpic = false, bool $isLegendary = false): ?int
    {
        $reward = $this->xpRewards()
            ->where('difficulty_id', $difficultyId)
            ->where('is_epic', $isEpic)
            ->where('is_legendary', $isLegendary)
            ->first();

        return $reward?->base_xp;
    }

    /**
     * Get all available XP rewards grouped by type.
     */
    public function getGroupedXpRewardsAttribute(): array
    {
        $rewards = [
            'heroic' => [],
            'epic' => [],
            'legendary' => []
        ];

        foreach ($this->xpRewards as $reward) {
            if ($reward->is_legendary) {
                $rewards['legendary'][] = $reward;
            } elseif ($reward->is_epic) {
                $rewards['epic'][] = $reward;
            } else {
                $rewards['heroic'][] = $reward;
            }
        }

        return $rewards;
    }
}
