<script setup>
import {Head, Link} from '@inertiajs/vue3';
import Game from "@/Game/Game.vue";
import {ArrowLeftIcon} from "@heroicons/vue/20/solid";

const props = defineProps({
    gameBoard: {
        type: Object,
        required: true,
    },
    highScores: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <Head title="Game"/>


    <div
        class="w-full relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div class="sm:fixed sm:top-0 sm:left-0 p-6 text-left text-gray-400">
        <Link :href="route('game.index')" >
            <ArrowLeftIcon class="h-6 w-6 inline" /> Return to level select
        </Link>
        </div>

        <div class="w-1/2 mx-auto flex">
            <div class="w-1/2">
                <Game :board-config="gameBoard.data" :level-name="gameBoard.name" :high-score="highScores[0]?.score || 0" />
            </div>
            <div class="w-1/4 text-gray-100">
                <h3>Highscores</h3>
                <ul>
                    <li v-for="score in highScores" class="flex">
                        <span class="username grow">{{ score.username }}</span>
                        <span class="score font-black">{{ score.score }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
