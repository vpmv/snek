<script setup>
import {GameBoard, loadLevel} from "@/Game/LevelLoader";
import {onMounted, watch} from "vue";

const props = defineProps({
    matrix: {
        type: Array,
        required: true,
    },
    meta: {
        type: Object,
        required: false,
        default: () => {
            return {};
        }
    },
    name: {
        type: String,
        required: true
    }
});

let gameBoard = loadLevel(props.name, props.matrix, props.meta)
watch(props.matrix, () => {
          gameBoard = loadLevel(props.name, props.matrix, props.meta)
      }
)
</script>

<template>
    <div class="gameboard preview text-center">
        <h4>{{ name }}</h4>
        <div v-for="boardRow in gameBoard.matrix" class="tile-row">
            <div v-for="boardCell in boardRow" class="tile" :class="boardCell.class"></div>
        </div>
    </div>
</template>
