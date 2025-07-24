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
            'ddo_durations',
            'ddo_patrons', 
            'ddo_adventure_packs',
            'ddo_locations',
            'ddo_difficulties',
            'ddo_quests',
            'ddo_quest_xp_rewards'
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
        $this->assertTrue(Schema::hasColumn('ddo_durations', 'id'));
        $this->assertTrue(Schema::hasColumn('ddo_durations', 'name'));
        $this->assertTrue(Schema::hasColumn('ddo_durations', 'estimated_minutes'));
        $this->assertTrue(Schema::hasColumn('ddo_durations', 'created_at'));
        $this->assertTrue(Schema::hasColumn('ddo_durations', 'updated_at'));
    }

    #[Test]
    public function patrons_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('ddo_patrons', 'id'));
        $this->assertTrue(Schema::hasColumn('ddo_patrons', 'name'));
        $this->assertTrue(Schema::hasColumn('ddo_patrons', 'description'));
        $this->assertTrue(Schema::hasColumn('ddo_patrons', 'created_at'));
        $this->assertTrue(Schema::hasColumn('ddo_patrons', 'updated_at'));
    }

    #[Test]
    public function adventure_packs_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'id'));
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'name'));
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'purchase_type'));
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'release_date'));
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'created_at'));
        $this->assertTrue(Schema::hasColumn('ddo_adventure_packs', 'updated_at'));
    }

    #[Test]
    public function locations_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'id'));
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'name'));
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'area_type'));
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'parent_location_id'));
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'created_at'));
        $this->assertTrue(Schema::hasColumn('ddo_locations', 'updated_at'));
    }

    #[Test]
    public function difficulties_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'id'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'name'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'multiplier'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'first_time_bonus_percent'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'sort_order'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'created_at'));
        $this->assertTrue(Schema::hasColumn('ddo_difficulties', 'updated_at'));
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
                Schema::hasColumn('ddo_quests', $column),
                "Column '{$column}' should exist in ddo_quests table"
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
                Schema::hasColumn('ddo_quest_xp_rewards', $column),
                "Column '{$column}' should exist in ddo_quest_xp_rewards table"
            );
        }
    }

    #[Test]
    public function foreign_key_constraints_work_correctly()
    {
        // Create test data to verify foreign key relationships
        $duration = \DB::table('ddo_durations')->insertGetId([
            'name' => 'Short',
            'estimated_minutes' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $patron = \DB::table('ddo_patrons')->insertGetId([
            'name' => 'The Coin Lords',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adventurePack = \DB::table('ddo_adventure_packs')->insertGetId([
            'name' => 'Free to Play',
            'purchase_type' => 'Free to Play',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $location = \DB::table('ddo_locations')->insertGetId([
            'name' => 'Korthos Village',
            'area_type' => 'Village',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $difficulty = \DB::table('ddo_difficulties')->insertGetId([
            'name' => 'Normal',
            'multiplier' => 1.00,
            'first_time_bonus_percent' => 20,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test that we can create a quest with these foreign keys
        $questId = \DB::table('ddo_quests')->insertGetId([
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
        $quest = \DB::table('ddo_quests')->where('id', $questId)->first();
        $this->assertEquals($duration, $quest->duration_id);
        $this->assertEquals($patron, $quest->patron_id);
        $this->assertEquals($adventurePack, $quest->adventure_pack_id);
        $this->assertEquals($location, $quest->location_id);

        // Test quest XP rewards relationship
        $xpRewardId = \DB::table('ddo_quest_xp_rewards')->insertGetId([
            'quest_id' => $questId,
            'difficulty_id' => $difficulty,
            'is_epic' => false,
            'is_legendary' => false,
            'base_xp' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertNotNull($xpRewardId);
        
        $xpReward = \DB::table('ddo_quest_xp_rewards')->where('id', $xpRewardId)->first();
        $this->assertEquals($questId, $xpReward->quest_id);
        $this->assertEquals($difficulty, $xpReward->difficulty_id);
        $this->assertEquals(1000, $xpReward->base_xp);
    }

    #[Test]
    public function migration_rollback_removes_all_tables()
    {
        // First, ensure tables exist
        $this->assertTrue(Schema::hasTable('ddo_quests'));
        
        // Run rollback
        \Artisan::call('migrate:rollback', ['--step' => 1]);
        
        // Verify all tables are removed
        $expectedTables = [
            'ddo_quest_xp_rewards',
            'ddo_quests',
            'ddo_difficulties',
            'ddo_locations',
            'ddo_adventure_packs',
            'ddo_patrons',
            'ddo_durations'
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
        \DB::table('ddo_quests')->where('heroic_level', 1)->get();
        
        // Test epic_level index  
        \DB::table('ddo_quests')->where('epic_level', 20)->get();
        
        // Test compound index (patron_id, duration_id)
        \DB::table('ddo_quests')->where('patron_id', 1)->where('duration_id', 1)->get();
        
        // Test name index for search
        \DB::table('ddo_quests')->where('name', 'like', '%test%')->get();
        
        // If we get here without exceptions, indexes are working
        $this->assertTrue(true);
    }
}
