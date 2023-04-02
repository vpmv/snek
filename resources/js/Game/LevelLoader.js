import {cellTypes, collisions, directions} from "@/Game/enums";
import Snek from "@/Game/Snek.vue";

class GameBoard {
    name = ''
    matrix = []
    snek   = null

    constructor(name, matrix = [], snek = null) {
        if (!snek instanceof Snek) {
            snek = new Snek([])
        }

        this.name   = name
        this.matrix = matrix
        this.snek   = snek
    }

    getSize = () => {
        return this.matrix.length
    }
}

const setCell   = (matrix, x, y, collision) => {
    if (matrix[y] === undefined) {
        matrix[y] = []
    }
    matrix[y][x] = {class: collision}
}
const loadLevel = (name, grid = [], meta = {}, setSnake = false) => {
    let matrix  = [];
    let snekPos = [];
    let snekDir = meta.snekDirection || null

    grid.forEach((row, y) => {
        row.forEach((cell, x) => {
            let coll = cellTypes[cell].collision

            // snek type
            if (coll === cellTypes.s.collision) {
                snekPos.push([y, x])
                if (!setSnake) {
                    coll = collisions.VOID
                }
            }
            setCell(matrix, x, y, coll)
        })
    })
    if (snekPos && [directions.RIGHT, directions.DOWN].indexOf(snekDir) !== -1) {
        snekPos = snekPos.reverse()
    }

    return new GameBoard(name, matrix, new Snek(snekPos, snekDir))
}

export {GameBoard, loadLevel}
