<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\AdventurePack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\AdventurePack>
 */
class AdventurePackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdventurePack::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packs = [
            ['name' => 'Free to Play', 'purchase_type' => 'Free to Play'],
            ['name' => 'The Catacombs', 'purchase_type' => 'Premium'],
            ['name' => 'Waterworks', 'purchase_type' => 'Premium'],
            ['name' => 'Three Barrel Cove', 'purchase_type' => 'Premium'],
            ['name' => 'The Desert', 'purchase_type' => 'Premium'],
            ['name' => 'Gianthold', 'purchase_type' => 'Premium'],
            ['name' => 'The Vale of Twilight', 'purchase_type' => 'Premium'],
            ['name' => 'The Twelve', 'purchase_type' => 'Premium'],
            ['name' => 'The Necropolis', 'purchase_type' => 'Premium'],
            ['name' => 'Attack on Stormreach', 'purchase_type' => 'Premium'],
            ['name' => 'Against the Cult of the Dragon Below', 'purchase_type' => 'Premium'],
            ['name' => 'Keep on the Borderlands', 'purchase_type' => 'Premium'],
        ];

        $pack = $this->faker->randomElement($packs);

        return [
            'name' => $pack['name'],
            'purchase_type' => $pack['purchase_type'],
            'release_date' => $this->faker->dateTimeBetween('2006-01-01', '2024-12-31'),
        ];
    }

    /**
     * Create a free to play adventure pack.
     */
    public function freeToPlay(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'purchase_type' => 'Free to Play',
        ]);
    }

    /**
     * Create a premium adventure pack.
     */
    public function premium(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'purchase_type' => 'Premium',
        ]);
    }

    /**
     * Create a VIP adventure pack.
     */
    public function vip(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'purchase_type' => 'VIP',
        ]);
    }

    /**
     * Create an expansion adventure pack.
     */
    public function expansion(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'purchase_type' => 'Expansion',
        ]);
    }
}
