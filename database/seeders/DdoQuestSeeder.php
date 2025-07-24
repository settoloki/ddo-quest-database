<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\Quest;
use App\Models\Ddo\Patron;
use App\Models\Ddo\AdventurePack;
use App\Models\Ddo\Location;
use App\Models\Ddo\Duration;
use App\Models\Ddo\Difficulty;

class DdoQuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get reference data
        $patrons = Patron::all()->keyBy('name');
        $adventurePacks = AdventurePack::all()->keyBy('name');
        $locations = Location::all()->keyBy('name');
        $durations = Duration::all()->keyBy('name');
        $difficulties = Difficulty::all();

        // Sample quests data
        $questsData = [
            [
                'name' => 'Waterworks',
                'overview' => 'A classic low-level quest chain in the sewers beneath Stormreach.',
                'heroic_level' => 3,
                'patron_name' => 'The Coin Lords',
                'adventure_pack_name' => null, // Free to Play
                'location_name' => 'Marketplace',
                'duration_name' => 'Medium',
                'difficulties' => ['Casual', 'Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'Tomb of the Astrologer',
                'overview' => 'Investigate the mysterious tomb and uncover ancient secrets.',
                'heroic_level' => 5,
                'patron_name' => 'The Harpers',
                'adventure_pack_name' => 'Gianthold',
                'location_name' => 'Gianthold',
                'duration_name' => 'Medium',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'The Pit',
                'overview' => 'Descend into the depths and face the challenges within.',
                'heroic_level' => 12,
                'patron_name' => 'The Silver Flame',
                'adventure_pack_name' => 'Gianthold',
                'location_name' => 'Gianthold',
                'duration_name' => 'Long',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'The Dreaming Dark',
                'overview' => 'Uncover the conspiracy of the Dreaming Dark in this challenging quest.',
                'heroic_level' => 16,
                'patron_name' => 'House Phiarlan',
                'adventure_pack_name' => 'Dreaming Dark',
                'location_name' => 'House Phiarlan',
                'duration_name' => 'Very Long',
                'difficulties' => ['Hard', 'Elite'],
            ],
            [
                'name' => 'Korthos Village: Heyton\'s Rest',
                'overview' => 'Help the villagers of Korthos deal with their undead problem.',
                'heroic_level' => 1,
                'patron_name' => 'The Coin Lords',
                'adventure_pack_name' => null, // Free to Play
                'location_name' => 'Korthos Island',
                'duration_name' => 'Short',
                'difficulties' => ['Casual', 'Normal', 'Hard'],
            ],
            [
                'name' => 'The Bloody Crypt',
                'overview' => 'Explore the haunted crypt and put the restless spirits to rest.',
                'heroic_level' => 4,
                'patron_name' => 'The Silver Flame',
                'adventure_pack_name' => null, // Free to Play
                'location_name' => 'Marketplace',
                'duration_name' => 'Medium',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'Tempest\'s Spine',
                'overview' => 'Navigate the dangerous spine and face the elemental challenges.',
                'heroic_level' => 7,
                'patron_name' => 'The Twelve',
                'adventure_pack_name' => 'The Twelve',
                'location_name' => 'The Twelve',
                'duration_name' => 'Long',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'The Vault of Night',
                'overview' => 'Brave the deadly traps and creatures in this challenging raid.',
                'heroic_level' => 12,
                'patron_name' => 'The Coin Lords',
                'adventure_pack_name' => 'The Vault of Night',
                'location_name' => 'Marketplace',
                'duration_name' => 'Very Long',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'Delera\'s Tomb',
                'overview' => 'Uncover the mystery of Delera\'s curse in this multi-part adventure.',
                'heroic_level' => 2,
                'patron_name' => 'The Coin Lords',
                'adventure_pack_name' => null, // Free to Play
                'location_name' => 'Marketplace',
                'duration_name' => 'Medium',
                'difficulties' => ['Casual', 'Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'The Desert\'s Despair',
                'overview' => 'Journey into the harsh desert and survive its many dangers.',
                'heroic_level' => 9,
                'patron_name' => 'The Purple Dragon Knights',
                'adventure_pack_name' => 'Desert of Desolation',
                'location_name' => 'The Twelve',
                'duration_name' => 'Long',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'Threnal',
                'overview' => 'Explore the ancient dwarven ruins and their mechanical guardians.',
                'heroic_level' => 4,
                'patron_name' => 'House Kundarak',
                'adventure_pack_name' => 'Threnal',
                'location_name' => 'House Kundarak',
                'duration_name' => 'Medium',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
            [
                'name' => 'Diplomatic Impunity',
                'overview' => 'Navigate political intrigue in this social stealth adventure.',
                'heroic_level' => 8,
                'patron_name' => 'House Phiarlan',
                'adventure_pack_name' => 'Diplomatic Impunity',
                'location_name' => 'House Phiarlan',
                'duration_name' => 'Medium',
                'difficulties' => ['Normal', 'Hard', 'Elite'],
            ],
        ];

        // Create quests
        foreach ($questsData as $questData) {
            $quest = Quest::create([
                'name' => $questData['name'],
                'overview' => $questData['overview'],
                'heroic_level' => $questData['heroic_level'],
                'patron_id' => isset($questData['patron_name']) ? $patrons[$questData['patron_name']]->id ?? null : null,
                'adventure_pack_id' => isset($questData['adventure_pack_name']) ? $adventurePacks[$questData['adventure_pack_name']]->id ?? null : null,
                'location_id' => isset($questData['location_name']) ? $locations[$questData['location_name']]->id ?? null : null,
                'duration_id' => isset($questData['duration_name']) ? $durations[$questData['duration_name']]->id ?? null : null,
            ]);

            // Attach difficulties
            if (isset($questData['difficulties'])) {
                $difficultyIds = [];
                foreach ($questData['difficulties'] as $difficultyName) {
                    $difficulty = $difficulties->firstWhere('name', $difficultyName);
                    if ($difficulty) {
                        $difficultyIds[] = $difficulty->id;
                    }
                }
                $quest->difficulties()->attach($difficultyIds);
            }
        }

        $this->command->info('Sample DDO quests seeded successfully!');
    }
}
