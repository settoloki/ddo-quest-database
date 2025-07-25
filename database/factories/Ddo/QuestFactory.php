<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\Quest;
use App\Models\Ddo\Duration;
use App\Models\Ddo\Patron;
use App\Models\Ddo\AdventurePack;
use App\Models\Ddo\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\Quest>
 */
class QuestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questNames = [
            'Stopping the Sahuagin',
            'The Collaborator',
            'Storehouse\'s Secret',
            'Redemption',
            'The Depths of Despair',
            'The Captains',
            'The Tide Turns',
            'Garrison\'s Missing Pack',
            'Sacrifices',
            'The Crypt of Gerard Dryden',
            'The Tomb of the Burning Heart',
            'The Scourge of the Seas',
            'Slavers of the Shrieking Mines',
            'The Sunken Sewer',
            'A Small Problem',
            'Proof is in the Poison',
            'The Friar\'s Niece',
            'Tangleroot Gorge',
            'Three Barrel Cove',
            'The Pit',
        ];

        $name = $this->faker->randomElement($questNames);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'heroic_level' => $this->faker->numberBetween(1, 30),
            'epic_level' => $this->faker->optional(0.3)->numberBetween(20, 34),
            'legendary_level' => $this->faker->optional(0.1)->numberBetween(30, 34),
            'duration_id' => Duration::factory(),
            'patron_id' => Patron::factory(),
            'adventure_pack_id' => AdventurePack::factory(),
            'location_id' => Location::factory(),
            'base_favor' => $this->faker->randomElement([0, 6, 9, 12]),
            'extreme_challenge' => $this->faker->boolean(10), // 10% chance
            'overview' => $this->faker->optional()->paragraph(),
            'objectives' => $this->faker->optional()->text(),
            'tips' => $this->faker->optional()->text(),
            'wiki_url' => $this->faker->optional()->url(),
        ];
    }

    /**
     * Create a low level quest (1-5).
     */
    public function lowLevel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'heroic_level' => $this->faker->numberBetween(1, 5),
            'epic_level' => null,
            'legendary_level' => null,
        ]);
    }

    /**
     * Create a mid level quest (6-15).
     */
    public function midLevel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'heroic_level' => $this->faker->numberBetween(6, 15),
        ]);
    }

    /**
     * Create a high level quest (16-30).
     */
    public function highLevel(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'heroic_level' => $this->faker->numberBetween(16, 30),
        ]);
    }

    /**
     * Create an epic quest.
     */
    public function epic(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'epic_level' => $this->faker->numberBetween(20, 34),
        ]);
    }

    /**
     * Create a legendary quest.
     */
    public function legendary(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'legendary_level' => $this->faker->numberBetween(30, 34),
        ]);
    }

    /**
     * Create a quest with extreme challenge.
     */
    public function extremeChallenge(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'extreme_challenge' => true,
        ]);
    }

    /**
     * Create a free to play quest.
     */
    public function freeToPlay(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'adventure_pack_id' => AdventurePack::factory()->freeToPlay(),
        ]);
    }

    /**
     * Create a premium quest.
     */
    public function premium(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'adventure_pack_id' => AdventurePack::factory()->premium(),
        ]);
    }
}
