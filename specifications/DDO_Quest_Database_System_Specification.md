# DDO Quest Database System - Technical Specification

## 1. Project Overview

### 1.1 Objective
Build a comprehensive DDO (Dungeons & Dragons Online) quest database system that collects, stores, and analyzes quest data from ddowiki.com to optimize character leveling routes and provide advanced quest filtering capabilities.

### 1.2 Current Technology Stack
- **Backend Framework**: Laravel 12.21.0 (PHP 8+)
- **Frontend Framework**: React 18.2.0 + Inertia.js 2.0.4
- **UI Library**: Chakra UI 2.8.2
- **Database**: MySQL with Laravel Eloquent ORM
- **Development Environment**: Laravel Sail 1.44.0 (Docker)
- **Testing**: PHPUnit with modern #[Test] attributes (124 tests passing)
- **Authentication**: Laravel Sanctum + Google OAuth integration

### 1.3 Current Project State
- Complete authentication system (login, registration, Google OAuth)
- Modern Laravel 12 architecture with proper middleware and security
- Comprehensive test suite with 100% pass rate
- Production-ready codebase with proper error handling

## 2. Data Analysis & Requirements

### 2.1 Quest Data Structure (From Level 1 Quests Analysis)
Based on analysis of https://ddowiki.com/page/Level_1_quests, the quest table contains:

**Core Quest Attributes:**
- Quest Name (links to detailed pages)
- Duration (Short, Medium, Long) 
- Base Favor (0, 6, 9, 12)
- Patron (The Coin Lords, House Deneith, etc.)
- Adventure Pack (Free to Play, Keep on the Borderlands, etc.)
- Location (Korthos Village, Korthos Island, etc.)
- XP Rewards by Difficulty:
  - ♣ Casual XP
  - ♦ Normal XP  
  - ♥ Hard XP
  - ♠ Elite XP
- Epic availability (Yes/No/Both)

### 2.2 Detailed Quest Page Analysis (From Stopping the Sahuagin)
Individual quest pages contain extensive additional data:

**Quest Details:**
- Heroic/Epic levels
- Duration classification
- XP rewards per difficulty
- Location/Adventure area
- Bestowed by (NPC)
- Base favor reward
- Purchase requirement
- Extreme Challenge availability

**Quest Content:**
- Overview/storyline
- Objectives (main and optional)
- Expected challenges (traps, puzzles, monsters)
- Known traps with DCs
- Tips and strategies
- Bonus XP conditions
- Monster information with CR ratings
- Loot tables (end rewards, chest drops)
- Maps and walkthrough links

### 2.3 XP Mechanics Analysis
From https://ddowiki.com/page/Experience_point analysis:

**XP Calculation Factors:**
- Base quest XP
- Difficulty multipliers (Casual: 1x, Normal: 1x, Hard: 1.25x, Elite: 1.5x, Reaper: 1.5x+)
- First-time completion bonuses (20% for Normal/Hard, 45% for Elite/Reaper)
- Delving bonus (up to +150% for higher difficulties)
- Daily bonus (+25% heroic, +40% epic)
- Quest ransack penalty (-20% per repeat, minimum 20%)
- Bonus XP categories:
  - Monster killing bonuses (Discreet: +5%, Aggression: +10%, Onslaught: +15%, Conquest: +25%)
  - Trap disarming bonuses (Tamper: +10%, Neutralization: +20%, Ingenious Debilitation: +30%)
  - Secret door bonuses (Observance: +8%, Perception: +10%, Vigilant Sight: +15%)
  - Breakables bonuses (Mischief: +8%, Vandal: +10%, Ransack: +15%)
  - Group bonuses, VIP bonuses, item bonuses

### 2.4 Saga System Analysis
From https://ddowiki.com/page/Saga analysis:

**Saga Mechanics:**
- Groups of related quests with completion bonuses
- Point system: 1 point (Casual/Normal), 2 points (Hard), 3 points (Elite/Reaper)
- Reward tiers: Normal, Hard, Elite, True Elite
- End rewards: XP gems, Skill tomes, Guild renown, special items
- Cooldown timers (varies by saga)

## 3. Database Schema Design

### 3.1 Core Tables

