<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combats', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->unsignedBigInteger('user_pokemon_id');
            $table->unsignedBigInteger('enemy_pokemon_id');

            $table->enum('turn', ['user_turn', 'enemy_turn'])->default('user_turn');
            $table->enum('status', ['in_progress', 'finished'])->default('in_progress');
            $table->string('winner')->nullable();

            $table->foreign('user_pokemon_id')->references('id')->on('pokemons');
            $table->foreign('enemy_pokemon_id')->references('id')->on('pokemons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combats');
    }
};
