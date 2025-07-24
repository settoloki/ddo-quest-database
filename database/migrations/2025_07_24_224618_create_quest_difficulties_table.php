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
        Schema::create('quest_difficulties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('quests')->onDelete('cascade');
            $table->foreignId('difficulty_id')->constrained('difficulties')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['quest_id', 'difficulty_id']);
            $table->index(['quest_id']);
            $table->index(['difficulty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_difficulties');
    }
};
