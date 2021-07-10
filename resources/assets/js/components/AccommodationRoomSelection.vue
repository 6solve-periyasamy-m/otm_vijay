<template>
    <div class="accommodation-select-rooms">
        <select v-model="room_selected" @change="selectedRoom">
            <option v-for="room in rooms" :key="room.id" :value="room">{{room.board_type_name}} {{room.room_type_name}} for {{room.maximum_occupancy}} {{room.maximum_occupancy > 1 ? 'people' : 'person' }}</option>
        </select>
        <div v-if="room_selected.maximum_occupancy>1">
        <div v-for="index in (room_selected.maximum_occupancy - 1)" :key="index" class="accommodation_traveller_options--share-with">
            <p>Share with
            <keep-alive>
                <select @change="selectSharer(traveller)" v-model="sharer[traveller.id]">
                    <option selected disabled>Open</option>
                    <option v-for="(share, id) in getOthers(traveller)" :key="id" :value="share">
                        {{share.first_name}} {{share.last_name}}
                    </option>
                </select>
            </keep-alive>
            </p>
            <hr/>
        </div>
        </div>
    </div>
</template>
<script>
// import { defineComponent } from '@vue/composition-api'
// export default defineComponent({
import { bus } from "../bus";
export default {
    props: ['tour', 'order_id', 'traveller', 'group'],
    data() {
        return {
            rooms: [],
            room_selected: {}, 
            sharer: [],
            others: [],
            selected: null
        }
    },
    created() {
        bus.$on('AccommodationRoomSelectorInit', (group) => {
            this.group = group
            this.others = this.group
            this.init()
        })
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            this.loadRoomsForTour();
            this.getOthers()
            this.sharer = []
        },
        getOthers() {
            let group = this.group
            group = group.filter(t => t.id != this.traveller.id)
            console.log('getOthers', this.group, group)
            //this.others = this.others.filter(t => t.id != this.others.id)
            //this.$forceUpdate()
            return group
        },
        selectSharer() {
            this.traveller.sharer =  Object.values(this.sharer)[0]
            bus.$emit('setRoomShare', this.traveller)
            //axios.post('/api/booking/accommodation/')
        },
        selectedRoom() {
            this.selected = this.room_selected
            console.log('Room selection', this.room_selected)
        },
        loadRoomsForTour() {
            console.log(this.tour, this.order_id)
            axios.get(`/api/accommodation/rooms/tour/${this.tour.id}/${this.order_id}`)
                .then(response => {
                    console.log('accomodation rooms for tour', response)
                    this.rooms = response.data.rooms
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>
