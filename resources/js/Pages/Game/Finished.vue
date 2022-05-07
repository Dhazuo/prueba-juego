<template>
    <div class="main fixed w-full h-full flex bg-white transform transition duration-200 origin-center scale-0 justify-center items-center font-bold font-press-start">
        <div class="space-y-4 text-center">
            <div>Parece que tu combate ha terminado.</div>
            <div>El ganador ... {{ winner == 'user' ? '¡Fuiste tú! Felicidades' : 'Fue la máquina...' }}</div>
            <div>¿Deseas reintentarlo?</div>
            <div class="flex space-x-6 items-center justify-center">
                <button @click="yes" class="my-shadow-2 text-white rounded-md relative active:top-2 bg-green-500  p-2">Sí</button>
                <inertia-link :href="route('index')" class="my-shadow-3 text-white rounded-md relative active:top-2 bg-red-500  p-2">No</inertia-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Finished",
    props: ['winner', 'user_pokemon', 'enemy_pokemon'],
    data(){
        return{
            music: new Audio('/sounds/victory.mp3')
        }
    },
    mounted() {
        setTimeout(() => {
            document.querySelector('.main').classList.replace('scale-0', 'scale-100')
        }, 10)
        if (this.winner == 'user') {
            this.music.play()
        }
    },
    beforeUnmount() {
      if (!this.music.paused) {
          this.music.currentTime = 0
          this.music.pause()
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
        }
    }
}
</script>

<style scoped>
.my-shadow-2 {
    -webkit-box-shadow: 5px 5px 0px 1px #166534;
    box-shadow: 5px 5px 0px 1px #166534;
}
 .my-shadow-3 {
    -webkit-box-shadow: 5px 5px 0px 1px #991b1b;
    box-shadow: 5px 5px 0px 1px #991b1b;
}
</style>
