<script>
import {Head, Link} from '@inertiajs/vue3';
import LevelPreview from "@/Pages/LevelPreview.vue";

export default {
    components: {
        LevelPreview,
        Head,
        Link,
    },
    props: {
        // canLogin: {
        //     type: Boolean,
        // },
        // canRegister: {
        //     type: Boolean,
        // },
        gameBoards: {
            type: Object,
            required: true,
        },
        highScores: {
            type: Object,
            required: true,
        },

    },
    data() {
        return {
            selection: 'default',
            scoresList: this.highScores[this.selection],
        }
    },
    methods:  {
        selectLevel(name) {
            this.selection = name
            this.scoresList = this.highScores[name]
        }
    },
    mounted() {
        this.selection = Object.keys(this.gameBoards)[0]
        this.selectLevel(this.selection)
    }
}

</script>

<template>
    <Head title="Level Select"/>

    <div class="w-full relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <!-- nav -->
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                :href="route('game.editor')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Level editor
            </Link>
        </div>

        <div class="w-1/2 flex flex-col">
            <h1 class="text-white text-center block">Level selection</h1>

            <div class="w-full mx-auto flex flex-row justify-between mb-4">
                <button
                    v-for="board,boardName in gameBoards"
                    class="p-2 pb-4" :class="{'border-b-2': selection == board.name}"
                    @click="selectLevel(board.name)"
                >
                    <level-preview :matrix="board.data.matrix" :meta="board.data.meta" :name="board.name" />
                    <a :href="route('game.play', {level: boardName})" class="rounded-full bg-blue-400 text-white p-2 mt-2">
                        Play
                    </a>
                </button>

            </div>
            <div class="w-1/2 text-gray-100 mx-auto">
                <h2 class="font-bold text-center">Highscores</h2>
                <ul>
                    <li v-for="score in scoresList" class="flex">
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
