<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatronResource extends JsonResource
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
            'favor' => [
                'min_favor' => $this->min_favor,
                'max_favor' => $this->max_favor,
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
