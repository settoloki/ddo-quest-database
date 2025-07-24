<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ddo\Patron;

class DdoPatronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patrons = [
            [
                'name' => 'The Coin Lords',
                'description' => 'The ruling merchant class of Stormreach, controlling trade and commerce throughout the harbor city.',
            ],
            [
                'name' => 'House Deneith',
                'description' => 'A dragonmarked house known for their mercenary services and the Mark of Sentinel.',
            ],
            [
                'name' => 'The Harpers',
                'description' => 'A secret organization of spellcasters and spies who advocate equality and fight against the abuse of power.',
            ],
            [
                'name' => 'House Phiarlan',
                'description' => 'The dragonmarked house of entertainment and espionage, bearing the Mark of Shadow.',
            ],
            [
                'name' => 'The Twelve',
                'description' => 'An organization of wizards representing the twelve dragonmarked houses, dedicated to magical research.',
            ],
            [
                'name' => 'House Kundarak',
                'description' => 'The dragonmarked house of warding and security, known for their banking and protection services.',
            ],
            [
                'name' => 'The Silver Flame',
                'description' => 'A lawful good church dedicated to protecting the world from supernatural evil and corruption.',
            ],
            [
                'name' => 'The Purple Dragon Knights',
                'description' => 'Elite knights of Cormyr, dedicated to protecting the realm and upholding justice.',
            ],
            [
                'name' => 'The Gatekeepers',
                'description' => 'Ancient druids who guard against aberrations and protect the natural balance.',
            ],
            [
                'name' => 'House Jorasco',
                'description' => 'The dragonmarked house of healing, bearing the Mark of Healing and providing medical services.',
            ],
            [
                'name' => 'The Lords of Dust',
                'description' => 'Ancient fiends seeking to free the Overlords and bring about the end times.',
            ],
            [
                'name' => 'The Inspired',
                'description' => 'Quori-possessed rulers of Riedra, seeking to expand their dominion over Khorvaire.',
            ],
        ];

        foreach ($patrons as $patron) {
            Patron::firstOrCreate(
                ['name' => $patron['name']],
                $patron
            );
        }
    }
}
