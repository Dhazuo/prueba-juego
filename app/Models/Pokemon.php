<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons';

    protected $fillable = [
        'name',
        'max_hp',
        'hp',
    ];

    use HasFactory;

    public function abilities(){
        return $this->hasMany(Ability::class);
    }
}
