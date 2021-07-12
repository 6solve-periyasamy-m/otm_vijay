<template>
    <div class="accommodation-select-rooms">
        <select v-model="room_selected" @change="selectedRoom">
            <option v-for="room in rooms" :key="room.id" :value="room">{{room.board_type_name}} {{room.room_type_name}} for {{room.maximum_occupancy}} {{room.maximum_occupancy > 1 ? 'people' : 'person' }}</option>
        </select>
        <div v-if="room_selected.maximum_occupancy>1">
        <div v-for="index in (room_selected.maximum_occupancy - 1)" :key="index" class="accommodation_traveller_options--share-with">
            <p>Share with<br/>
                <keep-alive>
                    <select @change="selectSharer(traveller)" v-model="sharer[traveller.id]">
                        <option selected disabled>Open</option>
                        <option v-for="(share, id) in others" :key="id" :value="share">
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
import { bus } from "../bus";
export default {
    props: ['tour', 'order_id', 'traveller', 'group'],
    data() {
        return {
            debug: 6,
            rooms: [],
            room_selected: {}, 
            sharer: [],
            others: [],
            selected: null,
            share: {},
            control: true
        }
    },
    created() {
        bus.$on('AccommodationRoomSelectorReset', (group) => {
            this.others = group
            this.init()
        })
        bus.$on('setOthers', (others, traveller) => {
            this.debug>4 && console.log('>>>setOthers', others.map(o => o.first_name))
            this.others = this.getOthers(traveller)
            this.control--
        })
        this.others = this.group
        this.control = this.group.length
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            this.loadRoomsForTour();
            this.others = this.group
            this.control = this.others.length
            this.getOthers(this.traveller)
            this.sharer = []
        },
        getOthers(traveller) {
            this.debug>4 && console.log('getOthers', this.control, this.others); //, Object.values(this.others))
            if (this.control-- > 0) {
                let others = this.others
                this.debug>3 && console.log('getOthers: prefilter others: ', this.control, traveller, traveller.first_name, others.map(o=>o.first_name))
                others = others.filter(t => t.id != traveller.id)
                const storeOthers = others
                if (typeof traveller.sharer != 'undefined') {
                    others = others.filter(t => t.id != traveller.sharer.id)
                }
                this.debug>2 && console.log('getOthers: others: ', this.control, others.map(o=>o.first_name))
                this.others = others

                return storeOthers
            }
        },
        selectSharer(traveller) {
            traveller.sharer =  Object.values(this.sharer)[0]
            this.debug>4 && console.log('settin up the roomshare for ', traveller.first_name, ' being ', traveller.sharer, this.sharer)
            bus.$emit('setRoomShare', this.traveller)
            this.control = this.group.length
        },
        selectedRoom() {
            this.selected = this.room_selected
            this.debug>3 && console.log('Room selection', this.room_selected)
        },
        loadRoomsForTour() {
            let that = this
            this.debug>3 && console.log(this.tour, this.order_id)
            axios.get(`/api/accommodation/rooms/tour/${this.tour.id}/${this.order_id}`)
                .then(response => {
                    that.debug && console.log('accomodation rooms for tour', response)
                    this.rooms = response.data.rooms
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>
