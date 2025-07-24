<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\Duration;

class DdoDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $durations = [
            [
                'name' => 'Short',
                'estimated_minutes' => 15,
            ],
            [
                'name' => 'Medium',
                'estimated_minutes' => 30,
            ],
            [
                'name' => 'Long',
                'estimated_minutes' => 45,
            ],
            [
                'name' => 'Very Long',
                'estimated_minutes' => 60,
            ],
        ];

        foreach ($durations as $duration) {
            Duration::firstOrCreate(
                ['name' => $duration['name']],
                $duration
            );
        }
    }
}
