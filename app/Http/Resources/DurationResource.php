<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DurationResource extends JsonResource
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
            'timing' => [
                'minutes_min' => $this->minutes_min,
                'minutes_max' => $this->minutes_max,
                'average_minutes' => $this->when(
                    $this->minutes_min && $this->minutes_max,
                    fn() => round(($this->minutes_min + $this->minutes_max) / 2)
                ),
            ],
            'display_order' => $this->display_order,
            'metadata' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ],
        ];
    }
}
