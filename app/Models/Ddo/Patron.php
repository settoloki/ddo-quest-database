<?php

namespace App\Models\Ddo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patron extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'patrons';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the quests that belong to this patron.
     */
    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class, 'patron_id');
    }

    /**
     * Scope to get patrons with quest counts.
     */
    public function scopeWithQuestCount($query)
    {
        return $query->withCount('quests');
    }

    /**
     * Get the total favor available from this patron.
     */
    public function getTotalFavorAttribute(): int
    {
        return $this->quests()->sum('base_favor');
    }
}
