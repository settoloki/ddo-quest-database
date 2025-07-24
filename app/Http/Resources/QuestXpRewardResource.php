<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestXpRewardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'level_type' => $this->level_type,
            'xp_rewards' => [
                'base_xp' => $this->base_xp,
                'first_time_bonus' => $this->first_time_bonus,
                'repeat_xp' => $this->repeat_xp,
                'optional_xp' => $this->optional_xp,
            ],
            'difficulty' => new DifficultyResource($this->whenLoaded('difficulty')),
            'calculated_totals' => $this->when(
                $this->difficulty_multiplier ?? false,
                fn() => [
                    'difficulty_multiplier' => (float) $this->difficulty_multiplier,
                    'total_base_xp' => $this->total_base_xp ?? null,
                    'total_first_time' => $this->total_first_time ?? null,
                    'total_repeat_xp' => $this->total_repeat_xp ?? null,
                    'total_optional_xp' => $this->total_optional_xp ?? null,
                ]
            ),
            'metadata' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
        ];
    }
}
