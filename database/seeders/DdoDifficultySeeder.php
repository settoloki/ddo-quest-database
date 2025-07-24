<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\Difficulty;

class DdoDifficultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $difficulties = [
            [
                'name' => 'Casual',
                'multiplier' => 1.00,
                'first_time_bonus_percent' => 20,
                'sort_order' => 1,
            ],
            [
                'name' => 'Normal',
                'multiplier' => 1.00,
                'first_time_bonus_percent' => 25,
                'sort_order' => 2,
            ],
            [
                'name' => 'Hard',
                'multiplier' => 1.25,
                'first_time_bonus_percent' => 30,
                'sort_order' => 3,
            ],
            [
                'name' => 'Elite',
                'multiplier' => 1.50,
                'first_time_bonus_percent' => 45,
                'sort_order' => 4,
            ],
            [
                'name' => 'Reaper',
                'multiplier' => 1.50,
                'first_time_bonus_percent' => 50,
                'sort_order' => 5,
            ],
        ];

        foreach ($difficulties as $difficulty) {
            Difficulty::firstOrCreate(
                ['name' => $difficulty['name']],
                $difficulty
            );
        }
    }
}
