<script>

import {Link, Head, useForm} from "@inertiajs/vue3"
import {directions, collisions, cellTypesInverse} from "@/Game/enums";
import {loadLevel} from "@/Game/LevelLoader";

export default {
    components: {
        Link,
        Head,
    },
    props: {
        boardConfig: {
            type: Object,
            required: true,
        },
        mayDelete: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            directions: {}, // fixme
            collisions: {}, // fixme
            name: '',
            matrix: [],
            meta: {
                snekDirection: directions.LEFT,
            },
            brush: collisions.WALL,
            allowance: {
                wall: 25*25,
                snek: 2,
            }
        }
    },
    methods: {
        selectBrush(type) {
            this.brush = type
        },
        drawCell(x,y) {
            let curr = this.matrix[y][x].class;
            if (curr.indexOf(this.brush) !== -1) {
                this.matrix[y][x].class = collisions.VOID
                this.allowance[this.brush]++
            } else if (this.allowance[this.brush] > 0) {
                this.matrix[y][x].class = this.brush
                this.allowance[this.brush]--
            }
        },
        saveLevel() {
            let cells = [];
            this.matrix.forEach((row, y) => {
                row.forEach((cell, x) => {
                    if (cells[y] === undefined) {
                        cells[y] = [];
                    }
                    cells[y][x] = cellTypesInverse[cell.class].cell
                });
            });
            let form = useForm({matrix: cells, meta: this.meta, levelname: this.name})
            form.post(route('game.editor.save'), {
                onFinish: () => {
                    this.name = '';
                    this.resetLevelEditor()
                }
            })
        },
        resetLevelEditor() {
            let gameBoard = loadLevel('', this.boardConfig.data.matrix, this.boardConfig.data.meta, true)
            this.name = this.boardConfig.name !== 'default' ? this.boardConfig.name : '';
            this.matrix   = gameBoard.matrix
            this.meta = {snekDirection: gameBoard.snek.direction || directions.LEFT}
            // if (this.meta.snekDirection == undefined) {
            //     this.meta.snekDirection = directions.LEFT
            // }
        },
    },
    mounted() {
        this.resetLevelEditor()
        this.directions = directions // fixme
        this.collisions = collisions // fixme
    }
}

</script>

<template>
    <Head title="Level editor"/>
    <div
        class="w-full relative sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white flex">


        <div class="gameboard editor text-center">
            <div v-for="boardRow,y in matrix" class="tile-row">
                <div v-for="boardCell,x in boardRow" class="tile" :class="boardCell.class" @mousedown="drawCell(x,y)"></div>
            </div>
        </div>

        <div class="flex flex-col p-2">
            <div class="toolbox mx-auto">
                <h1 class="text-white">Brushes</h1>
                <div class="toggle-brush mb-2">
                    <button class="text-white" :class="{'border-b-2': brush === collisions.WALL}" @click="selectBrush(collisions.WALL)">
                        <span class="w-6 h-6 brush tile wall align-middle">&nbsp;</span>
                        Walls
                    </button>
                    <button class="text-white ml-2" :class="{'border-b-2': brush === collisions.SNEK}" @click="selectBrush(collisions.SNEK)">
                        <span class="w-6 h-6 brush tile snek align-middle">&nbsp;</span>
                        Snek
                    </button>
                </div>
            </div>
            <div class="game-options">
                <div class="flex flex-row mb-2">
                    <label for="name" class="grow text-white">Level name</label>
                    <input type="text" v-model="name" required maxlength="20">
                </div>

                <div class="flex flex-row">
                    <label for="name" class="grow text-white">Initial direction</label>
                    <select v-model="meta.snekDirection">
                        <option v-for="value, dir in directions" :value="value">{{ dir }}</option>
                    </select>
                </div>

                <button class="bg-blue-400 text-white rounded-full p-2 float-right mt-2" @click="saveLevel">Save level</button>
                <Link v-if="mayDelete"
                      :href="route('game.editor.delete', {level: name})"
                      class="bg-red-600 text-white rounded-full p-2 float-right mt-2"
                >Delete level</Link>
            </div>
        </div>





    </div>
</template>
