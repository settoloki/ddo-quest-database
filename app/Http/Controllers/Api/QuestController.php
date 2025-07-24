<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ddo\Quest;
use App\Http\Requests\StoreQuestRequest;
use App\Http\Requests\UpdateQuestRequest;
use App\Http\Requests\QuestFilterRequest;
use App\Http\Resources\QuestResource;
use App\Http\Resources\QuestCollection;
use App\Http\Resources\DifficultyResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestController extends Controller
{
    /**
     * Display a listing of quests with filtering and pagination.
     */
    public function index(QuestFilterRequest $request)
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

            return new QuestCollection($quests);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve quests',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
        }
    }

    /**
     * Store a newly created quest in storage.
     */
    public function store(StoreQuestRequest $request)
    {
        try {
            $validated = $request->validated();

            $quest = Quest::create($validated);
            $quest->load(['duration', 'patron', 'adventurePack', 'location']);

            return ApiResponse::created(
                new QuestResource($quest),
                'Quest created successfully'
            );

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to create quest',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
        }
    }

    /**
     * Display the specified quest.
     */
    public function show(string $id)
    {
        try {
            $quest = Quest::with([
                'duration', 
                'patron', 
                'adventurePack', 
                'location.parentLocation',
                'xpRewards.difficulty'
            ])->findOrFail($id);

            return ApiResponse::success(
                new QuestResource($quest),
                'Quest retrieved successfully'
            );

        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Quest not found');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve quest',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
        }
    }

    /**
     * Update the specified quest in storage.
     */
    public function update(UpdateQuestRequest $request, string $id)
    {
        try {
            $quest = Quest::findOrFail($id);
            $validated = $request->validated();

            $quest->update($validated);
            $quest->load(['duration', 'patron', 'adventurePack', 'location']);

            return ApiResponse::success(
                new QuestResource($quest),
                'Quest updated successfully'
            );

        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Quest not found');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to update quest',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
        }
    }

    /**
     * Remove the specified quest from storage.
     */
    public function destroy(string $id)
    {
        try {
            $quest = Quest::findOrFail($id);
            $questName = $quest->name;
            
            $quest->delete();

            return ApiResponse::success(
                null,
                "Quest '{$questName}' deleted successfully"
            );

        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Quest not found');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to delete quest',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
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

            return ApiResponse::success([
                'quest' => new QuestResource($quest),
                'xp_rewards' => $quest->grouped_xp_rewards,
            ], 'XP rewards retrieved successfully');

        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Quest not found');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve XP rewards',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
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

            // Add calculated XP data to quest resource
            $quest->calculated_xp = [
                'base_xp' => $baseXp,
                'difficulty_multiplier' => $difficulty->xp_multiplier,
                'total_xp' => $calculatedXp,
                'difficulty' => $difficulty->name,
            ];

            return ApiResponse::success([
                'quest' => new QuestResource($quest),
                'calculation_details' => [
                    'difficulty' => new DifficultyResource($difficulty),
                    'parameters' => [
                        'is_epic' => $isEpic,
                        'is_legendary' => $isLegendary,
                        'include_first_time_bonus' => $includeFirstTimeBonus,
                    ],
                ],
            ], 'XP calculated successfully');

        } catch (ModelNotFoundException $e) {
            return ApiResponse::notFound('Quest not found');
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to calculate XP',
                config('app.debug') ? ['exception' => $e->getMessage()] : null
            );
        }
    }
}
