<template>
    <div class="accommodation-select-rooms">
        <select v-model="room_selected" @change="selectedRoom">
            <option v-for="room in rooms" :key="room.id" :value="room">{{room.board_type_name}} {{room.room_type_name}} for {{room.maximum_occupancy}} {{room.maximum_occupancy > 1 ? 'people' : 'person' }}</option>
            <!-- TODO: Nic, may need rename. room_type_name -> name. board_type_name -> name -->
        </select>
        <div v-if="room_selected.maximum_occupancy>1">
            <div v-for="index in (room_selected.maximum_occupancy - 1)" 
                :key="index" 
                class="accommodation-traveller__share-with">
                Share with 
                    <span v-if="traveller.sharename != undefined 
                            && traveller.sharename[traveller.id] != undefined
                            && traveller.sharename[traveller.id][index-1] != undefined">
                            {{traveller.sharename[traveller.id][index-1]}}
                    </span>
                    <select v-else @change="selectSharer(traveller)" 
                        v-model="sharer[traveller.id]" :key="traveller.id">
                        <option selected disabled>Open</option>
                        <option v-for="(share, id) in evalOthers(traveller)" :key="id" :value="share">
                            {{share.first_name}} {{share.last_name}}
                        </option>
                    </select>
              
            </div>
        </div>
    </div>
</template>
<script>
import Vue from 'vue'
import { bus } from "../bus";
export default {
    props: ['tour', 'order_id', 'traveller', 'group'],
    name: 'RoomSelection',
    data() {
        return {
            debug: 5,
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
        // reset event is not yet being used as it does not clear out
        // subarrays traveller.sharename/shares
        bus.$on('AccommodationRoomSelectorReset', (group) => {
            this.others = group
            // let c = 0
            // console.log(this.sharer)
            // group.map((s) => {
            //     console.log(s, this.sharer[c])
            //     Vue.set(this.sharer, c, 'asdf')
            //     c++
            // })
            // this.sharer = undefined
            if (this.traveller.sharename != undefined) {
                this.traveller.sharename.map(i => {
                    this.debug>4 && console.log('clearing ', i)
                    i.length = 0
                    i = []
                })
                this.traveller.shares.map(i => {
                    i.length = 0
                    i = []
                })
                this.traveller.sharename.length = 0
                this.traveller.sharename = []
                this.traveller.shares.length = 0
                this.traveller.shares = []
            }
            this.init()
        })
        bus.$on('setOthers', (others, traveller) => {
            this.debug>4 && console.log('>>>setOthers', others.map(o => o.first_name))
            this.others = others
            this.evalOthers(traveller)
        })
        this.others = this.group
        this.control = this.group.length
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            this.rooms = []
            this.room_selected = {}
            this.loadRoomsForTour();
            this.others = this.evalOthers(this.traveller)
            this.control = this.others.length
            this.sharer = []
        },
        // roomsAvailable() {
        //     return this.rooms.filter(r => r.maximum_occupancy <= this.others.length)
        // },
        sharerIndex(traveller_id, index) {
            const max = this.group.length //this.others.length
            this.debug>4 && console.log('sharerIndex', traveller_id, index, traveller_id * max + index)
            return traveller_id * max + index
        },
        evalOthers(traveller) {
            let others = this.others
            this.debug>3 && console.log('evalOthers: prefilter others: ', this.control, traveller, traveller.first_name, others.map(o=>o.first_name))
            this.debug>2 && console.log('evalOthers: others: ', this.control, others.map(o=>o.first_name))
            return others
        },
        getOthers(traveller) {
            this.debug>4 && console.log('getOthers', this.control, this.others); //, Object.values(this.others))
            if (this.control > 0) {
                const others = this.evalOthers(traveller)
                return others //storeOthers
            } else {
                console.log('no others left')
            }
        },
        selectSharer(traveller) {
            const that = this
            const sharer =  Object.values(this.sharer)[0]
            if (typeof sharer != 'undefined') {
                this.debug>4 && console.log('***** selectSharer', sharer.first_name)
                this.debug>4 && console.log('settin up the roomshare for ', traveller.first_name, ' being ', sharer.first_name)
                bus.$emit('setRoomShare', traveller, sharer, this.room_selected)
            }
        },
        selectedRoom() {
            this.selected = this.room_selected
            this.debug>3 && console.log('Room selection', this.room_selected)
            bus.$emit('setRoomSelection', this.traveller, this.room_selected)
        },
        loadRoomsForTour() {
            let that = this
            this.debug>3 && console.log(this.tour, this.order_id)
            axios.get(`/api/accommodation/rooms/tour/${this.tour.id}/${this.order_id}`)
                .then(response => {
                    that.debug && console.log('accomodation rooms for tour', response)
                    that.rooms = response.data.rooms
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>