#### 3.1.1 Quests Table
```sql
CREATE TABLE quests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    heroic_level TINYINT UNSIGNED,
    epic_level TINYINT UNSIGNED,
    legendary_level TINYINT UNSIGNED,
    duration_id BIGINT UNSIGNED,
    patron_id BIGINT UNSIGNED,
    adventure_pack_id BIGINT UNSIGNED,
    location_id BIGINT UNSIGNED,
    base_favor SMALLINT UNSIGNED DEFAULT 0,
    extreme_challenge BOOLEAN DEFAULT FALSE,
    overview TEXT,
    objectives TEXT,
    tips TEXT,
    wiki_url VARCHAR(512),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (duration_id) REFERENCES durations(id),
    FOREIGN KEY (patron_id) REFERENCES patrons(id),
    FOREIGN KEY (adventure_pack_id) REFERENCES adventure_packs(id),
    FOREIGN KEY (location_id) REFERENCES locations(id),
    
    INDEX idx_heroic_level (heroic_level),
    INDEX idx_epic_level (epic_level),
    INDEX idx_legendary_level (legendary_level),
    INDEX idx_patron_duration (patron_id, duration_id)
);
```

#### 3.1.2 Quest XP Rewards Table
```sql
CREATE TABLE quest_xp_rewards (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    quest_id BIGINT UNSIGNED NOT NULL,
    difficulty_id BIGINT UNSIGNED NOT NULL,
    is_epic BOOLEAN DEFAULT FALSE,
    is_legendary BOOLEAN DEFAULT FALSE,
    base_xp INT UNSIGNED NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (quest_id) REFERENCES quests(id) ON DELETE CASCADE,
    FOREIGN KEY (difficulty_id) REFERENCES difficulties(id),
    
    UNIQUE KEY unique_quest_difficulty_type (quest_id, difficulty_id, is_epic, is_legendary),
    INDEX idx_quest_difficulty (quest_id, difficulty_id)
);
```

#### 3.1.3 Reference Tables
```sql
-- Durations Table
CREATE TABLE durations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE, -- 'Short', 'Medium', 'Long'
    estimated_minutes SMALLINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Patrons Table  
CREATE TABLE patrons (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Adventure Packs Table
CREATE TABLE adventure_packs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    purchase_type ENUM('Free to Play', 'Premium', 'VIP', 'Expansion') NOT NULL,
    release_date DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Locations Table
CREATE TABLE locations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    area_type ENUM('Village', 'Island', 'City', 'Wilderness', 'Dungeon') NOT NULL,
    parent_location_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (parent_location_id) REFERENCES locations(id)
);

-- Difficulties Table
CREATE TABLE difficulties (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL UNIQUE, -- 'Casual', 'Normal', 'Hard', 'Elite', 'Reaper'
    multiplier DECIMAL(3,2) NOT NULL, -- 1.00, 1.25, 1.50, etc.
    first_time_bonus_percent TINYINT UNSIGNED, -- 20, 45, etc.
    sort_order TINYINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 3.2 Monster & Combat Tables

```sql
-- Monsters Table
CREATE TABLE monsters (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50), -- 'Monstrous Humanoid', 'Vermin', etc.
    subtype VARCHAR(50), -- 'Sahuagin', 'Spider', etc.
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_type_subtype (type, subtype)
);

-- Quest Monsters Table (with CR by difficulty)
CREATE TABLE quest_monsters (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    quest_id BIGINT UNSIGNED NOT NULL,
    monster_id BIGINT UNSIGNED NOT NULL,
    difficulty_id BIGINT UNSIGNED NOT NULL,
    challenge_rating DECIMAL(4,2), -- 0.50, 1.00, 2.00, etc.
    quantity_estimate SMALLINT UNSIGNED,
    is_boss BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (quest_id) REFERENCES quests(id) ON DELETE CASCADE,
    FOREIGN KEY (monster_id) REFERENCES monsters(id),
    FOREIGN KEY (difficulty_id) REFERENCES difficulties(id),
    
    INDEX idx_quest_difficulty (quest_id, difficulty_id),
    INDEX idx_cr_range (challenge_rating)
);
```

### 3.3 Loot & Item System

```sql
-- Items Table
CREATE TABLE items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    item_type VARCHAR(50), -- 'Helm', 'Boots', 'Weapon', etc.
    minimum_level TINYINT UNSIGNED,
    binding_type ENUM('Unbound', 'BtC on Acquire', 'BtC on Equip', 'BtA on Acquire') DEFAULT 'Unbound',
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_type_level (item_type, minimum_level)
);

