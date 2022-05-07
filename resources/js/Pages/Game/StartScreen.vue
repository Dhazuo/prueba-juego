<template>
    <div class="main w-full transform origin-center transition duration-500 h-full bg-cover bg-main flex justify-center items-center relative">
        <button @click="aud.paused ? aud.play() : aud.pause()" class="absolute top-12 right-12 bg-orange-50 shadow-md p-2 rounded-full h-10 w-10 flex items-center justify-center content-center">
            <img src="/images/music_img.png" alt="">
        </button>
        <button @click="start" class="button relative active:top-1 rounded-md my-shadow px-4 py-2 text-white font-bold font-press-start">Start</button>
    </div>
</template>

<script>
export default {
    name: "StartScreen",
    data(){
        return {
            aud: new Audio('/sounds/main.mp3')
        }
    },

    mounted() {

    },
    beforeUnmount() {
            if (this.aud.paused) {

            } else {
                this.aud.pause()
            }
        },
    methods: {
        start(){
            document.querySelector('.main').classList.add('scale-0')
            setTimeout(() => this.$emit('start'), 500)
        }
    }
}
</script>

<style scoped>
.button {
    background-color: #0BAF51;
}
    .my-shadow {
        -webkit-box-shadow: inset -4px -4px 0px 0px #06642E;
        box-shadow: inset -4px -4px 0px 0px #06642E;
    }
</style>
