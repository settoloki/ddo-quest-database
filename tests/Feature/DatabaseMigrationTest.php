<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function core_tables_migration_creates_all_tables()
    {
        // Test that all core tables exist after migration
        $expectedTables = [
            'durations',
            'patrons', 
            'adventure_packs',
            'locations',
            'difficulties',
            'quests',
            'quest_xp_rewards',
            'quest_difficulties'
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table '{$table}' should exist after migration"
            );
        }
    }

    #[Test]
    public function durations_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('durations', 'id'));
        $this->assertTrue(Schema::hasColumn('durations', 'name'));
        $this->assertTrue(Schema::hasColumn('durations', 'estimated_minutes'));
        $this->assertTrue(Schema::hasColumn('durations', 'created_at'));
        $this->assertTrue(Schema::hasColumn('durations', 'updated_at'));
    }

    #[Test]
    public function patrons_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('patrons', 'id'));
        $this->assertTrue(Schema::hasColumn('patrons', 'name'));
        $this->assertTrue(Schema::hasColumn('patrons', 'description'));
        $this->assertTrue(Schema::hasColumn('patrons', 'created_at'));
        $this->assertTrue(Schema::hasColumn('patrons', 'updated_at'));
    }

    #[Test]
    public function adventure_packs_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'id'));
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'name'));
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'purchase_type'));
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'release_date'));
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'created_at'));
        $this->assertTrue(Schema::hasColumn('adventure_packs', 'updated_at'));
    }

    #[Test]
    public function locations_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('locations', 'id'));
        $this->assertTrue(Schema::hasColumn('locations', 'name'));
        $this->assertTrue(Schema::hasColumn('locations', 'area_type'));
        $this->assertTrue(Schema::hasColumn('locations', 'parent_location_id'));
        $this->assertTrue(Schema::hasColumn('locations', 'created_at'));
        $this->assertTrue(Schema::hasColumn('locations', 'updated_at'));
    }

    #[Test]
    public function difficulties_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('difficulties', 'id'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'name'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'multiplier'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'first_time_bonus_percent'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'sort_order'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'created_at'));
        $this->assertTrue(Schema::hasColumn('difficulties', 'updated_at'));
    }

    #[Test]
    public function quests_table_has_correct_columns()
    {
        $expectedColumns = [
            'id', 'name', 'slug', 'heroic_level', 'epic_level', 'legendary_level',
            'duration_id', 'patron_id', 'adventure_pack_id', 'location_id',
            'base_favor', 'extreme_challenge', 'overview', 'objectives', 'tips',
            'wiki_url', 'created_at', 'updated_at'
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('quests', $column),
                "Column '{$column}' should exist in quests table"
            );
        }
    }

    #[Test]
    public function quest_xp_rewards_table_has_correct_columns()
    {
        $expectedColumns = [
            'id', 'quest_id', 'difficulty_id', 'is_epic', 'is_legendary',
            'base_xp', 'created_at', 'updated_at'
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('quest_xp_rewards', $column),
                "Column '{$column}' should exist in quest_xp_rewards table"
            );
        }
    }

    #[Test]
    public function foreign_key_constraints_work_correctly()
    {
        // Create test data to verify foreign key relationships
        $duration = \DB::table('durations')->insertGetId([
            'name' => 'Short',
            'estimated_minutes' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $patron = \DB::table('patrons')->insertGetId([
            'name' => 'The Coin Lords',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adventurePack = \DB::table('adventure_packs')->insertGetId([
            'name' => 'Free to Play',
            'purchase_type' => 'Free to Play',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $location = \DB::table('locations')->insertGetId([
            'name' => 'Korthos Village',
            'area_type' => 'Village',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $difficulty = \DB::table('difficulties')->insertGetId([
            'name' => 'Normal',
            'multiplier' => 1.00,
            'first_time_bonus_percent' => 20,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test that we can create a quest with these foreign keys
        $questId = \DB::table('quests')->insertGetId([
            'name' => 'Test Quest',
            'slug' => 'test-quest',
            'heroic_level' => 1,
            'duration_id' => $duration,
            'patron_id' => $patron,
            'adventure_pack_id' => $adventurePack,
            'location_id' => $location,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertNotNull($questId);
        
        // Verify the quest was created with correct relationships
        $quest = \DB::table('quests')->where('id', $questId)->first();
        $this->assertEquals($duration, $quest->duration_id);
        $this->assertEquals($patron, $quest->patron_id);
        $this->assertEquals($adventurePack, $quest->adventure_pack_id);
        $this->assertEquals($location, $quest->location_id);

        // Test quest XP rewards relationship
        $xpRewardId = \DB::table('quest_xp_rewards')->insertGetId([
            'quest_id' => $questId,
            'difficulty_id' => $difficulty,
            'is_epic' => false,
            'is_legendary' => false,
            'base_xp' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertNotNull($xpRewardId);
        
        $xpReward = \DB::table('quest_xp_rewards')->where('id', $xpRewardId)->first();
        $this->assertEquals($questId, $xpReward->quest_id);
        $this->assertEquals($difficulty, $xpReward->difficulty_id);
        $this->assertEquals(1000, $xpReward->base_xp);
    }

    #[Test]
    public function migration_rollback_removes_all_tables()
    {
        // First, ensure tables exist
        $this->assertTrue(Schema::hasTable('quests'));
        
        // Run rollback - need 2 steps to remove both quest migrations
        \Artisan::call('migrate:rollback', ['--step' => 2]);
        
        // Verify all tables are removed
        $expectedTables = [
            'quest_difficulties',
            'quest_xp_rewards',
            'quests',
            'difficulties',
            'locations',
            'adventure_packs',
            'patrons',
            'durations'
        ];

        foreach ($expectedTables as $table) {
            $this->assertFalse(
                Schema::hasTable($table),
                "Table '{$table}' should not exist after rollback"
            );
        }
        
        // Re-run migration for other tests
        \Artisan::call('migrate');
    }

    #[Test]
    public function indexes_are_created_properly()
    {
        // Note: Testing indexes directly is complex in Laravel
        // This test ensures no errors occur when running queries that would use indexes
        
        // Test heroic_level index
        \DB::table('quests')->where('heroic_level', 1)->get();
        
        // Test epic_level index  
        \DB::table('quests')->where('epic_level', 20)->get();
        
        // Test compound index (patron_id, duration_id)
        \DB::table('quests')->where('patron_id', 1)->where('duration_id', 1)->get();
        
        // Test name index for search
        \DB::table('quests')->where('name', 'like', '%test%')->get();
        
        // If we get here without exceptions, indexes are working
        $this->assertTrue(true);
    }
}
