<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'levels' => [
                'heroic' => $this->heroic_level,
                'epic' => $this->epic_level,
                'legendary' => $this->legendary_level,
            ],
            'duration' => new DurationResource($this->whenLoaded('duration')),
            'patron' => new PatronResource($this->whenLoaded('patron')),
            'adventure_pack' => new AdventurePackResource($this->whenLoaded('adventurePack')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'base_favor' => $this->base_favor,
            'extreme_challenge' => $this->extreme_challenge,
            'content' => [
                'overview' => $this->overview,
                'objectives' => $this->objectives,
                'tips' => $this->tips,
            ],
            'wiki_url' => $this->wiki_url,
            'xp_rewards' => QuestXpRewardResource::collection($this->whenLoaded('xpRewards')),
            'calculated_xp' => $this->when(
                $this->calculated_xp ?? false,
                fn() => [
                    'base_xp' => $this->calculated_xp['base_xp'] ?? null,
                    'difficulty_multiplier' => $this->calculated_xp['difficulty_multiplier'] ?? null,
                    'total_xp' => $this->calculated_xp['total_xp'] ?? null,
                    'difficulty' => $this->calculated_xp['difficulty'] ?? null,
                ]
            ),
            'metadata' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'version' => '1.0',
            'api_version' => 'v1',
        ];
    }
}
