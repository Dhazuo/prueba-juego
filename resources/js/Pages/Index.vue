<template>
    <div class="fixed w-full h-full flex justify-center items-center">
        <start-screen v-on:start="startGame" v-if="game_status == 'initializating' && status != 'finished'"></start-screen>
        <start-game  :game_status="game_status" :pokemon="pokemon" :enemy_pokemon="enemy_pokemon" :status="status" v-if="game_status == 'started' && status != 'finished'"></start-game>
        <finished :winner="winner" v-if="status=='finished'"></finished>
    </div>
</template>

<script>
import StartScreen from "./Game/StartScreen";
import StartGame from "./Game/StartGame";
import Finished from "./Game/Finished";

export default {
    components: {Finished, StartGame, StartScreen},
    data() {
        return {
            game_status: 'initializating',
            btn: new Audio('/sounds/button.mp3'),
            pokemon: {
                name: null,
                hp: null,
                abilities: []
            },
            enemy_pokemon: {
                name: null,
                hp: null,
                abilities: []
            },
            status: null,
            winner: null
        }
    },
    mounted() {
        window.addEventListener('click', this.clck);
    },
    methods: {
        clck() {
            if (this.btn.paused) {
                this.btn.play()
            } else {
                this.btn.pause()
                this.btn.currentTime = 0
                this.btn.play()
            }
        },
        startGame() {
            axios.post(this.route('start'), {
                game_status: this.game_status
            })
                .then(response => {
                    console.log(response)
                    if (
                        response.data.status == 'finished'
                        && (response.data.winner == 'user'
                        || response.data.winner == 'bot')
                    ) {
                        this.status = response.data.status
                        this.winner = response.data.winner
                    } else {
                        this.status = response.data.status
                        this.winner = response.data.winner
                        this.game_status = response.data.game_status

                        this.pokemon.name = response.data.pokemon.name
                        this.pokemon.max_hp = response.data.pokemon.max_hp
                        this.pokemon.hp = response.data.pokemon.hp
                        this.pokemon.abilities = response.data.pokemon_abilities

                        this.enemy_pokemon.name = response.data.enemy_pokemon.name
                        this.enemy_pokemon.hp = response.data.enemy_pokemon.hp
                        this.enemy_pokemon.max_hp = response.data.enemy_pokemon.max_hp
                        this.enemy_pokemon.abilities = response.data.enemy_pokemon_abilities
                    }
                })

        }
    }
}
</script>

<style scoped>

</style>
