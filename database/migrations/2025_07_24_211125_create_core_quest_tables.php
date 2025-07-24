<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create durations table first (no dependencies)
        Schema::create('durations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // 'Short', 'Medium', 'Long'
            $table->smallInteger('estimated_minutes')->unsigned()->nullable();
            $table->timestamps();
        });

        // Create patrons table
        Schema::create('patrons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create adventure_packs table
        Schema::create('adventure_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('purchase_type', ['Free to Play', 'Premium', 'VIP', 'Expansion']);
            $table->date('release_date')->nullable();
            $table->timestamps();
        });

        // Create locations table with hierarchical relationships
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('area_type', ['Village', 'Island', 'City', 'Wilderness', 'Dungeon']);
            $table->foreignId('parent_location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->timestamps();
        });

        // Create difficulties table
        Schema::create('difficulties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique(); // 'Casual', 'Normal', 'Hard', 'Elite', 'Reaper'
            $table->decimal('multiplier', 3, 2); // 1.00, 1.25, 1.50, etc.
            $table->tinyInteger('first_time_bonus_percent')->unsigned()->nullable(); // 20, 45, etc.
            $table->tinyInteger('sort_order')->unsigned();
            $table->timestamps();
        });

        // Create main quests table
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->tinyInteger('heroic_level')->unsigned()->nullable();
            $table->tinyInteger('epic_level')->unsigned()->nullable();
            $table->tinyInteger('legendary_level')->unsigned()->nullable();
            $table->foreignId('duration_id')->nullable()->constrained('durations')->onDelete('set null');
            $table->foreignId('patron_id')->nullable()->constrained('patrons')->onDelete('set null');
            $table->foreignId('adventure_pack_id')->nullable()->constrained('adventure_packs')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->smallInteger('base_favor')->unsigned()->default(0);
            $table->boolean('extreme_challenge')->default(false);
            $table->text('overview')->nullable();
            $table->text('objectives')->nullable();
            $table->text('tips')->nullable();
            $table->string('wiki_url', 512)->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index('heroic_level');
            $table->index('epic_level');
            $table->index('legendary_level');
            $table->index(['patron_id', 'duration_id']);
            $table->index('name'); // For search
        });

        // Create quest XP rewards table
        Schema::create('quest_xp_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('quests')->onDelete('cascade');
            $table->foreignId('difficulty_id')->constrained('difficulties');
            $table->boolean('is_epic')->default(false);
            $table->boolean('is_legendary')->default(false);
            $table->integer('base_xp')->unsigned();
            $table->timestamps();

            $table->unique(['quest_id', 'difficulty_id', 'is_epic', 'is_legendary'], 'unique_quest_difficulty_type');
            $table->index(['quest_id', 'difficulty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_xp_rewards');
        Schema::dropIfExists('quests');
        Schema::dropIfExists('difficulties');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('adventure_packs');
        Schema::dropIfExists('patrons');
        Schema::dropIfExists('durations');
    }
};
