<script>
import {nextTick} from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {PauseIcon, PlayIcon} from "@heroicons/vue/24/solid"
import {collisions, directions} from '@/Game/enums.js'
import Snek from '@/Game/Snek.vue'
import {useForm} from "@inertiajs/vue3";
import {loadLevel} from "@/Game/LevelLoader";

export default {
    components: {
        InputLabel,
        TextInput,
        Modal,
        PauseIcon,
        PlayIcon,
    },
    props: {
        boardConfig: {
            type: Object,
            required: true,
        },
        levelName: {
            type: String,
            required: true,
        },
        highScore: {
            type: Number,
            required: false,
        }
    },

    data() {
        return {
            gameStop: true,
            gameOver: false,
            showForm: false,
            level: 0,
            timeout: 250,
            gameBoard: [],
            interval: null,
            snek: {},
            form: useForm({username: '', score: 0, levelname: ''})
        };
    },

    mounted() {
        this.gameReset()
        this.gameLoop()
    },

    methods: {
        gameReset() {
            let levelData = loadLevel(this.levelName, this.boardConfig.matrix, this.boardConfig.meta);

            this.gameBoard = []
            this.snek      = {}
            this.level     = 0
            this.timeout   = 250

            this.gameBoard = levelData.matrix
            this.snek      = levelData.snek

            if (!this.snek.position.length) {
                let mid = Math.floor(levelData.getSize() / 2);

                this.snek = new Snek([
                    [mid, mid],
                    [mid, mid + 1]
                ])
            }

            this.snek.position.forEach((v) => {
                let y                      = v[0];
                let x                      = v[1];
                this.gameBoard[y][x].class = collisions.SNEK;
            });

            this.focusGameBoard();
            nextTick(() => {
                if (this.gameStop) {
                    this.gameOver = false;
                    this.gameStop = false
                    this.setFood()
                    this.gameLoop()
                }
            })
        },
        gameLoop() {
            if (!this.gameStop) {
                this.interval = setInterval(() => {
                    this.gameStep()
                }, this.timeout);
            }
        },
        gameStep() {
            if (this.gameStop) {
                return;
            }

            if (this.snekAt(collisions.FOOD)) {
                this.level++
                this.snekGrow()
                this.setFood()
                if (this.level % 8 === 0) {
                    this.gameSpeedUp();
                }
            } else if (!this.snekAt(collisions.VOID)) {
                this.showGameOver();
                this.snekDie()
            }
            this.snekCrawl();
        },
        gameSpeedUp() {
            clearInterval(this.interval)
            this.timeout -= 25
            this.gameLoop()
        },
        gameBoardCoord(x, y) {
            return this.gameBoard[y][x];
        },
        toggleGameStop() {
            this.gameStop = !this.gameStop;
            if (this.gameStop) {
                clearInterval(this.interval)
            } else {
                this.gameLoop()
                this.focusGameBoard();
            }
        },
        showGameOver() {
            this.gameOver = true;
            this.gameStop = true;
            this.showForm = this.level > 1;
            clearInterval(this.interval)
        },
        setFood() {
            let col   = 'wall',
                max   = this.gameBoard.length - 1,
                x     = 0,
                y     = 0,
                cell  = null,
                tries = 0;

            while (col !== collisions.VOID) {
                x    = Math.ceil(Math.random() * (max))
                y    = Math.ceil(Math.random() * (max))
                cell = this.gameBoardCoord(x, y);
                col = cell.class;
                tries++

                // fixme
                if (tries >= 20) {
                    this.showGameOver()
                }
            }

            this.gameBoard[y][x].class = collisions.FOOD
        },
        snekCrawl() {
            let x = this.snek.head[1]
            let y = this.snek.head[0]

            switch (this.snek.direction) {
                case directions.UP:
                    this.snekUpdateHead(x, y - 1)
                    this.snekUpdateTail()
                    break;
                case directions.DOWN:
                    this.snekUpdateHead(x, y + 1)
                    this.snekUpdateTail()
                    break;
                case directions.LEFT:
                    this.snekUpdateHead(x - 1, y)
                    this.snekUpdateTail()
                    break;
                case directions.RIGHT:
                    this.snekUpdateHead(x + 1, y)
                    this.snekUpdateTail()
                    break;
            }
        },
        snekDie() {
            console.info('i died');
        },
        snekGrow() {
            this.snek.tailMemory += Math.round(this.level / 5);
        },
        snekAt(collisionType) {
            let x = this.snek.head[1],
                y = this.snek.head[0]

            switch (this.snek.direction) {
                case directions.UP:
                    y--
                    break;
                case directions.DOWN:
                    y++
                    break;
                case directions.LEFT:
                    x--
                    break;
                case directions.RIGHT:
                    x++
                    break;
            }
            let cell = this.gameBoardCoord(x, y);
            if (cell === undefined) {
                return false;
            }
            return cell.class.indexOf(collisionType) > -1;
        },
        snekUpdateHead(x, y) {
            this.snek.position.unshift([y, x])
            this.gameBoard[y][x].class = collisions.SNEK;
            this.snek.head             = this.snek.position[0];
        },
        snekUpdateTail() {
            if (!this.snek.tailMemory) {
                // remove last tail piece
                let tail                               = this.snek.position.pop();
                this.gameBoard[tail[0]][tail[1]].class = collisions.VOID
                // update tail position
                this.snek.tail                         = this.snek.position[this.snek.position.length - 1];
            } else {
                // keep tail in place, increases body length
                this.snek.tailMemory--
            }
        },
        focusGameBoard() {
            this.$refs.gameBoardDiv.focus();
        },
        changeDirection(e) {
            if (e.key === directions.UP && this.snek.direction !== directions.DOWN) {
                this.snek.direction = directions.UP
            } else if (e.key === directions.DOWN && this.snek.direction !== directions.UP) {
                this.snek.direction = directions.DOWN
            } else if (e.key === directions.LEFT && this.snek.direction !== directions.RIGHT) {
                this.snek.direction = directions.LEFT
            } else if (e.key === directions.RIGHT && this.snek.direction !== directions.LEFT) {
                this.snek.direction = directions.RIGHT
            }
        },
        submitScore() {
            this.form.score = this.level
            this.form.levelname = this.levelName
            this.form.post(route('game.score'), {
                onFinish: () => {
                    this.form.username = ''
                    this.form.score    = 0;
                    this.showForm      = false
                }
            });
        }
    },
}
</script>

