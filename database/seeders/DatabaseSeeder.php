<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed DDO reference data first (required for quest relationships)
        $this->call([
            DdoReferenceDataSeeder::class,
        ]);

        // Seed sample quests if in development environment
        if (app()->environment('local', 'testing')) {
            $this->call([
                DdoQuestSeeder::class,
            ]);
        }

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->command->info('Database seeding completed successfully!');
    }
}
