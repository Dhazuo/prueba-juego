<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'pokemon_id',
        'name',
        'power_points',
        'max_ability_points',
        'ability_points',
    ];


    public function pokemon(){
        return $this->belongsTo(Pokemon::class);
    }
}