-- Enhancements Table
CREATE TABLE enhancements (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    enhancement_type VARCHAR(50), -- 'Skill Bonus', 'Save Bonus', 'Spell', etc.
    value VARCHAR(100), -- '+1', '+3', 'Cold Resistance +3', etc.
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Item Enhancements Junction Table
CREATE TABLE item_enhancements (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    item_id BIGINT UNSIGNED NOT NULL,
    enhancement_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP,
    
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (enhancement_id) REFERENCES enhancements(id),
    
    UNIQUE KEY unique_item_enhancement (item_id, enhancement_id)
);

-- Quest Loot Table
CREATE TABLE quest_loot (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    quest_id BIGINT UNSIGNED NOT NULL,
    item_id BIGINT UNSIGNED NOT NULL,
    loot_source ENUM('End Reward', 'Named Chest', 'Random Drop', 'Optional Chest') NOT NULL,
    drop_rate ENUM('Guaranteed', 'High', 'Medium', 'Low', 'Rare') DEFAULT 'Medium',
    choice_group VARCHAR(50), -- For end reward choices
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (quest_id) REFERENCES quests(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id),
    
    INDEX idx_quest_source (quest_id, loot_source)
);
```

### 3.4 Saga System

```sql
-- Sagas Table
CREATE TABLE sagas (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    saga_type ENUM('Heroic', 'Epic', 'Legendary') NOT NULL,
    level_range_min TINYINT UNSIGNED,
    level_range_max TINYINT UNSIGNED,
    cooldown_hours SMALLINT UNSIGNED DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_type_level (saga_type, level_range_min, level_range_max)
);

-- Saga Quests Junction Table
CREATE TABLE saga_quests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    saga_id BIGINT UNSIGNED NOT NULL,
    quest_id BIGINT UNSIGNED NOT NULL,
    required BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    
    FOREIGN KEY (saga_id) REFERENCES sagas(id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quests(id),
    
    UNIQUE KEY unique_saga_quest (saga_id, quest_id)
);
```

### 3.5 Bonus XP & Objectives

```sql
-- Bonus XP Categories Table
CREATE TABLE bonus_xp_categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    category_type ENUM('Monster', 'Trap', 'Secret', 'Breakable', 'Special') NOT NULL,
    bonus_percent TINYINT UNSIGNED NOT NULL,
    threshold_description VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Quest Bonus XP Table
CREATE TABLE quest_bonus_xp (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    quest_id BIGINT UNSIGNED NOT NULL,
    bonus_category_id BIGINT UNSIGNED NOT NULL,
    threshold_value SMALLINT UNSIGNED, -- Number needed for bonus
    created_at TIMESTAMP,
    
    FOREIGN KEY (quest_id) REFERENCES quests(id) ON DELETE CASCADE,
    FOREIGN KEY (bonus_category_id) REFERENCES bonus_xp_categories(id),
    
    UNIQUE KEY unique_quest_bonus (quest_id, bonus_category_id)
);

-- Quest Objectives Table
CREATE TABLE quest_objectives (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    quest_id BIGINT UNSIGNED NOT NULL,
    objective_text TEXT NOT NULL,
    is_optional BOOLEAN DEFAULT FALSE,
    xp_reward INT UNSIGNED DEFAULT 0,
    order_sequence TINYINT UNSIGNED,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (quest_id) REFERENCES quests(id) ON DELETE CASCADE,
    
    INDEX idx_quest_type (quest_id, is_optional)
);
```

## 4. Laravel Implementation

### 4.1 Models Structure

```php
// app/Models/Quest.php
class Quest extends Model
{
    protected $fillable = [
        'name', 'slug', 'heroic_level', 'epic_level', 'legendary_level',
        'duration_id', 'patron_id', 'adventure_pack_id', 'location_id',
        'base_favor', 'extreme_challenge', 'overview', 'objectives', 
        'tips', 'wiki_url'
    ];

    protected $casts = [
        'extreme_challenge' => 'boolean',
    ];

    // Relationships
    public function duration() { return $this->belongsTo(Duration::class); }
    public function patron() { return $this->belongsTo(Patron::class); }
    public function adventurePack() { return $this->belongsTo(AdventurePack::class); }
    public function location() { return $this->belongsTo(Location::class); }
    public function xpRewards() { return $this->hasMany(QuestXpReward::class); }
    public function monsters() { return $this->belongsToMany(Monster::class, 'quest_monsters')->withPivot('difficulty_id', 'challenge_rating', 'quantity_estimate', 'is_boss'); }
    public function loot() { return $this->hasMany(QuestLoot::class); }
    public function sagas() { return $this->belongsToMany(Saga::class, 'saga_quests')->withPivot('required'); }
    public function bonusXp() { return $this->belongsToMany(BonusXpCategory::class, 'quest_bonus_xp')->withPivot('threshold_value'); }
    public function objectives() { return $this->hasMany(QuestObjective::class)->orderBy('order_sequence'); }

    // Scopes for filtering
    public function scopeByLevel($query, $level) {
        return $query->where('heroic_level', '<=', $level + 2)
                    ->where('heroic_level', '>=', $level - 2);
    }

    public function scopeByDuration($query, $duration) {
        return $query->whereHas('duration', function($q) use ($duration) {
            $q->where('name', $duration);
        });
    }

    public function scopeByPatron($query, $patronId) {
        return $query->where('patron_id', $patronId);
    }

    // XP Calculation Methods
    public function calculateXp($difficulty, $characterLevel, $bonuses = []) {
        // Complex XP calculation logic here
        $baseXp = $this->getBaseXpForDifficulty($difficulty);
        $levelPenalty = $this->calculateLevelPenalty($characterLevel);
        $bonusMultiplier = $this->calculateBonuses($bonuses);
        
        return $baseXp * $levelPenalty * $bonusMultiplier;
    }
}
```

### 4.2 Controllers Structure

```php
// app/Http/Controllers/QuestController.php
class QuestController extends Controller
{
    public function index(Request $request)
    {
        $quests = Quest::with(['duration', 'patron', 'adventurePack', 'location'])
            ->when($request->level, fn($q) => $q->byLevel($request->level))
            ->when($request->duration, fn($q) => $q->byDuration($request->duration))
            ->when($request->patron, fn($q) => $q->byPatron($request->patron))
            ->when($request->min_xp, fn($q) => $q->whereHas('xpRewards', function($query) use ($request) {
                $query->where('base_xp', '>=', $request->min_xp);
            }))
            ->paginate(25);

        return Inertia::render('Quests/Index', [
            'quests' => $quests,
            'filters' => $request->only(['level', 'duration', 'patron', 'min_xp']),
            'durations' => Duration::all(),
            'patrons' => Patron::all(),
        ]);
    }

    public function show(Quest $quest)
    {
        $quest->load([
            'xpRewards.difficulty',
            'monsters.difficulties',
            'loot.item.enhancements',
            'objectives',
            'bonusXp',
            'sagas'
        ]);

        return Inertia::render('Quests/Show', compact('quest'));
    }
}

// app/Http/Controllers/LevelingController.php  
class LevelingController extends Controller
{
    public function calculateOptimalRoute(Request $request)
    {
        $startLevel = $request->input('start_level', 1);
        $endLevel = $request->input('end_level', 20);
        $preferences = $request->input('preferences', []);
        
        $optimizer = new QuestRouteOptimizer();
        $route = $optimizer->calculateOptimalPath($startLevel, $endLevel, $preferences);
        
        return response()->json($route);
    }
}
```

### 4.3 Data Collection Service

```php
// app/Services/WikiScrapingService.php
class WikiScrapingService
{
    public function scrapeQuestData($questUrl)
    {
        // Use HTTP client to fetch and parse wiki pages
        $response = Http::get($questUrl);
        $html = $response->body();
        
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        return [
            'name' => $this->extractQuestName($xpath),
            'level' => $this->extractLevel($xpath),
            'xp_rewards' => $this->extractXpRewards($xpath),
            'monsters' => $this->extractMonsters($xpath),
            'loot' => $this->extractLoot($xpath),
            // ... other data extraction
        ];
    }

    public function scrapeAllQuests()
    {
        // Iterate through all quest level pages
        for ($level = 1; $level <= 30; $level++) {
            $questListUrl = "https://ddowiki.com/page/Level_{$level}_quests";
            $questUrls = $this->extractQuestUrls($questListUrl);
            
            foreach ($questUrls as $questUrl) {
                $questData = $this->scrapeQuestData($questUrl);
                $this->storeQuestData($questData);
            }
        }
    }
}
```

## 5. Advanced Features

### 5.1 XP Optimization Algorithm

```php
// app/Services/QuestRouteOptimizer.php
class QuestRouteOptimizer
{
    public function calculateOptimalPath($startLevel, $endLevel, $preferences = [])
    {
        // Dynamic programming approach to find optimal quest sequence
        $xpNeeded = $this->getXpRequiredForLevels($startLevel, $endLevel);
        $availableQuests = $this->getAvailableQuests($startLevel, $endLevel);
        
        // Apply user preferences (time constraints, patron preferences, etc.)
        $filteredQuests = $this->applyPreferences($availableQuests, $preferences);
        
        // Calculate XP per minute for each quest
        $questEfficiency = $this->calculateQuestEfficiency($filteredQuests);
        
        // Use optimization algorithm (greedy with lookahead)
        return $this->optimizeQuestSequence($questEfficiency, $xpNeeded);
    }

    private function calculateQuestEfficiency($quests)
    {
        return $quests->map(function($quest) {
            $xpPerMinute = $quest->calculateMaxXp() / $quest->duration->estimated_minutes;
            $repeatability = $this->calculateRepeatability($quest);
            $sagaBonus = $this->calculateSagaBonus($quest);
            
            return [
                'quest' => $quest,
                'efficiency' => $xpPerMinute * $repeatability * $sagaBonus,
            ];
        });
    }
}
```

### 5.2 Advanced Search & Filtering

```php
// app/Http/Controllers/Api/QuestSearchController.php
class QuestSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Quest::query();
        
        // Level range filtering
        if ($request->has('level_range')) {
            $query->whereBetween('heroic_level', $request->level_range);
        }
        
        // XP efficiency filtering
        if ($request->has('min_xp_per_minute')) {
            $query->whereRaw('(SELECT MAX(base_xp) FROM quest_xp_rewards WHERE quest_id = quests.id) / (SELECT estimated_minutes FROM durations WHERE id = quests.duration_id) >= ?', [$request->min_xp_per_minute]);
        }
        
        // Enhancement filtering
        if ($request->has('enhancements')) {
            $query->whereHas('loot.item.enhancements', function($q) use ($request) {
                $q->whereIn('enhancements.id', $request->enhancements);
            });
        }
        
        // Monster CR filtering
        if ($request->has('max_cr')) {
            $query->whereHas('monsters', function($q) use ($request) {
                $q->where('challenge_rating', '<=', $request->max_cr);
            });
        }
        
        return $query->with(['duration', 'patron', 'xpRewards'])->get();
    }
}
```

## 6. Frontend Implementation

### 6.1 React Components

```jsx
// resources/js/Pages/Quests/Index.jsx
import { useState } from 'react';
import { 
    Box, VStack, HStack, Select, Input, Button, 
    Table, Thead, Tbody, Tr, Th, Td, Badge,
    useDisclosure, Modal, ModalOverlay, ModalContent
} from '@chakra-ui/react';

