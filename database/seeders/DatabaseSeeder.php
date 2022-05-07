<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Pokemon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $pokemons = [
            [
                'name' => 'Pikachu',
                'max_hp' => 240,
                'hp' => 240,
            ],
            [
                'name' => 'Charizard',
                'max_hp' => 260,
                'hp' => 260

            ]
        ];

        $abilities = [
            [
                'pokemon_id' => 1,
                'name' => 'Supercarga',
                'power_points' => 90,
                'max_ability_points' => 15,
                'ability_points' => 15
            ],
            [
                'pokemon_id' => 1,
                'name' => 'Golpe Letal',
                'power_points' => 95,
                'max_ability_points' => 10,
                'ability_points' => 10
            ],
            [
                'pokemon_id' => 1,
                'name' => 'Choque',
                'power_points' => 80,
                'max_ability_points' => 20,
                'ability_points' => 20
            ],
            [
                'pokemon_id' => 1,
                'name' => 'Onda Paralizante',
                'power_points' => 70,
                'max_ability_points' => 20,
                'ability_points' => 20,
            ],
            [
                'pokemon_id' => 2,
                'name' => 'Garra Acero',
                'power_points' => 90,
                'max_ability_points' => 15,
                'ability_points' => 15
            ],
            [
                'pokemon_id' => 2,
                'name' => 'Llamarada',
                'power_points' => 95,
                'max_ability_points' => 10,
                'ability_points' => 10
            ],
            [
                'pokemon_id' => 2,
                'name' => 'Ascuas',
                'power_points' => 80,
                'max_ability_points' => 10,
                'ability_points' => 10
            ],
            [
                'pokemon_id' => 2,
                'name' => 'Voluntad Ferrea',
                'power_points' => 70,
                'max_ability_points' => 20,
                'ability_points' => 20
            ],

        ];

        Pokemon::insert($pokemons);
        Ability::insert($abilities);
    }
}
