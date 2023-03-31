<script>
import {directions} from '@/Game/enums.js'

class Snek {
    position  = []
    head      = []
    tail      = []
    direction = directions.LEFT
    tailMemory = 0

    constructor(position, direction = null) {
        this.position  = position || []
        this.direction = direction
        this.setOrientation()
    }

    setOrientation() {
        if (this.position.length) {
            this.head = this.position[0]
            this.tail = this.position[this.position.length - 1]
            if (this.head[0] > this.tail[0]) {
                this.direction = directions.DOWN
            } else if (this.head[0] < this.tail[0]) {
                this.direction = directions.UP
            } else if (this.head[1] > this.tail[1]) {
                this.direction = directions.RIGHT
            } else if (this.head[1] < this.tail[1]) {
                this.direction = directions.LEFT
            }
        }
    }

    // getConfig = () => {
    //     return {
    //         position: this.position,
    //         head: this.head,
    //         tail: this.tail,
    //         direction: this.direction,
    //         tailMemory: 0,
    //     }
    // }
}

export default Snek

</script>