export default function QuestIndex({ quests, filters, durations, patrons }) {
    const [localFilters, setLocalFilters] = useState(filters);
    const { isOpen, onOpen, onClose } = useDisclosure();

    const handleFilterChange = (key, value) => {
        setLocalFilters(prev => ({ ...prev, [key]: value }));
    };

    const applyFilters = () => {
        router.get('/quests', localFilters);
    };

    return (
        <Box p={6}>
            <VStack spacing={4} align="stretch">
                <HStack spacing={4}>
                    <Select 
                        placeholder="Select Duration"
                        value={localFilters.duration || ''}
                        onChange={(e) => handleFilterChange('duration', e.target.value)}
                    >
                        {durations.map(duration => (
                            <option key={duration.id} value={duration.name}>
                                {duration.name}
                            </option>
                        ))}
                    </Select>
                    
                    <Select 
                        placeholder="Select Patron"
                        value={localFilters.patron || ''}
                        onChange={(e) => handleFilterChange('patron', e.target.value)}
                    >
                        {patrons.map(patron => (
                            <option key={patron.id} value={patron.id}>
                                {patron.name}
                            </option>
                        ))}
                    </Select>
                    
                    <Input 
                        placeholder="Character Level"
                        type="number"
                        value={localFilters.level || ''}
                        onChange={(e) => handleFilterChange('level', e.target.value)}
                    />
                    
                    <Button colorScheme="blue" onClick={applyFilters}>
                        Apply Filters
                    </Button>
                </HStack>

                <QuestTable quests={quests.data} />
                
                <Button colorScheme="green" onClick={onOpen}>
                    Calculate Optimal Route
                </Button>
            </VStack>

            <LevelingOptimizerModal isOpen={isOpen} onClose={onClose} />
        </Box>
    );
}

