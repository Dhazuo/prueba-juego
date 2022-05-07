<template>
    <div class="main relative w-full h-full bg-battle bg-cover transform transition duration-500">
        <!--Enemy pokemon-->
        <div v-if="items" class="enemy absolute">
            <div class="relative">
                <div class="relative z-10">
                    <img :class="hide != null ? 'opacity-0' : 'opacity-100'" class="cha transition-all duration-200" src="https://img.pokemondb.net/sprites/ruby-sapphire/normal/charizard.png" alt="Charizard"
                         width="150">
                </div>
            </div>
            <div class="absolute z-0 rounded-full transition duration-200 bg-blue-500"
                 style="left: -8rem;bottom: -10rem;transform: scaleY(.2);width: 24rem;height: 24rem;"></div>
        </div>
        <div v-if="items2"
             class="absolute enemy-stats p-2 left-12 w-60 h-32 rounded-md flex items-center justify-end  bg-amber-50 font-press-start my-shadow-2">
            <div class="space-y-2">
                <div class="flex justify-end">{{ en_pokemon.name }}</div>
                <div class="flex justify-end">
                    <div class="text-red-500 rounded-l-full h-4 w-8 bg-black p-1 flex items-center justify-center content-center">
                        <div class="rounded-l-full text-center flex items-center justify-center" style="font-size: 0.5rem;">HP</div>
                    </div>
                    <div class="w-32 h-4 bg-white rounded-r-full bg-black p-1">
                        <div
                            :style="{
                                width: original_enemy_hp_bar + '%'
                            }"
                            class="h-full rounded-full bg-green-500 transform origin-left transition duration-200"></div>
                    </div>
                </div>
                <div class="flex justify-end">
                    {{ en_pokemon.hp }} / {{ original_enemy_hp }}
                </div>
            </div>
        </div>
        <!--Ally Pokemon-->
        <div v-if="items2"
             class="absolute ally-stats p-2 right-12 w-60 h-32 rounded-md flex items-center justify-end  bg-amber-50 font-press-start my-shadow-2">
            <div class="space-y-2">
                <div class="flex justify-end">{{ my_pokemon.name }}</div>
                <div class="flex justify-end">
                    <div class="text-red-500 rounded-l-full h-4 w-8 bg-black p-1 flex items-center justify-center content-center">
                        <div class="rounded-l-full text-center flex items-center justify-center" style="font-size: 0.5rem;">HP</div>
                    </div>
                    <div class="w-32 h-4 bg-white rounded-r-full bg-black p-1">
                        <div
                            :style="{
                                width: original_my_hp_bar + '%'
                            }"

                            class="w-full h-full rounded-full bg-green-500 transform origin-left transition duration-200"></div>
                    </div>
                </div>
                <div class="flex justify-end">
                    {{ my_pokemon.hp }} / {{ original_user_hp }}
                </div>
            </div>
        </div>
    </div>
    <div v-if="items" class="ally absolute">
        <div class="relative">
            <div class="relative z-10">
                <img :class="hide2 != null ? 'opacity-0' : 'opacity-100'" class="pik" src="https://img.pokemondb.net/sprites/emerald/back-normal/pikachu.png" alt="Pikachu" width="200"
                     height="200">
            </div>
            <div class="absolute z-0 rounded-full transition duration-200 bg-blue-500"
                 style="left: -6rem;bottom: -10rem;transform: scaleY(.2);width: 24rem;height: 24rem;"></div>
        </div>
    </div>
    <button v-if="action == 'combat' && phrase == ''" @click="action = 'nothing'"
            class="border bg-white border-gray-200 btn font-press-start rounded-md my-shadow-2 absolute px-4 py-2">
        REGRESAR
    </button>
    <div
        class="p-1 w-full menu absolute bottom-0 border border-gray-600 bg-gradient-to-r from-blue-100 to-pink-100  font-press-start ">
            <div v-if="items2" class="grid grid-cols-4 gap-4 p-1">
                <div class="col-span-3  flex items-center justify-center content-center">
                    <div v-if="phrase == ''" class=" grid  grid-cols-2  gap-10 w-full border border-gray-600 bg-white p-2">
                        <button :disabled="turn == 'enemy_turn'" v-if="action == 'nothing'" @click="myAction($event)" v-for="(phrase, index) in phrases"
                                :id="index"
                                class="relative active:top-2 rounded-md my-shadow-2 border border-gray-200 text-black p-2 text-base text-center flex items-center justify-center">
                            {{ phrase }}
                        </button>
                        <button  v-else v-for="ability in my_pokemon.abilities" :disabled="turn == 'enemy_turn'" @mouseover="showDescription(ability)"
                                @mouseleave="hideDescription" @click="attack(ability)"
                                class="relative active:top-2 rounded-md my-shadow-2 border border-gray-200 text-black p-2 text-base text-center flex items-center justify-center">
                            {{ ability.name }}
                        </button>
                    </div>
                    <div v-else class="bg-white border border-black w-full h-full">
                        <div :class="phrase == 'Has ganado el combate.' || phrase == 'Has perdido el combate.' ? 'block' : 'flex '" class="w-full h-full justify-center items-center content-center">
                            <div :class="phrase == 'Has ganado el combate.' || phrase == 'Has perdido el combate.' ? 'flex justify-center' : ''">
                                {{ phrase }}
                            </div>
                            <div v-if="phrase == 'Has ganado el combate.' || phrase == 'Has perdido el combate.'" class="my-2 space-y-2">
                                <div class="text-center">¿Deseas reintentarlo?</div>
                                <div class="flex space-x-6 items-center justify-center">
                                    <button @click="yes" class="my-shadow-3 text-white rounded-md relative active:top-2 bg-green-500  p-2">Sí</button>
                                    <inertia-link :href="route('index')" class="my-shadow-4 text-white rounded-md relative active:top-2 bg-red-500  p-2">No</inertia-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="action == 'nothing' && phrase == ''"
                     class="border border-gray-600 flex items-center justify-center content-center bg-white p-2">
                    ¿Qué quieres hacer?
                </div>
                <div v-else class="border border-gray-600 flex justify-center bg-white p-2 text-sm">
                    <div
                        v-if="(currentDescription.name && currentDescription.ability_points>=0 && currentDescription.power_points)"
                        class="space-y-4">
                        <div class="uppercase underline text-lg">
                            {{ currentDescription.name }}
                        </div>
                        <div>
                            DISPONIBLES: {{ currentDescription.ability_points }} / {{ currentDescription.max_ability_points }}
                        </div>
                        <div class="text-red-500">
                            PODER: {{ currentDescription.power_points }}
                        </div>
                    </div>
                </div>
            </div>

    </div>
