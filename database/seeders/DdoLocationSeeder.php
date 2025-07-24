<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\Location;

class DdoLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First create root locations (no parent)
        $rootLocations = [
            // Islands/Major Areas
            ['name' => 'Korthos Island', 'area_type' => 'Island'],
            ['name' => 'Stormreach', 'area_type' => 'City'], 
            ['name' => 'Three Barrel Cove', 'area_type' => 'Village'],
            ['name' => 'The Twelve', 'area_type' => 'City'],
            ['name' => 'Eveningstar', 'area_type' => 'Village'],
            ['name' => 'The King\'s Forest', 'area_type' => 'Wilderness'],
            ['name' => 'Thunderholme', 'area_type' => 'City'],
            ['name' => 'The Underdark', 'area_type' => 'Wilderness'],
            ['name' => 'Ravenloft', 'area_type' => 'Wilderness'],
            ['name' => 'Sharn', 'area_type' => 'City'],
            ['name' => 'Feywild', 'area_type' => 'Wilderness'],
            ['name' => 'Isle of Dread', 'area_type' => 'Island'],
            ['name' => 'Myth Drannor', 'area_type' => 'City'],
        ];

        foreach ($rootLocations as $location) {
            Location::firstOrCreate(
                ['name' => $location['name']],
                $location
            );
        }

        // Now create child locations with parents
        $childLocations = [
            // Korthos Island areas
            ['name' => 'Korthos Village', 'area_type' => 'Village', 'parent' => 'Korthos Island'],
            ['name' => 'Shipwreck Shore', 'area_type' => 'Wilderness', 'parent' => 'Korthos Island'],
            ['name' => 'Korthos Island Caverns', 'area_type' => 'Dungeon', 'parent' => 'Korthos Island'],

            // Stormreach areas  
            ['name' => 'The Harbor', 'area_type' => 'City', 'parent' => 'Stormreach'],
            ['name' => 'The Marketplace', 'area_type' => 'City', 'parent' => 'Stormreach'],
            ['name' => 'House Deneith', 'area_type' => 'City', 'parent' => 'Stormreach'],
            ['name' => 'House Phiarlan', 'area_type' => 'City', 'parent' => 'Stormreach'],
            ['name' => 'House Jorasco', 'area_type' => 'City', 'parent' => 'Stormreach'],
            ['name' => 'House Kundarak', 'area_type' => 'City', 'parent' => 'Stormreach'],

            // Wilderness Areas
            ['name' => 'Cerulean Hills', 'area_type' => 'Wilderness', 'parent' => 'Stormreach'],
            ['name' => 'Tangleroot Gorge', 'area_type' => 'Wilderness', 'parent' => 'Stormreach'],
            ['name' => 'Three Barrel Cove Wilderness', 'area_type' => 'Wilderness', 'parent' => 'Three Barrel Cove'],
            ['name' => 'The Red Fens', 'area_type' => 'Wilderness', 'parent' => 'Stormreach'],
            ['name' => 'The Desert', 'area_type' => 'Wilderness', 'parent' => 'Stormreach'],
            ['name' => 'The Restless Isles', 'area_type' => 'Island', 'parent' => 'Stormreach'],
            ['name' => 'Gianthold', 'area_type' => 'Wilderness', 'parent' => 'Stormreach'],

            // Dungeons and Specific Locations
            ['name' => 'The Catacombs', 'area_type' => 'Dungeon', 'parent' => 'Stormreach'],
            ['name' => 'The Necropolis', 'area_type' => 'Dungeon', 'parent' => 'The Desert'],
            ['name' => 'Splinterskull', 'area_type' => 'Dungeon', 'parent' => 'Three Barrel Cove'],
            ['name' => 'The Vault of Night', 'area_type' => 'Dungeon', 'parent' => 'The Harbor'],

            // Sharn Districts
            ['name' => 'Lower Dura', 'area_type' => 'City', 'parent' => 'Sharn'],
            ['name' => 'Upper Central', 'area_type' => 'City', 'parent' => 'Sharn'],
            ['name' => 'The Cogs', 'area_type' => 'Dungeon', 'parent' => 'Sharn'],

            // Ravenloft Areas
            ['name' => 'Barovia', 'area_type' => 'Village', 'parent' => 'Ravenloft'],
            ['name' => 'Castle Ravenloft', 'area_type' => 'Dungeon', 'parent' => 'Ravenloft'],

            // Feywild Areas
            ['name' => 'The Summer Court', 'area_type' => 'City', 'parent' => 'Feywild'],
            ['name' => 'The Autumn Grove', 'area_type' => 'Wilderness', 'parent' => 'Feywild'],
        ];

        foreach ($childLocations as $childData) {
            $parent = Location::where('name', $childData['parent'])->first();
            if ($parent) {
                Location::firstOrCreate(
                    ['name' => $childData['name']],
                    [
                        'name' => $childData['name'],
                        'area_type' => $childData['area_type'],
                        'parent_location_id' => $parent->id,
                    ]
                );
            }
        }
    }
}