// Quest Efficiency Calculator Component
const QuestEfficiencyCard = ({ quest }) => {
    const maxXp = Math.max(...quest.xp_rewards.map(r => r.base_xp));
    const xpPerMinute = maxXp / quest.duration.estimated_minutes;
    
    return (
        <Box borderWidth={1} borderRadius="lg" p={4}>
            <Text fontWeight="bold">{quest.name}</Text>
            <HStack>
                <Badge colorScheme="blue">Level {quest.heroic_level}</Badge>
                <Badge colorScheme="green">{quest.duration.name}</Badge>
                <Badge colorScheme="purple">{Math.round(xpPerMinute)} XP/min</Badge>
            </HStack>
        </Box>
    );
};
```

## 7. Data Migration Strategy

### 7.1 Seeder Implementation

```php
// database/seeders/QuestDataSeeder.php
class QuestDataSeeder extends Seeder
{
    public function run()
    {
        // Create reference data first
        $this->createDurations();
        $this->createDifficulties();
        $this->createPatrons();
        $this->createAdventurePacks();
        $this->createLocations();
        
        // Scrape and import quest data
        $scrapingService = new WikiScrapingService();
        $scrapingService->scrapeAllQuests();
    }

    private function createDurations()
    {
        Duration::create(['name' => 'Short', 'estimated_minutes' => 15]);
        Duration::create(['name' => 'Medium', 'estimated_minutes' => 30]);
        Duration::create(['name' => 'Long', 'estimated_minutes' => 45]);
    }