</template>
<script>
export default {
    name: "StartGame",
    props: ['game_status', 'pokemon', 'enemy_pokemon', 'status'],
    data() {
        return {
            hide1: null,
            hide2: null,
            victory: new Audio('/sounds/victory.mp3'),
            damage: new Audio('/sounds/damage.mp3'),
            skills_sound_1: {
                first: new Audio('/sounds/pikachu/first.mp3'),
                second: new Audio('/sounds/pikachu/second.mp3'),
            },
            skills_sound_2: {
                first: new Audio('/sounds/charizard/first.mp3'),
                second: new Audio('/sounds/charizard/second.mp3'),
            },
            items: false,
            items2: false,
            turn: null,
            phrase: '',
            responseA: null,
            responseB: null,
            user_interval: null,
            enemy_interval: null,
            my_pokemon: this.pokemon,
            en_pokemon: this.enemy_pokemon,
            original_user_hp: this.pokemon.max_hp,
            objective_my_hp_bar: null,
            original_my_hp_bar: 100,
            my_hp_bar_interval: null,
            objective_enemy_hp_bar: null,
            original_enemy_hp_bar: 100,
            enemy_hp_bar_interval: null,
            original_enemy_hp: this.enemy_pokemon.max_hp,
            user_new_hp: null,
            enemy_new_hp: null,
            bot_skill: null,
            music: new Audio('/sounds/battle.mp3'),
            phrases: [
                'COMBATE', 'INVENTARIO', 'NADA', 'PASAR TURNO'
            ],
            action: 'nothing',
            currentDescription: {
                name: null,
                max_ability_points: null,
                ability_points: null,
                power_points: null
            }
        }
    },
    mounted() {
        this.music.play()
        this.music.volume = 0.2
        setTimeout(() => {
            this.items = true
        }, 600)
        setTimeout(() => {
            document.querySelector('.ally').classList.add('ally-appear')
            document.querySelector('.enemy').classList.add('enemy-appear')
        }, 610)
        setTimeout(() => {
            this.items2 = true
        }, 3600)
        setTimeout(() => {
            let val1 = Math.floor((this.en_pokemon.hp*100) / this.original_enemy_hp);
            let val2 = Math.floor((this.my_pokemon.hp*100) / this.original_user_hp);
            this.original_enemy_hp_bar = 0
            this.original_enemy_hp_bar = val1
            this.original_my_hp_bar = 0
            this.original_my_hp_bar = val2
        }, 3700)

    },
    beforeUnmount() {
        if (!this.victory.paused) {
            this.victory.currentTime = 0
            this.victory.pause()
        }
    },
    methods: {
        yes(){
            axios.post(this.route('restart'), {
                res: 'yes',
            })
                .then(response => {
                    window.location = this.route('index')
                })
        },
        myAction(event) {
            if (event.currentTarget.id == 0) {
                this.action = 'combat'
            }
            else if (event.currentTarget.id == 3) {
                this.phrase = `${this.my_pokemon.name} pasa turno`
                axios.post(this.route('attack'), {
                    pass: 'pass'
                })
                    .then(response => {
                        this.responseA = response
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.phrase = ''
                        }, 3500)
                    })
                    .then(() => {
                        let choosed = this.en_pokemon.abilities.find(skill => {
                            return skill.id == this.responseA.data.bot_choose
                        })
                        this.bot_skill = choosed
                        this.botAction()
                    })
            }
        },
        attack(ability) {
            if (ability.ability_points == 0) {
                this.phrase = 'Ya no puedes usar este ataque.'
                setTimeout(() => {
                    this.phrase = ''
                }, 2000)
            } else {
                let phar = `Pikachu usa ${ability.name}`
                this.phrase = phar

                axios.post(this.route('attack'), {ability})
                    .then(response => {
                        this.responseA = response
                    })
                    .then(() => {
                        if (ability.name == 'Golpe Letal'){
                            this.skills_sound_1.second.play()
                        } else {
                            this.skills_sound_1.first.play()
                        }
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.damage.play()
                            document.querySelector('.cha').classList.add('blink')
                            setTimeout(() => {
                                document.querySelector('.cha').classList.remove('blink')
                            }, 2200)
                        }, 1500)
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.turn = this.responseA.data.turn
                            this.enemy_new_hp = this.responseA.data.new_enemy_hp
                            ability.ability_points = this.responseA.data.decrement
                            let choosed = this.en_pokemon.abilities.find(skill => {
                                return skill.id == this.responseA.data.bot_choose
                            })
                            this.bot_skill = choosed
                            this.enemy_interval = setInterval(() => {
                                this.en_pokemon.hp--
                            }, 20)
                            this.objective_enemy_hp_bar = Math.floor(((this.enemy_new_hp * 100) / this.original_enemy_hp))
                            this.enemy_hp_bar_interval = setInterval(() => {
                                this.original_enemy_hp_bar--
                            }, 40)
                        }, 2500)
                    })
                    .then(() => {
                        if (this.responseA.data.status == 'user_win') {
                            this.height = 0
                            if (!this.music.paused) {
                                setTimeout(() => {
                                    this.music.pause()
                                    this.victory.play()
                                }, 4500)
                            }
                            setTimeout(() => {
                                this.hide = 'yes'
                                this.phrase = 'Has ganado el combate.'
                            }, 4500)
                        } else {
                            setTimeout(() => {
                                this.phrase = ''
                                this.action = 'nothing'
                            }, 4500)
                        }
                    })
                    .then(() => {
                        if (this.responseA.data.status == 'user_win') {
                            return
                        } else {
                            this.botAction();
                        }
                    })

            }
        },
        botAction(){
            setTimeout(() => {
                let phar = `Charizard usa ${this.bot_skill.name}`
                this.phrase = phar

                axios.post(this.route('attack'), {
                    bot_skill: this.bot_skill})
                    .then(response => {
                        this.responseB = response
                    })
                    .then(() => {
                        if (this.bot_skill.name == 'Garra Acero' || this.bot_skill.name == 'Voluntad Ferrea'){
                            this.skills_sound_2.second.play()
                        } else {
                            this.skills_sound_2.first.play()
                        }
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.damage.play()
                            document.querySelector('.pik').classList.add('blink')
                            setTimeout(() => {
                                document.querySelector('.pik').classList.remove('blink')
                            }, 2200)
                        }, 1500)
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.turn = this.responseB.data.turn
                            this.user_new_hp = this.responseB.data.my_new_hp
                            this.user_interval = setInterval(() => {
                                this.my_pokemon.hp--
                            }, 20)
                            this.objective_my_hp_bar = Math.floor(((this.user_new_hp * 100) / this.original_user_hp))
                            this.my_hp_bar_interval = setInterval(() => {
                                this.original_my_hp_bar--
                            }, 40)
                        }, 2500)
                    })
                    .then(() => {
                        setTimeout(() => {
                            if (this.responseB.data.status == 'bot_win') {
                                this.hide2 = 'yes'
                                this.phrase = 'Has perdido el combate.'
                            } else {
                                this.phrase = ''
                                this.action = 'nothing'
                            }
                        }, 4500)
                    })
            }, 5000)
        },
        showDescription(ability) {
            this.currentDescription.name = ability.name
            this.currentDescription.ability_points = ability.ability_points
            this.currentDescription.max_ability_points = ability.max_ability_points
            this.currentDescription.power_points = ability.power_points
        },
        hideDescription() {
            this.currentDescription.name = null
            this.currentDescription.ability_points = null
            this.currentDescription.max_ability_points = null
            this.currentDescription.power_points = null
        }
    },
    watch: {
        "en_pokemon.hp": function() {
            if (this.en_pokemon.hp == this.enemy_new_hp) {
                clearInterval(this.enemy_interval)
                this.en_pokemon.hp = this.enemy_new_hp
            }
        },
        "my_pokemon.hp": function() {
            if (this.my_pokemon.hp == this.user_new_hp) {
                clearInterval(this.user_interval)
                this.my_pokemon.hp = this.user_new_hp
            }
        },
        "original_enemy_hp_bar": function () {
            if (this.original_enemy_hp_bar == this.objective_enemy_hp_bar) {
                clearInterval(this.enemy_hp_bar_interval)
                this.original_enemy_hp_bar = this.objective_enemy_hp_bar
            }
        },
        "original_my_hp_bar": function () {
            if (this.original_my_hp_bar == this.objective_my_hp_bar) {
                clearInterval(this.my_hp_bar_interval)
                this.original_my_hp_bar = this.objective_my_hp_bar
            }
        }
    }
}
</script>

