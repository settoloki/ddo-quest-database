<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'location_type' => $this->location_type,
            'hierarchy' => [
                'parent' => new self($this->whenLoaded('parent')),
                'children' => self::collection($this->whenLoaded('children')),
                'ancestors' => self::collection($this->whenLoaded('ancestors')),
                'descendants' => self::collection($this->whenLoaded('descendants')),
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