    private function createDifficulties()
    {
        Difficulty::create(['name' => 'Casual', 'multiplier' => 1.00, 'first_time_bonus_percent' => 20, 'sort_order' => 1]);
        Difficulty::create(['name' => 'Normal', 'multiplier' => 1.00, 'first_time_bonus_percent' => 20, 'sort_order' => 2]);
        Difficulty::create(['name' => 'Hard', 'multiplier' => 1.25, 'first_time_bonus_percent' => 20, 'sort_order' => 3]);
        Difficulty::create(['name' => 'Elite', 'multiplier' => 1.50, 'first_time_bonus_percent' => 45, 'sort_order' => 4]);
        Difficulty::create(['name' => 'Reaper', 'multiplier' => 1.50, 'first_time_bonus_percent' => 45, 'sort_order' => 5]);
    }
}
```

## 8. API Design

### 8.1 RESTful API Endpoints

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::get('/quests', [QuestController::class, 'index']);
    Route::get('/quests/{quest}', [QuestController::class, 'show']);
    Route::get('/quests/search', [QuestSearchController::class, 'search']);
    Route::get('/quests/{quest}/calculate-xp', [QuestController::class, 'calculateXp']);
    
    Route::get('/leveling/optimal-route', [LevelingController::class, 'calculateOptimalRoute']);
    Route::get('/leveling/xp-requirements', [LevelingController::class, 'getXpRequirements']);
    
    Route::get('/sagas', [SagaController::class, 'index']);
    Route::get('/sagas/{saga}/progress', [SagaController::class, 'calculateProgress']);
    
    Route::get('/items/search', [ItemController::class, 'search']);
    Route::get('/enhancements', [EnhancementController::class, 'index']);
});
```

