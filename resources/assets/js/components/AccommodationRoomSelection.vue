<template>
    <div class="accommodation-select-rooms">
        <select v-model="room_selected" @change="selectedRoom">
            <option v-for="room in rooms" :key="room.id" :value="room">{{room.board_type_name}} {{room.room_type_name}} for {{room.maximum_occupancy}} {{room.maximum_occupancy > 1 ? 'people' : 'person' }}</option>
        </select>
        <div v-if="room_selected.maximum_occupancy>1">
            <div v-for="index in (room_selected.maximum_occupancy - 1)" 
                :key="index" 
                class="accommodation-traveller__share-with">
                Share with 
                <keep-alive>
                    <span v-if="traveller.sharename != undefined 
                            && traveller.sharename[traveller.id] != undefined
                            && traveller.sharename[traveller.id][index-1] != undefined">
                            {{traveller.sharename[traveller.id][index-1]}}
                    </span>
                    <select v-else @change="selectSharer(traveller)" 
                        v-model="sharer[traveller.id]" :key="traveller.id">
                        <option selected disabled>Open</option>
                        <option v-for="(share, id) in others" :key="id" :value="share">
                            {{share.first_name}} {{share.last_name}}
                        </option>
                    </select>
                </keep-alive>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from "../bus";
export default {
    props: ['tour', 'order_id', 'traveller', 'group'],
    name: 'BFA',
    data() {
        return {
            debug: 6,
            rooms: [],
            room_selected: {}, 
            sharer: [],
            sharers: [],
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
            this.others = others
            this.evalOthers(traveller)
            //this.control--
        })
        bus.$on('setTravellerShares', traveller => {
            console.log('>>>>>>> setTravellerShares event received traveller', traveller.first_name, ' share count:',traveller.shares.length)
            console.log(traveller.sharename.map(share => {
                console.log('TRAVELLER:',traveller.first_name, ' SHARE', share)
            }))
            this.traveller.shares = traveller.shares
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
            this.others = this.evalOthers(this.traveller)
            this.control = this.others.length
            this.sharer = []
            this.sharers = []
        },
        sharerIndex(traveller_id, index) {
            const max = this.group.length //this.others.length
            console.log('sharerIndex', traveller_id, index, traveller_id * max + index)

            return traveller_id * max + index
        },
        evalOthers(traveller) {
            let others = this.others
                this.debug>3 && console.log('evalOthers: prefilter others: ', this.control, traveller, traveller.first_name, others.map(o=>o.first_name))
            others = others.filter(t => t.id != traveller.id)
            const storeOthers = others
            if (typeof traveller.sharer != 'undefined') {
                others = others.filter(t => t.id != traveller.sharer.id)
                //this.control--
            }
                this.debug>2 && console.log('evalOthers: others: ', this.control, others.map(o=>o.first_name))
            return others
            //this.others = others
            //this.control = others.length
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
            const sharer =  Object.values(this.sharer)[0]
            
            if (typeof sharer != 'undefined') {
                    console.log('***** selectSharer', sharer.first_name)
                    this.debug>4 && console.log('settin up the roomshare for ', traveller.first_name, ' being ', sharer.first_name)
                bus.$emit('setRoomShare', traveller, sharer)
            }
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
