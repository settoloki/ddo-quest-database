<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ddo\Quest;
use App\Http\Requests\StoreQuestRequest;
use App\Http\Requests\UpdateQuestRequest;
use App\Http\Requests\QuestFilterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestController extends Controller
{
    /**
     * Display a listing of quests with filtering and pagination.
     */
    public function index(QuestFilterRequest $request): JsonResponse
    {
        try {
            $query = Quest::with(['duration', 'patron', 'adventurePack', 'location']);

            // Apply filters
            if ($request->filled('level')) {
                $level = (int) $request->level;
                $query->where(function ($q) use ($level) {
                    $q->where('heroic_level', $level)
                      ->orWhere('epic_level', $level)
                      ->orWhere('legendary_level', $level);
                });
            }

            if ($request->filled('heroic_level')) {
                $query->byHeroicLevel((int) $request->heroic_level);
            }

            if ($request->filled('epic_level')) {
                $query->byEpicLevel((int) $request->epic_level);
            }

            if ($request->filled('legendary_level')) {
                $query->byLegendaryLevel((int) $request->legendary_level);
            }

            if ($request->filled('level_range')) {
                $range = explode('-', $request->level_range);
                if (count($range) === 2) {
                    $minLevel = (int) $range[0];
                    $maxLevel = (int) $range[1];
                    $query->byHeroicLevelRange($minLevel, $maxLevel);
                }
            }

            if ($request->filled('patron')) {
                $query->byPatron($request->patron);
            }

            if ($request->filled('duration')) {
                $query->byDuration($request->duration);
            }

            if ($request->filled('adventure_pack')) {
                $query->byAdventurePack($request->adventure_pack);
            }

            if ($request->filled('free_to_play')) {
                if ($request->boolean('free_to_play')) {
                    $query->freeToPlay();
                } else {
                    $query->premium();
                }
            }

            if ($request->filled('extreme_challenge')) {
                $query->extremeChallenge();
            }

            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            $allowedSorts = ['name', 'heroic_level', 'epic_level', 'legendary_level', 'base_favor', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }

            // Paginate results
            $perPage = min((int) $request->get('per_page', 15), 100); // Max 100 per page
            $quests = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $quests->items(),
                'pagination' => [
                    'current_page' => $quests->currentPage(),
                    'last_page' => $quests->lastPage(),
                    'per_page' => $quests->perPage(),
                    'total' => $quests->total(),
                    'from' => $quests->firstItem(),
                    'to' => $quests->lastItem(),
                ],
                'filters_applied' => $request->only([
                    'level', 'heroic_level', 'epic_level', 'legendary_level', 
                    'level_range', 'patron', 'duration', 'adventure_pack', 
                    'free_to_play', 'extreme_challenge', 'search'
                ]),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve quests',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Store a newly created quest in storage.
     */
    public function store(StoreQuestRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $quest = Quest::create($validated);
            $quest->load(['duration', 'patron', 'adventurePack', 'location']);

            return response()->json([
                'success' => true,
                'message' => 'Quest created successfully',
                'data' => $quest
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quest',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Display the specified quest.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $quest = Quest::with([
                'duration', 
                'patron', 
                'adventurePack', 
                'location.parentLocation',
                'xpRewards.difficulty'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $quest
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quest not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve quest',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Update the specified quest in storage.
     */
    public function update(UpdateQuestRequest $request, string $id): JsonResponse
    {
        try {
            $quest = Quest::findOrFail($id);
            $validated = $request->validated();

            $quest->update($validated);
            $quest->load(['duration', 'patron', 'adventurePack', 'location']);

            return response()->json([
                'success' => true,
                'message' => 'Quest updated successfully',
                'data' => $quest
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quest not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quest',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Remove the specified quest from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $quest = Quest::findOrFail($id);
            $questName = $quest->name;
            
            $quest->delete();

            return response()->json([
                'success' => true,
                'message' => "Quest '{$questName}' deleted successfully"
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quest not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quest',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Search quests with advanced filtering.
     */
    public function search(QuestFilterRequest $request): JsonResponse
    {
        // Use the same logic as index but with more specific search parameters
        return $this->index($request);
    }

    /**
     * Get XP rewards for a specific quest.
     */
    public function getXpRewards(string $id): JsonResponse
    {
        try {
            $quest = Quest::with(['xpRewards.difficulty'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'quest' => $quest->only(['id', 'name', 'slug']),
                    'xp_rewards' => $quest->grouped_xp_rewards,
                ]
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quest not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve XP rewards',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Calculate XP for a quest with specific parameters.
     */
    public function calculateXp(string $id, Request $request): JsonResponse
    {
        try {
            $quest = Quest::with(['xpRewards.difficulty'])->findOrFail($id);

            $validated = $request->validate([
                'difficulty' => 'required|string|exists:difficulties,name',
                'is_epic' => 'boolean',
                'is_legendary' => 'boolean',
                'include_first_time_bonus' => 'boolean',
            ]);

            $difficulty = \App\Models\Ddo\Difficulty::where('name', $validated['difficulty'])->first();
            $isEpic = $validated['is_epic'] ?? false;
            $isLegendary = $validated['is_legendary'] ?? false;
            $includeFirstTimeBonus = $validated['include_first_time_bonus'] ?? false;

            $baseXp = $quest->getXpForDifficulty($difficulty->id, $isEpic, $isLegendary);

            if ($baseXp === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'No XP data available for the specified difficulty and quest type'
                ], 404);
            }

            $calculatedXp = $difficulty->calculateXp($baseXp, $includeFirstTimeBonus);

            return response()->json([
                'success' => true,
                'data' => [
                    'quest' => $quest->only(['id', 'name', 'slug']),
                    'difficulty' => $difficulty->only(['id', 'name', 'multiplier']),
                    'base_xp' => $baseXp,
                    'calculated_xp' => $calculatedXp,
                    'multiplier' => $difficulty->multiplier,
                    'first_time_bonus' => $includeFirstTimeBonus ? $difficulty->first_time_bonus_percent : null,
                    'is_epic' => $isEpic,
                    'is_legendary' => $isLegendary,
                ]
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quest not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate XP',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
