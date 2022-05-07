<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combat extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'user_pokemon_id',
        'enemy_pokemon_id',
        'turn',
        'status',
        'winner'
    ];
}
