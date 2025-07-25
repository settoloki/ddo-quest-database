<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\QuestXpReward;
use App\Models\Ddo\Quest;
use App\Models\Ddo\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\QuestXpReward>
 */
class QuestXpRewardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestXpReward::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quest_id' => Quest::factory(),
            'difficulty_id' => Difficulty::factory(),
            'is_epic' => false,
            'is_legendary' => false,
            'base_xp' => $this->faker->numberBetween(500, 5000),
        ];
    }

    /**
     * Create a heroic XP reward.
     */
    public function heroic(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_epic' => false,
            'is_legendary' => false,
            'base_xp' => $this->faker->numberBetween(500, 3000),
        ]);
    }

    /**
     * Create an epic XP reward.
     */
    public function epic(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_epic' => true,
            'is_legendary' => false,
            'base_xp' => $this->faker->numberBetween(5000, 15000),
        ]);
    }

    /**
     * Create a legendary XP reward.
     */
    public function legendary(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_epic' => false,
            'is_legendary' => true,
            'base_xp' => $this->faker->numberBetween(10000, 30000),
        ]);
    }

    /**
     * Create a low XP reward.
     */
    public function lowXp(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'base_xp' => $this->faker->numberBetween(100, 1000),
        ]);
    }

    /**
     * Create a high XP reward.
     */
    public function highXp(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'base_xp' => $this->faker->numberBetween(5000, 25000),
        ]);
    }

    /**
     * Create XP reward for a specific difficulty.
     */
    public function forDifficulty(string $difficultyName): Factory
    {
        return $this->state(function (array $attributes) use ($difficultyName) {
            $difficulty = Difficulty::factory()->state(['name' => $difficultyName])->create();
            
            return [
                'difficulty_id' => $difficulty->id,
            ];
        });
    }

    /**
     * Create casual difficulty XP reward.
     */
    public function casual(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_id' => Difficulty::factory()->casual(),
        ]);
    }

    /**
     * Create normal difficulty XP reward.
     */
    public function normal(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_id' => Difficulty::factory()->normal(),
        ]);
    }

    /**
     * Create hard difficulty XP reward.
     */
    public function hard(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_id' => Difficulty::factory()->hard(),
        ]);
    }

    /**
     * Create elite difficulty XP reward.
     */
    public function elite(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_id' => Difficulty::factory()->elite(),
        ]);
    }

    /**
     * Create reaper difficulty XP reward.
     */
    public function reaper(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_id' => Difficulty::factory()->reaper(),
        ]);
    }
}
