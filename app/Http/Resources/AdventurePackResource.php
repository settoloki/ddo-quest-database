<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdventurePackResource extends JsonResource
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
            'description' => $this->description,
            'levels' => [
                'min_level' => $this->min_level,
                'max_level' => $this->max_level,
            ],
            'release_info' => [
                'release_date' => $this->release_date?->toDateString(),
                'is_free' => $this->is_free,
            ],
            'quests_count' => $this->when(
                isset($this->quests_count),
                $this->quests_count
            ),
            'quests' => QuestResource::collection($this->whenLoaded('quests')),
            'metadata' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
        ];
    }
}
