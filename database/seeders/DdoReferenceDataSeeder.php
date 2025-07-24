<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DdoReferenceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed reference data in dependency order
        $this->call([
            DdoDurationSeeder::class,
            DdoDifficultySeeder::class,
            DdoPatronSeeder::class,
            DdoAdventurePackSeeder::class,
            DdoLocationSeeder::class,
        ]);

        $this->command->info('DDO reference data seeded successfully!');
    }
}
