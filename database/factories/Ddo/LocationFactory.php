<?php

namespace Database\Factories\Ddo;

use App\Models\Ddo\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddo\Location>
 */
class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            ['name' => 'Stormreach', 'area_type' => 'City'],
            ['name' => 'Korthos Village', 'area_type' => 'Village'],
            ['name' => 'Korthos Island', 'area_type' => 'Island'],
            ['name' => 'The Harbor', 'area_type' => 'City'],
            ['name' => 'The Marketplace', 'area_type' => 'City'],
            ['name' => 'House Deneith', 'area_type' => 'City'],
            ['name' => 'House Phiarlan', 'area_type' => 'City'],
            ['name' => 'Three Barrel Cove', 'area_type' => 'Wilderness'],
            ['name' => 'The Desert', 'area_type' => 'Wilderness'],
            ['name' => 'Gianthold', 'area_type' => 'Wilderness'],
            ['name' => 'The Vale of Twilight', 'area_type' => 'Wilderness'],
            ['name' => 'The Catacombs', 'area_type' => 'Dungeon'],
            ['name' => 'Waterworks', 'area_type' => 'Dungeon'],
        ];

        $location = $this->faker->randomElement($locations);

        return [
            'name' => $location['name'],
            'area_type' => $location['area_type'],
            'parent_location_id' => null, // Will be set by withParent() state
        ];
    }

    /**
     * Create a location with a parent location.
     */
    public function withParent(?Location $parent = null): Factory
    {
        return $this->state(function (array $attributes) use ($parent) {
            $parentLocation = $parent ?? Location::factory()->create();
            
            return [
                'parent_location_id' => $parentLocation->id,
            ];
        });
    }

    /**
     * Create a city location.
     */
    public function city(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'area_type' => 'City',
        ]);
    }

    /**
     * Create a village location.
     */
    public function village(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'area_type' => 'Village',
        ]);
    }

    /**
     * Create a wilderness location.
     */
    public function wilderness(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'area_type' => 'Wilderness',
        ]);
    }

    /**
     * Create a dungeon location.
     */
    public function dungeon(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'area_type' => 'Dungeon',
        ]);
    }

    /**
     * Create an island location.
     */
    public function island(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'area_type' => 'Island',
        ]);
    }
}
