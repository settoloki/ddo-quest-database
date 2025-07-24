<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = QuestResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total() ?? $this->collection->count(),
                'count' => $this->collection->count(),
                'per_page' => $this->perPage() ?? null,
                'current_page' => $this->currentPage() ?? 1,
                'last_page' => $this->lastPage() ?? 1,
                'has_more_pages' => $this->hasMorePages() ?? false,
            ],
            'links' => [
                'first' => $this->url(1) ?? null,
                'last' => $this->url($this->lastPage()) ?? null,
                'prev' => $this->previousPageUrl() ?? null,
                'next' => $this->nextPageUrl() ?? null,
            ],
            'filters' => $this->when(
                $request->hasAny(['level', 'patron_id', 'adventure_pack_id', 'location_id', 'difficulty_id']),
                fn() => [
                    'applied_filters' => array_filter([
                        'level' => $request->input('level'),
                        'patron_id' => $request->input('patron_id'),
                        'adventure_pack_id' => $request->input('adventure_pack_id'),
                        'location_id' => $request->input('location_id'),
                        'difficulty_id' => $request->input('difficulty_id'),
                        'search' => $request->input('search'),
                    ]),
                ]
            ),
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
            'timestamp' => now()->toISOString(),
        ];
    }
}