<style scoped>
.menu {
    height: 10rem;
}

.btn {
    left: 0;
    bottom: 12rem;
}

.ally {
    transition: all 3s ease-in;
    right: -15rem;
    bottom: 8.6rem;
}
.blink {
    animation: blink;
    animation-duration: .5s;
}

@keyframes blink {
    0% {
        opacity: 1;
    }
    25%{
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    75%{
        opacity: 0;
    }
    100% {
        opacity: 1;
    }

}
.ally-appear {
    right: 60rem;
    transition: all 3s ease-in;
}
.ally-stats {
    bottom: 12rem;
}
.enemy-stats {
    top: 2rem;
}
.enemy {
    transition: all 3s ease-in;
    left: -15rem;
    top: 2rem;
}

.enemy-appear {
    left: 60rem;
    transition: all 3s ease-in;
}


.my-shadow {
    -webkit-box-shadow: inset -12px -12px 0px 0px #b4b4b4;
    box-shadow: inset -12px -12px 0px 0px #b4b4b4;
}

.my-shadow-2 {
    -webkit-box-shadow: 5px 5px 0px 1px #b4b4b4;
    box-shadow: 5px 5px 0px 1px #b4b4b4;
}
.my-shadow-3 {
    -webkit-box-shadow: 5px 5px 0px 1px #166534;
    box-shadow: 5px 5px 0px 1px #166534;
}

.my-shadow-4 {
    -webkit-box-shadow: 5px 5px 0px 1px #991b1b;
    box-shadow: 5px 5px 0px 1px #991b1b;
}
</style>
