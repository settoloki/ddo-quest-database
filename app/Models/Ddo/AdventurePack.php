<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AdventurePack extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'adventure_packs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'purchase_type',
        'release_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'release_date' => 'date',
    ];

    /**
     * Get the quests that belong to this adventure pack.
     */
    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class, 'adventure_pack_id');
    }

    /**
     * Scope to filter by purchase type.
     */
    public function scopeByPurchaseType($query, string $purchaseType)
    {
        return $query->where('purchase_type', $purchaseType);
    }

    /**
     * Scope to get free-to-play adventure packs.
     */
    public function scopeFreeToPlay($query)
    {
        return $query->where('purchase_type', 'Free to Play');
    }

    /**
     * Scope to get premium adventure packs.
     */
    public function scopePremium($query)
    {
        return $query->whereIn('purchase_type', ['Premium', 'VIP', 'Expansion']);
    }

    /**
     * Check if the adventure pack is free to play.
     */
    public function getIsFreeToPlayAttribute(): bool
    {
        return $this->purchase_type === 'Free to Play';
    }

    /**
     * Get the age of the adventure pack in years.
     */
    public function getAgeInYearsAttribute(): ?float
    {
        return $this->release_date ? $this->release_date->diffInYears(Carbon::now()) : null;
    }
}