<template>
    <div id="game" class="relative">

        <div class="game-controls mx-auto text-gray-400 flex">
            <div class="score grow">
                <span class="title">Score:</span>
                <span class="font-semibold ml-2">{{ level }}</span>
            </div>
            <div class="">
                <button class="" @click="toggleGameStop">
                    <PauseIcon v-if="!gameStop" class="right-10 h-6 w-6 "/>
                    <PlayIcon v-else class="right-10 h-6 w-6 "/>
                </button>
            </div>

        </div>

        <div id="gameboard" @keydown="changeDirection" tabindex="0" class="outline-0 mx-auto" ref="gameBoardDiv">
            <div v-for="boardRow in gameBoard" class="tile-row">
                <div v-for="boardCell in boardRow" class="tile" :class="boardCell.class"></div>
            </div>
        </div>

        <div v-if="gameStop" class="absolute left-0 bg-blue-800 w-full" :class="{'top-1/2': !showForm, 'top-0 h-full': showForm }">
            <div v-if="gameOver" class="text-white p-4">
                <h3 class="text-center mb-2">Game Over</h3>

                <h4 v-if="highScore < level" class="text-red-400 capitalize">New highscore</h4>
                <form v-if="showForm" @submit.prevent="submitScore">
                    <div class="flex flex-col">
                        <div class="flex flex-row">
                            <span class="grow">Score</span>
                            <span class="shrink">{{ level }}</span>
                        </div>
                        <div class="flex flex-row">
                            <span class="grow">Tail length</span>
                            <span class="shrink">{{ snek.position.length }}</span>
                        </div>
                        <div class="flex flex-row">
                            <label for="username" autofocus class="grow">Player name</label>
                            <span class="shrink">
                                <input class="text-black" name="username" type="text" v-model="form.username">
                            </span>
                        </div>
                    </div>


                    <button type="submit" class="rounded-full block bg-blue-400 text-white px-2 py-0.5 mx-auto mt-2">Submit</button>
                </form>
                <span v-if="level <= 1">Try again, ya noob...</span>

                <button class="rounded-full block bg-blue-400 text-white px-2 py-0.5 mx-auto mt-2" @click="gameReset">
                    Restart game
                </button>
            </div>
            <div v-else>
                Game Paused
                <button class="rounded-full block bg-blue-800 text-white px-2 py-0.5" @click="toggleGameStop">
                    Continue
                </button>
            </div>
        </div>

    </div>
</template>
