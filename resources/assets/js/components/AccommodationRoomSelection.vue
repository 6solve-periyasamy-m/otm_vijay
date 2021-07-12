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
                    <option v-for="(share, id) in others /*getOthers(traveller)*/" :key="id" :value="share">
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
            logging: false,
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
            //this.group = group
            //const group = this.$root.group
            this.others = group
            this.init()
        })
        bus.$on('setOthers', (others, traveller) => {
            console.log('>>>setOthers', others)
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
            // let group = this.group
            // group = group.filter(t => t.id != this.traveller.id)
            // this.logging>3 && console.log('getOthers', this.group, group)
            console.log('getOthers', this.control, this.others); //, Object.values(this.others))
            // let others = this.others
            // // if (this.others.length < 1) {
            // //     this.others = this.$root.group
            // // }
            // others = others.filter(t => t.id != others.id && t.id != traveller.id)
                // // hack - this works!  wtf is it doing?  forceUpdate calls getOthers...
                // if (this.control > -96) {
                //     console.log('forceUpdate control', this.control)
                //     this.$forceUpdate()
                //     this.control--
                // }
            if (this.control-- > 0) {
                let others = this.others
                // if (this.others.length < 1) {
                //     this.others = this.$root.group
                // }
                this.debug > 2 && console.log('1. getOthers: others: ', this.control, traveller, traveller.first_name, others.map(o=>o.first_name))
                others = others.filter(t => t.id != traveller.id)
                if (typeof traveller.sharer != 'undefined') {
                    others = others.filter(t => t.id != traveller.sharer.id)
                }
                this.debug > 2 && console.log('2. getOthers: others: ', this.control, others.map(o=>o.first_name))
                this.others=others

                return others
            }
        },
        selectSharer(traveller) {
            console.log('selectSharer CHECK', traveller.first_name)
            traveller.sharer =  Object.values(this.sharer)[0]
            //this.share = traveller.sharer
            this.debug>4 && console.log('settin up the roomshare for ', traveller.first_name, ' being ', traveller.sharer, this.sharer)

            bus.$emit('setRoomShare', this.traveller)
            this.control = this.group.length
            // this.sharer[traveller.id] = traveller.sharer.id
            //axios.post('/api/booking/accommodation/')
        },
        selectedRoom() {
            this.selected = this.room_selected
            this.logging>3 && console.log('Room selection', this.room_selected)
        },
        loadRoomsForTour() {
            this.logging>3 && console.log(this.tour, this.order_id)
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