## 9. Performance Optimization

### 9.1 Database Indexing Strategy

```sql
-- Performance indexes for complex queries
CREATE INDEX idx_quest_level_patron_duration ON quests(heroic_level, patron_id, duration_id);
CREATE INDEX idx_xp_rewards_quest_difficulty ON quest_xp_rewards(quest_id, difficulty_id, base_xp);
CREATE INDEX idx_quest_monsters_cr ON quest_monsters(quest_id, challenge_rating);
CREATE INDEX idx_quest_loot_source ON quest_loot(quest_id, loot_source);

-- Full-text search indexes
ALTER TABLE quests ADD FULLTEXT(name, overview, objectives, tips);
ALTER TABLE items ADD FULLTEXT(name, description);
```

### 9.2 Caching Strategy

```php
// app/Services/QuestCacheService.php
class QuestCacheService
{
    public function getOptimalRoute($startLevel, $endLevel, $preferences)
    {
        $cacheKey = "optimal_route_{$startLevel}_{$endLevel}_" . md5(serialize($preferences));
        
        return Cache::remember($cacheKey, 3600, function() use ($startLevel, $endLevel, $preferences) {
            return (new QuestRouteOptimizer())->calculateOptimalPath($startLevel, $endLevel, $preferences);
        });
    }

    public function getQuestEfficiency($questId)
    {
        return Cache::remember("quest_efficiency_{$questId}", 1800, function() use ($questId) {
            return Quest::find($questId)->calculateEfficiencyMetrics();
        });
    }
}
```

## 10. Testing Strategy

### 10.1 Feature Tests

```php
// tests/Feature/QuestOptimizationTest.php
class QuestOptimizationTest extends TestCase
{
    #[Test]
    public function can_calculate_optimal_leveling_route()
    {
        // Create test data
        $quests = Quest::factory()->count(10)->create();
        
        $response = $this->getJson('/api/v1/leveling/optimal-route?start_level=1&end_level=5');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'route' => [
                        '*' => ['quest_id', 'estimated_xp', 'estimated_time']
                    ],
                    'total_xp',
                    'total_time',
                    'efficiency_rating'
                ]);
    }

    #[Test]
    public function can_filter_quests_by_enhancement_requirements()
    {
        $enhancement = Enhancement::factory()->create(['name' => 'Concentration +3']);
        $item = Item::factory()->create();
        $item->enhancements()->attach($enhancement);
        
        $quest = Quest::factory()->create();
        $quest->loot()->create(['item_id' => $item->id, 'loot_source' => 'End Reward']);
        
        $response = $this->getJson('/api/v1/quests/search?enhancements[]=' . $enhancement->id);
        
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.id', $quest->id);
    }
}
```

## 11. Implementation Phases

### Phase 1: Core Infrastructure (Weeks 1-2)
1. Create database migrations for core tables
2. Implement basic models and relationships  
3. Build initial data seeding from wiki scraping
4. Create basic CRUD controllers

### Phase 2: Data Collection (Weeks 3-4)
1. Implement comprehensive wiki scraping service
2. Create data validation and cleanup processes
3. Import all quest data from ddowiki.com
4. Build data update/refresh mechanisms

### Phase 3: Search & Filtering (Weeks 5-6)
1. Implement advanced quest search functionality
2. Create filtering by multiple criteria
3. Build XP calculation algorithms
4. Add quest comparison features

### Phase 4: Route Optimization (Weeks 7-8)  
1. Implement leveling route optimization algorithm
2. Create character progression tracking
3. Build efficiency calculations
4. Add saga completion tracking

### Phase 5: Frontend & UX (Weeks 9-10)
1. Build React components for quest browsing
2. Create optimization interface
3. Implement data visualization
4. Add responsive design for mobile

### Phase 6: Performance & Polish (Weeks 11-12)
1. Optimize database queries and add indexes
2. Implement caching strategies
3. Add comprehensive test coverage
4. Performance testing and optimization

This specification provides a complete roadmap for building a comprehensive DDO quest database system that will enable optimal character leveling route calculation and advanced quest filtering capabilities, built on the existing Laravel 12 + React + Inertia.js foundation.
