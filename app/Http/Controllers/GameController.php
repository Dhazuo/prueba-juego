<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use App\Models\Combat;
use App\Models\Pokemon;
use App\Logic\Bot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    private string $game_status;

    public function start(Request $request)
    {
        $petition = $request->get('game_status');

        if ($petition == 'initializating') {
            $this->game_status = 'started';

            $user_pokemon = Pokemon::where('name', 'Pikachu')->first();
            $user_pokemon_abilities = $user_pokemon->abilities;

            $enemy_pokemon = Pokemon::where('name', 'Charizard')->first();
            $enemy_pokemon_abilities = $enemy_pokemon->abilities;

            $combat_exists = Combat::where('ip', $request->ip())->first();

            if ($combat_exists) {
                if ($combat_exists->status == 'finished') {
                    return response()->json([
                        'status' => $combat_exists->status,
                        'winner' => $combat_exists->winner,
                    ], 200);
                } else {
                    return response()->json([
                        'game_status' => 'started',
                        'pokemon' => $user_pokemon,
                        'pokemon_abilities' => $user_pokemon_abilities,
                        'enemy_pokemon' => $enemy_pokemon,
                        'enemy_pokemon_abilities' => $enemy_pokemon_abilities,
                    ], 200);
                }
            } else {
                $ip = $request->ip();
                $this->startCombat($user_pokemon, $enemy_pokemon, $ip);

                return response()->json([
                    'game_status' => 'started',
                    'pokemon' => $user_pokemon,
                    'pokemon_abilities' => $user_pokemon_abilities,
                    'enemy_pokemon' => $enemy_pokemon,
                    'enemy_pokemon_abilities' => $enemy_pokemon_abilities,
                ], 200);
            }

        }
    }

    public function startCombat($user_pokemon, $enemy_pokemon, $ip)
    {
        return Combat::create([
            'ip' => $ip,
            'user_pokemon_id' => $user_pokemon->id,
            'enemy_pokemon_id' => $enemy_pokemon->id,
            'status' => 'in_progress'
        ]);
    }

    public function attack(Request $request)
    {
        $combat = Combat::where('ip', $request->ip())->first();

        if ($combat->turn == 'user_turn') {

            if ($request->get('pass')) {
                $enemy = Pokemon::where('id', $combat->enemy_pokemon_id)->first();

                $skills = [];
                $bot_pokemon = Pokemon::where('name', 'Charizard')->first();
                $bot_pokemon_abilities = $bot_pokemon->abilities;

                foreach ($bot_pokemon_abilities as $index => $bot_ability) {
                    $skills[$index] = $bot_ability;
                }

                $bot = new Bot;
                $bot_choose = $bot->ability($skills)->id;

                $combat->update([
                    'turn' => 'enemy_turn'
                ]);

                return response()->json([
                    'new_enemy_hp' => $enemy->hp,
                    'turn' => $combat->turn,
                    'bot_choose' => $bot_choose,
                    'status' => null
                ]);
            } else {
                $attack = $request->get('ability');

                $total_damage = (int)($attack['power_points'] / 2);

                $enemy = Pokemon::where('id', $combat->enemy_pokemon_id)->first();

                $new_enemy_hp = $enemy->hp - $total_damage;

                if ($new_enemy_hp <= 0) {

                    $enemy->update([
                        'hp' => 0
                    ]);

                    $combat->update([
                        'status' => 'finished',
                        'winner' => 'user'
                    ]);

                    return response()->json([
                        'new_enemy_hp' => 0,
                        'decrement' => Ability::where('name', $attack['name'])->first()->ability_points,
                        'status' => 'user_win'
                    ]);
                } else {
                    $enemy->update([
                        'hp' => $new_enemy_hp
                    ]);

                    DB::table('abilities')->where('name', $attack['name'])->decrement('ability_points', 1);
                    //bot logic
                    $skills = [];
                    $bot_pokemon = Pokemon::where('name', 'Charizard')->first();
                    $bot_pokemon_abilities = $bot_pokemon->abilities;

                    foreach ($bot_pokemon_abilities as $index => $bot_ability) {
                        $skills[$index] = $bot_ability;
                    }

                    $bot = new Bot;
                    $bot_choose = $bot->ability($skills)->id;

                    $combat->update([
                        'turn' => 'enemy_turn'
                    ]);
                    return response()->json([
                        'new_enemy_hp' => $new_enemy_hp,
                        'turn' => $combat->turn,
                        'bot_choose' => $bot_choose,
                        'decrement' => Ability::where('name', $attack['name'])->first()->ability_points,
                        'status' => null
                    ]);
                }
            }

        } else if ($combat->turn == 'enemy_turn') {
            $choose = $request->get('bot_skill');
            $total_damage = (int)($choose['power_points'] / 2);

            $enemy = Pokemon::where('id', $combat->user_pokemon_id)->first();

            $new_enemy_hp = $enemy->hp - $total_damage;

            if ($new_enemy_hp <= 0) {

                $enemy->update([
                    'hp' => 0
                ]);

                $combat->update([
                    'status' => 'finished',
                    'winner' => 'bot'
                ]);

                return response()->json([
                    'my_new_hp' => 0,
                    'status' => 'bot_win',
                    'decrement' => Ability::where('name', $choose['name'])->first()->ability_points
                ]);
            } else {
                $enemy->update([
                    'hp' => $new_enemy_hp
                ]);

                $combat->update([
                    'turn' => 'user_turn'
                ]);

                DB::table('abilities')->where('name', $choose['name'])->decrement('ability_points', 1);


                return response()->json([
                    'my_new_hp' => $new_enemy_hp,
                    'turn' => $combat->turn,
                    'decrement' => Ability::where('name', $choose['name'])->first()->ability_points,
                    'status' => null
                ]);
            }
        }

    }

    public function restart(Request $request)
    {
        $res = $request->get('res');
        if ($res == 'yes') {
            $combat = Combat::where('ip', $request->ip())->first();
            $user_pokemon = Pokemon::where('id', $combat->user_pokemon_id)->first();
            $user_pokemon_abilities = $user_pokemon->abilities;

            $enemy_pokemon = Pokemon::where('id', $combat->enemy_pokemon_id)->first();
            $enemy_pokemon_abilities = $enemy_pokemon->abilities;


            $combat->update([
                'turn' => 'user_turn',
                'status' => 'in_progress',
                'winner' => null,
            ]);

            $user_pokemon->update([
               'hp' => $user_pokemon->max_hp
            ]);
            $enemy_pokemon->update([
               'hp' => $enemy_pokemon->max_hp
            ]);

            foreach ($user_pokemon_abilities as $ability) {
                $ability->update([
                   'ability_points' => $ability->max_ability_points
                ]);
            }
            foreach ($enemy_pokemon_abilities as $ability) {
                $ability->update([
                    'ability_points' => $ability->max_ability_points
                ]);
            }
        }
    }
}
