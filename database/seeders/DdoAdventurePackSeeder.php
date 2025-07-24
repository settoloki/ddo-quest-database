<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\AdventurePack;
use Carbon\Carbon;

class DdoAdventurePackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adventurePacks = [
            // Free to Play Content
            [
                'name' => 'Free to Play',
                'purchase_type' => 'Free to Play',
                'release_date' => Carbon::parse('2006-02-28'), // DDO Launch
            ],
            
            // Premium Adventure Packs
            [
                'name' => 'The Catacombs',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2006-05-01'),
            ],
            [
                'name' => 'Sentinels of Stormreach',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2007-08-01'),
            ],
            [
                'name' => 'Demon Sands',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2008-03-01'),
            ],
            [
                'name' => 'Assault on Splinterskull',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2008-06-01'),
            ],
            [
                'name' => 'The Necropolis',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2008-09-01'),
            ],
            [
                'name' => 'Tangleroot Gorge',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2009-01-01'),
            ],
            [
                'name' => 'The Depths of Despair',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2009-04-01'),
            ],
            [
                'name' => 'The Vault of Night',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2009-07-01'),
            ],
            [
                'name' => 'The Ruins of Gianthold',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2009-10-01'),
            ],
            [
                'name' => 'The Restless Isles',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2010-02-01'),
            ],
            [
                'name' => 'The Red Fens',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2010-05-01'),
            ],
            [
                'name' => 'The Vale of Twilight',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2010-08-01'),
            ],
            [
                'name' => 'The Twelve',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2010-11-01'),
            ],
            [
                'name' => 'Reign of Madness',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2011-02-01'),
            ],
            [
                'name' => 'Secrets of the Artificers',
                'purchase_type' => 'Premium',
                'release_date' => Carbon::parse('2011-05-01'),
            ],
            
            // Expansions
            [
                'name' => 'Menace of the Underdark',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2012-06-25'),
            ],
            [
                'name' => 'Shadowfell Conspiracy',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2013-08-19'),
            ],
            [
                'name' => 'Ravenloft',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2017-12-06'),
            ],
            [
                'name' => 'Sharn: The City of Towers',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2019-08-19'),
            ],
            [
                'name' => 'Feywild',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2021-03-03'),
            ],
            [
                'name' => 'Isle of Dread',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2022-09-28'),
            ],
            [
                'name' => 'Myth Drannor',
                'purchase_type' => 'Expansion',
                'release_date' => Carbon::parse('2023-06-28'),
            ],
        ];

        foreach ($adventurePacks as $pack) {
            AdventurePack::firstOrCreate(
                ['name' => $pack['name']],
                $pack
            );
        }
    }
}
