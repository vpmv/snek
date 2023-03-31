
const directions = Object.freeze({
                                     LEFT: 'ArrowLeft',
                                     RIGHT: 'ArrowRight',
                                     UP: 'ArrowUp',
                                     DOWN: 'ArrowDown',
                                 })

const collisions = Object.freeze({
                                     WALL: 'wall',
                                     FOOD: 'food',
                                     SNEK: 'snek',
                                     VOID: 'void',
                                 });

const cellTypes = Object.freeze(
    {
        w: {
            cell: 'w',
            collision: collisions.WALL,
        },
        v: {
            cell: 'v',
            collision: collisions.VOID,
        },
        s: {
            cell: 's',
            collision: collisions.SNEK,
        },
    }
)

const cellTypesInverse = Object.freeze(
    {
        wall: {
            cell: 'w',
            collision: collisions.WALL,
        },
        void: {
            cell: 'v',
            collision: collisions.VOID,
        },
        snek: {
            cell: 's',
            collision: collisions.SNEK,
        },
    }
)


export {directions, collisions, cellTypes, cellTypesInverse}
