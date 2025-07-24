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
        Schema::create('ddo_durations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // 'Short', 'Medium', 'Long'
            $table->smallInteger('estimated_minutes')->unsigned()->nullable();
            $table->timestamps();
        });

        // Create patrons table
        Schema::create('ddo_patrons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create adventure_packs table
        Schema::create('ddo_adventure_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('purchase_type', ['Free to Play', 'Premium', 'VIP', 'Expansion']);
            $table->date('release_date')->nullable();
            $table->timestamps();
        });

        // Create locations table with hierarchical relationships
        Schema::create('ddo_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('area_type', ['Village', 'Island', 'City', 'Wilderness', 'Dungeon']);
            $table->foreignId('parent_location_id')->nullable()->constrained('ddo_locations')->onDelete('cascade');
            $table->timestamps();
        });

        // Create difficulties table
        Schema::create('ddo_difficulties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique(); // 'Casual', 'Normal', 'Hard', 'Elite', 'Reaper'
            $table->decimal('multiplier', 3, 2); // 1.00, 1.25, 1.50, etc.
            $table->tinyInteger('first_time_bonus_percent')->unsigned()->nullable(); // 20, 45, etc.
            $table->tinyInteger('sort_order')->unsigned();
            $table->timestamps();
        });

        // Create main DDO quests table
        Schema::create('ddo_quests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->tinyInteger('heroic_level')->unsigned()->nullable();
            $table->tinyInteger('epic_level')->unsigned()->nullable();
            $table->tinyInteger('legendary_level')->unsigned()->nullable();
            $table->foreignId('duration_id')->nullable()->constrained('ddo_durations')->onDelete('set null');
            $table->foreignId('patron_id')->nullable()->constrained('ddo_patrons')->onDelete('set null');
            $table->foreignId('adventure_pack_id')->nullable()->constrained('ddo_adventure_packs')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('ddo_locations')->onDelete('set null');
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
        Schema::create('ddo_quest_xp_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('ddo_quests')->onDelete('cascade');
            $table->foreignId('difficulty_id')->constrained('ddo_difficulties');
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
        Schema::dropIfExists('ddo_quest_xp_rewards');
        Schema::dropIfExists('ddo_quests');
        Schema::dropIfExists('ddo_difficulties');
        Schema::dropIfExists('ddo_locations');
        Schema::dropIfExists('ddo_adventure_packs');
        Schema::dropIfExists('ddo_patrons');
        Schema::dropIfExists('ddo_durations');
    }
};
