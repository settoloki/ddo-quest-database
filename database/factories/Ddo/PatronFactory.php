<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\Patron;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\Patron>
 */
class PatronFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patron::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patrons = [
            'The Coin Lords',
            'House Deneith',
            'House Phiarlan',
            'The Harpers',
            'House Jorasco',
            'The Twelve',
            'Agents of Argonnessen',
            'The Free Agents',
            'House Cannith',
            'House Kundarak',
            'The Gatekeepers',
            'The Silver Flame',
            'House Orien',
            'The Yugoloth',
            'Inspired Quarter',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($patrons),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }

    /**
     * Create The Coin Lords patron.
     */
    public function coinLords(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'The Coin Lords',
            'description' => 'The merchant princes of Stormreach.',
        ]);
    }

    /**
     * Create House Deneith patron.
     */
    public function houseDeneith(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'House Deneith',
            'description' => 'Dragonmarked house known for their mercenary services.',
        ]);
    }
}
