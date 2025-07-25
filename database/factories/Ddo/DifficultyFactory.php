<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\Difficulty>
 */
class DifficultyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Difficulty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $difficulties = [
            ['name' => 'Casual', 'multiplier' => 1.00, 'first_time_bonus_percent' => 20, 'sort_order' => 1],
            ['name' => 'Normal', 'multiplier' => 1.00, 'first_time_bonus_percent' => 20, 'sort_order' => 2],
            ['name' => 'Hard', 'multiplier' => 1.25, 'first_time_bonus_percent' => 20, 'sort_order' => 3],
            ['name' => 'Elite', 'multiplier' => 1.50, 'first_time_bonus_percent' => 45, 'sort_order' => 4],
            ['name' => 'Reaper', 'multiplier' => 1.50, 'first_time_bonus_percent' => 45, 'sort_order' => 5],
        ];

        $difficulty = $this->faker->randomElement($difficulties);

        return [
            'name' => $difficulty['name'],
            'multiplier' => $difficulty['multiplier'],
            'first_time_bonus_percent' => $difficulty['first_time_bonus_percent'],
            'sort_order' => $difficulty['sort_order'],
        ];
    }

    /**
     * Create a casual difficulty.
     */
    public function casual(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Casual',
            'multiplier' => 1.00,
            'first_time_bonus_percent' => 20,
            'sort_order' => 1,
        ]);
    }

    /**
     * Create a normal difficulty.
     */
    public function normal(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Normal',
            'multiplier' => 1.00,
            'first_time_bonus_percent' => 20,
            'sort_order' => 2,
        ]);
    }

    /**
     * Create a hard difficulty.
     */
    public function hard(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Hard',
            'multiplier' => 1.25,
            'first_time_bonus_percent' => 20,
            'sort_order' => 3,
        ]);
    }

    /**
     * Create an elite difficulty.
     */
    public function elite(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Elite',
            'multiplier' => 1.50,
            'first_time_bonus_percent' => 45,
            'sort_order' => 4,
        ]);
    }

    /**
     * Create a reaper difficulty.
     */
    public function reaper(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Reaper',
            'multiplier' => 1.50,
            'first_time_bonus_percent' => 45,
            'sort_order' => 5,
        ]);
    }
}
