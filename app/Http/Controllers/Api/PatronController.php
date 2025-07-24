<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ddo\Patron;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PatronController extends Controller
{
    /**
     * Display a listing of patrons.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Patron::query();

            // Include quest count if requested
            if ($request->boolean('include_quest_count')) {
                $query->withQuestCount();
            }

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            $allowedSorts = ['name', 'created_at', 'quests_count'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }

            // Load relationships if requested
            if ($request->boolean('include_quests')) {
                $query->with('quests');
            }

            $patrons = $query->get();

            return response()->json([
                'success' => true,
                'data' => $patrons,
                'total' => $patrons->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve patrons',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Display the specified patron.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $patron = Patron::with(['quests.duration', 'quests.adventurePack'])
                ->findOrFail($id);

            // Add computed attributes
            $patronData = $patron->toArray();
            $patronData['total_favor'] = $patron->total_favor;
            $patronData['quest_count'] = $patron->quests->count();

            return response()->json([
                'success' => true,
                'data' => $patronData
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Patron not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve patron',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Store a newly created patron in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:patrons,name',
                'description' => 'nullable|string',
            ]);

            $patron = Patron::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patron created successfully',
                'data' => $patron
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create patron',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Update the specified patron in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $patron = Patron::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100|unique:patrons,name,' . $patron->id,
                'description' => 'nullable|string',
            ]);

            $patron->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patron updated successfully',
                'data' => $patron
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Patron not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update patron',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Remove the specified patron from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $patron = Patron::findOrFail($id);

            // Check if patron has associated quests
            if ($patron->quests()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete patron that has associated quests'
                ], 422);
            }

            $patronName = $patron->name;
            $patron->delete();

            return response()->json([
                'success' => true,
                'message' => "Patron '{$patronName}' deleted successfully"
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Patron not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete patron',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
