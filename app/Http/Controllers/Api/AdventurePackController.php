<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ddo\AdventurePack;
use App\Http\Resources\AdventurePackResource;
use App\Http\Resources\AdventurePackCollection;
use App\Http\Resources\QuestCollection;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class AdventurePackController extends Controller
{
    /**
     * Display a listing of adventure packs.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = AdventurePack::query();

            // Filter by purchase type
            if ($request->filled('purchase_type')) {
                $query->byPurchaseType($request->purchase_type);
            }

            // Filter by free to play
            if ($request->filled('free_to_play')) {
                if ($request->boolean('free_to_play')) {
                    $query->freeToPlay();
                } else {
                    $query->premium();
                }
            }

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            // Apply date range filter
            if ($request->filled('release_year')) {
                $year = (int) $request->release_year;
                $query->whereYear('release_date', $year);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'release_date');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            $allowedSorts = ['name', 'purchase_type', 'release_date', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }

            // Include quest count if requested
            if ($request->boolean('include_quest_count')) {
                $query->withCount('quests');
            }

            // Load relationships if requested
            if ($request->boolean('include_quests')) {
                $query->with('quests');
            }

            $adventurePacks = $query->get();

            // Add computed attributes
            $adventurePacks->each(function ($pack) {
                $pack->is_free_to_play = $pack->is_free_to_play;
                $pack->age_in_years = $pack->age_in_years;
            });

            return response()->json([
                'success' => true,
                'data' => $adventurePacks,
                'total' => $adventurePacks->count(),
                'filters' => [
                    'purchase_types' => ['Free to Play', 'Premium', 'VIP', 'Expansion'],
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve adventure packs',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Display the specified adventure pack.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $adventurePack = AdventurePack::with(['quests.duration', 'quests.patron'])
                ->findOrFail($id);

            // Add computed attributes
            $packData = $adventurePack->toArray();
            $packData['is_free_to_play'] = $adventurePack->is_free_to_play;
            $packData['age_in_years'] = $adventurePack->age_in_years;
            $packData['quest_count'] = $adventurePack->quests->count();

            return response()->json([
                'success' => true,
                'data' => $packData
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Adventure pack not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve adventure pack',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Store a newly created adventure pack in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:adventure_packs,name',
                'purchase_type' => ['required', Rule::in(['Free to Play', 'Premium', 'VIP', 'Expansion'])],
                'release_date' => 'nullable|date',
            ]);

            $adventurePack = AdventurePack::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Adventure pack created successfully',
                'data' => $adventurePack
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create adventure pack',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Update the specified adventure pack in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $adventurePack = AdventurePack::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100|unique:adventure_packs,name,' . $adventurePack->id,
                'purchase_type' => ['sometimes', 'required', Rule::in(['Free to Play', 'Premium', 'VIP', 'Expansion'])],
                'release_date' => 'nullable|date',
            ]);

            $adventurePack->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Adventure pack updated successfully',
                'data' => $adventurePack
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Adventure pack not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update adventure pack',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Remove the specified adventure pack from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $adventurePack = AdventurePack::findOrFail($id);

            // Check if adventure pack has associated quests
            if ($adventurePack->quests()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete adventure pack that has associated quests'
                ], 422);
            }

            $packName = $adventurePack->name;
            $adventurePack->delete();

            return response()->json([
                'success' => true,
                'message' => "Adventure pack '{$packName}' deleted successfully"
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Adventure pack not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete adventure pack',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
