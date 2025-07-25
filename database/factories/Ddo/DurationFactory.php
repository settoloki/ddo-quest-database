<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\Duration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\Duration>
 */
class DurationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Duration::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durations = [
            ['name' => 'Short', 'estimated_minutes' => 15],
            ['name' => 'Medium', 'estimated_minutes' => 30],
            ['name' => 'Long', 'estimated_minutes' => 60],
        ];

        $duration = $this->faker->randomElement($durations);

        return [
            'name' => $duration['name'],
            'estimated_minutes' => $duration['estimated_minutes'],
        ];
    }

    /**
     * Create a short duration.
     */
    public function short(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Short',
            'estimated_minutes' => 15,
        ]);
    }

    /**
     * Create a medium duration.
     */
    public function medium(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Medium',
            'estimated_minutes' => 30,
        ]);
    }

    /**
     * Create a long duration.
     */
    public function long(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Long',
            'estimated_minutes' => 60,
        ]);
    }
}
