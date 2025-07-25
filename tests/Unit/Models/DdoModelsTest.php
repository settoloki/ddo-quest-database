<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Ddo\Quest;
use App\Models\Ddo\Duration;
use App\Models\Ddo\Patron;
use App\Models\Ddo\AdventurePack;
use App\Models\Ddo\Location;
use App\Models\Ddo\Difficulty;
use App\Models\Ddo\QuestXpReward;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DdoModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_quest_belongs_to_duration(): void
    {
        $duration = Duration::factory()->create();
        $quest = Quest::factory()->create(['duration_id' => $duration->id]);

        $this->assertInstanceOf(Duration::class, $quest->duration);
        $this->assertEquals($duration->id, $quest->duration->id);
    }

    public function test_quest_belongs_to_patron(): void
    {
        $patron = Patron::factory()->create();
        $quest = Quest::factory()->create(['patron_id' => $patron->id]);

        $this->assertInstanceOf(Patron::class, $quest->patron);
        $this->assertEquals($patron->id, $quest->patron->id);
    }

    public function test_quest_belongs_to_adventure_pack(): void
    {
        $adventurePack = AdventurePack::factory()->create();
        $quest = Quest::factory()->create(['adventure_pack_id' => $adventurePack->id]);

        $this->assertInstanceOf(AdventurePack::class, $quest->adventurePack);
        $this->assertEquals($adventurePack->id, $quest->adventurePack->id);
    }

    public function test_quest_belongs_to_location(): void
    {
        $location = Location::factory()->create();
        $quest = Quest::factory()->create(['location_id' => $location->id]);

        $this->assertInstanceOf(Location::class, $quest->location);
        $this->assertEquals($location->id, $quest->location->id);
    }

    public function test_quest_has_many_xp_rewards(): void
    {
        $quest = Quest::factory()->create();
        $difficulty = Difficulty::factory()->create();
        $xpReward = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id
        ]);

        $this->assertTrue($quest->xpRewards->contains($xpReward));
        $this->assertInstanceOf(QuestXpReward::class, $quest->xpRewards->first());
    }

    public function test_duration_has_many_quests(): void
    {
        $duration = Duration::factory()->create();
        $quest = Quest::factory()->create(['duration_id' => $duration->id]);

        $this->assertTrue($duration->quests->contains($quest));
        $this->assertInstanceOf(Quest::class, $duration->quests->first());
    }

    public function test_patron_has_many_quests(): void
    {
        $patron = Patron::factory()->create();
        $quest = Quest::factory()->create(['patron_id' => $patron->id]);

        $this->assertTrue($patron->quests->contains($quest));
        $this->assertInstanceOf(Quest::class, $patron->quests->first());
    }

    public function test_adventure_pack_has_many_quests(): void
    {
        $adventurePack = AdventurePack::factory()->create();
        $quest = Quest::factory()->create(['adventure_pack_id' => $adventurePack->id]);

        $this->assertTrue($adventurePack->quests->contains($quest));
        $this->assertInstanceOf(Quest::class, $adventurePack->quests->first());
    }

    public function test_location_has_many_quests(): void
    {
        $location = Location::factory()->create();
        $quest = Quest::factory()->create(['location_id' => $location->id]);

        $this->assertTrue($location->quests->contains($quest));
        $this->assertInstanceOf(Quest::class, $location->quests->first());
    }

    public function test_location_can_have_parent_child_relationships(): void
    {
        $parentLocation = Location::factory()->create(['name' => 'Parent Location Test']);
        $childLocation = Location::factory()->create([
            'name' => 'Child Location Test',
            'parent_location_id' => $parentLocation->id
        ]);

        $this->assertInstanceOf(Location::class, $childLocation->parentLocation);
        $this->assertEquals($parentLocation->id, $childLocation->parentLocation->id);
        $this->assertTrue($parentLocation->childLocations->contains($childLocation));
    }

    public function test_quest_xp_reward_belongs_to_quest(): void
    {
        $quest = Quest::factory()->create();
        $difficulty = Difficulty::factory()->create();
        $xpReward = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id
        ]);

        $this->assertInstanceOf(Quest::class, $xpReward->quest);
        $this->assertEquals($quest->id, $xpReward->quest->id);
    }

    public function test_quest_xp_reward_belongs_to_difficulty(): void
    {
        $quest = Quest::factory()->create();
        $difficulty = Difficulty::factory()->create();
        $xpReward = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id
        ]);

        $this->assertInstanceOf(Difficulty::class, $xpReward->difficulty);
        $this->assertEquals($difficulty->id, $xpReward->difficulty->id);
    }

    public function test_quest_scopes_filter_correctly(): void
    {
        $duration = Duration::factory()->create(['name' => 'Short']);
        $patron = Patron::factory()->create(['name' => 'The Coin Lords']);
        
        $quest1 = Quest::factory()->create([
            'heroic_level' => 5,
            'duration_id' => $duration->id,
            'patron_id' => $patron->id
        ]);
        
        $quest2 = Quest::factory()->create([
            'heroic_level' => 10,
            'duration_id' => $duration->id,
            'patron_id' => $patron->id
        ]);

        // Test byHeroicLevel scope
        $level5Quests = Quest::byHeroicLevel(5)->get();
        $this->assertTrue($level5Quests->contains($quest1));
        $this->assertFalse($level5Quests->contains($quest2));

        // Test byDuration scope
        $shortQuests = Quest::byDuration('Short')->get();
        $this->assertTrue($shortQuests->contains($quest1));
        $this->assertTrue($shortQuests->contains($quest2));

        // Test byPatron scope
        $coinLordsQuests = Quest::byPatron('The Coin Lords')->get();
        $this->assertTrue($coinLordsQuests->contains($quest1));
        $this->assertTrue($coinLordsQuests->contains($quest2));
    }

    public function test_xp_calculation_methods_work_correctly(): void
    {
        $difficulty = Difficulty::factory()->create([
            'multiplier' => 1.25,
            'first_time_bonus_percent' => 20
        ]);
        
        $quest = Quest::factory()->create();
        $xpReward = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id,
            'base_xp' => 1000
        ]);

        // Test calculated XP attribute (base XP * multiplier)
        $expectedXp = 1000 * 1.25; // 1250
        $this->assertEquals($expectedXp, $xpReward->calculated_xp);

        // Test XP with first time bonus
        $expectedFirstTimeXp = $expectedXp * 1.20; // 1500
        $this->assertEquals($expectedFirstTimeXp, $xpReward->xp_with_first_time_bonus);
    }

    public function test_quest_type_attribute_works_correctly(): void
    {
        $quest = Quest::factory()->create();
        $difficulty = Difficulty::factory()->create();

        // Test heroic quest
        $heroicXp = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id,
            'is_epic' => false,
            'is_legendary' => false
        ]);
        $this->assertEquals('Heroic', $heroicXp->quest_type);

        // Test epic quest
        $epicXp = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id,
            'is_epic' => true,
            'is_legendary' => false
        ]);
        $this->assertEquals('Epic', $epicXp->quest_type);

        // Test legendary quest
        $legendaryXp = QuestXpReward::factory()->create([
            'quest_id' => $quest->id,
            'difficulty_id' => $difficulty->id,
            'is_epic' => false,
            'is_legendary' => true
        ]);
        $this->assertEquals('Legendary', $legendaryXp->quest_type);
    }

    public function test_models_have_proper_mass_assignment_protection(): void
    {
        // Test that models protect against mass assignment of non-fillable fields
        $questData = [
            'name' => 'Test Quest',
            'id' => 999999, // Should be protected
            'created_at' => now(), // Should be protected
        ];

        $quest = new Quest($questData);
        $this->assertEquals('Test Quest', $quest->name);
        $this->assertNull($quest->id); // Should not be set via mass assignment
        $this->assertNull($quest->created_at); // Should not be set via mass assignment
    }

    public function test_quest_auto_generates_slug_on_creation(): void
    {
        $questName = 'Test Quest Name';
        $quest = new Quest(['name' => $questName]);
        $quest->save();
        
        $this->assertEquals('test-quest-name', $quest->slug);
    }

    public function test_quest_updates_slug_when_name_changes(): void
    {
        $quest = new Quest(['name' => 'Original Name']);
        $quest->save();
        $this->assertEquals('original-name', $quest->slug);

        $quest->update(['name' => 'Updated Name']);
        $this->assertEquals('updated-name', $quest->slug);
    }
}
