<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuestController;
use App\Http\Controllers\Api\PatronController;
use App\Http\Controllers\Api\AdventurePackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API v1 routes
Route::prefix('v1')->group(function () {
    
    // Quest management routes
    Route::apiResource('quests', QuestController::class);
    
    // Additional quest routes
    Route::get('quests/{quest}/xp-rewards', [QuestController::class, 'getXpRewards']);
    Route::post('quests/{quest}/xp-rewards', [QuestController::class, 'storeXpReward']);
    Route::get('quests/{quest}/calculate-xp', [QuestController::class, 'calculateXp']);
    
    // Patron management routes
    Route::apiResource('patrons', PatronController::class);
    Route::get('patrons/{patron}/quests', [PatronController::class, 'getQuests']);
    
    // Adventure Pack management routes
    Route::apiResource('adventure-packs', AdventurePackController::class);
    Route::get('adventure-packs/{adventurePack}/quests', [AdventurePackController::class, 'getQuests']);
    
    // Reference data routes (read-only)
    Route::get('durations', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Ddo\Duration::orderByTime()->get()
        ]);
    });
    
    Route::get('difficulties', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Ddo\Difficulty::ordered()->get()
        ]);
    });
    
    Route::get('locations', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Ddo\Location::with('parentLocation')->get()
        ]);
    });
    
    // Quest search and filtering
    Route::get('search/quests', [QuestController::class, 'search']);
    
    // Statistics and analytics routes
    Route::get('stats/overview', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_quests' => \App\Models\Ddo\Quest::count(),
                'total_patrons' => \App\Models\Ddo\Patron::count(),
                'total_adventure_packs' => \App\Models\Ddo\AdventurePack::count(),
                'total_locations' => \App\Models\Ddo\Location::count(),
                'free_to_play_quests' => \App\Models\Ddo\Quest::freeToPlay()->count(),
                'premium_quests' => \App\Models\Ddo\Quest::premium()->count(),
                'extreme_challenge_quests' => \App\Models\Ddo\Quest::extremeChallenge()->count(),
            ]
        ]);
    });
    
    Route::get('stats/levels', function () {
        $heroicLevels = \App\Models\Ddo\Quest::selectRaw('heroic_level, COUNT(*) as count')
            ->whereNotNull('heroic_level')
            ->groupBy('heroic_level')
            ->orderBy('heroic_level')
            ->get();
            
        $epicLevels = \App\Models\Ddo\Quest::selectRaw('epic_level, COUNT(*) as count')
            ->whereNotNull('epic_level')
            ->groupBy('epic_level')
            ->orderBy('epic_level')
            ->get();
            
        $legendaryLevels = \App\Models\Ddo\Quest::selectRaw('legendary_level, COUNT(*) as count')
            ->whereNotNull('legendary_level')
            ->groupBy('legendary_level')
            ->orderBy('legendary_level')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'heroic' => $heroicLevels,
                'epic' => $epicLevels,
                'legendary' => $legendaryLevels,
            ]
        ]);
    });
    
    // Health check route
    Route::get('health', function () {
        return response()->json([
            'success' => true,
            'message' => 'DDO Quest Database API is running',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString(),
        ]);
    });
});
