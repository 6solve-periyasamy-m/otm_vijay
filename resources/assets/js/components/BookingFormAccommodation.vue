<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">Accommodation</button>
                    <p>Accommodation options for your tour group including indication of single or shared rooms requirements.</p>
                </h5>
            </div>
            <div v-if="showAccommodation" class="card-body ept-form">
                <h2>Accommodation options</h2>
                <!-- {{travellers}} -->
                <div class="accommodation_travellers"
                    v-for="(traveller, index) in group"
                    v-bind:key="index">
                    <div class="accommodation_traveller">
                        <div class="accommodation_traveller__name">
                            {{traveller.first_name}} {{traveller.last_name}}
                        </div>
                        <div class="accommodation_booking-policy">
                        </div>
                        <div class="accommodation_traveller__options--labels">
                            <accommodation-room-selection
                                :order_id="order_id"
                                :tour="tour"
                                :traveller="traveller"
                                :group="group">
                            </accommodation-room-selection>
                        </div>
                    </div>
                </div>
                <button class="btn btn-default" @click="reset">Reset</button>
                <button class="btn btn-primary" @click="register">register</button>
                <p>Set your preferred accommodation selections and register to save settings. Availability of your settings is confirmed when the booking is completed.</p>
            </div>
        </div>
    </div>
</template>

<script>
import dates from "../utilities";
import { bus } from "../bus";
import Vue from "vue";
import AccommodationRoomSelection from './AccommodationRoomSelection.vue'
/**
 * accommodation is found related to the tour
 */
function initialState() {
    return {
        debug: 3,
        showAccommodation: false,
        accommodations: [],
        occupancy: [],
        traveller: {},
        group: [],
        others: [],
        room_selection: [],
        room_share: [],
        room_single: [],
        isShare: [],
        sharer: {},
        type: {},
    }
}
export default {
    components: { AccommodationRoomSelection },
    props: ["tour", "order_id", "order_token", "travellers"],
    data() {
        return initialState();
    },
    created() {
        this.debug>3 && console.log('Accommodation: this.tour=', this.tour, this.order_token, this.order_id, this.travellers)
        this.group = this.others = this.travellers
        this.setup()
    },
    methods: {
        setup() {
            let that = this
            this.group.map(t => t.shared = false)
            this.getAccommodationOptions()
            bus.$on('setRoomSelection', function(traveller, room) {
                console.log('setRoomSelection', traveller, room)
                that.travellers.filter(t => t.id == traveller.id).map(t => t.room_selected = room)
                const others = that.others.filter(t => t.id != traveller.id)
                bus.$emit('setOthers', others, traveller)
                that.others = others
            })
            bus.$on('setRoomShare', function(traveller, sharer, room) {
                that.debug>2 && console.log('>>> setRoomShare for traveller', traveller.first_name, sharer.first_name)
                if (typeof traveller.shares == 'undefined') {
                    traveller.shares = []
                }
                if (typeof traveller.shares[traveller.id] == 'undefined') {
                    traveller.shares[traveller.id] = []
                }
                traveller.shares[traveller.id].push(sharer)

                if (typeof traveller.sharename == 'undefined') {
                    traveller.sharename = []
                }
                if (typeof traveller.sharename[traveller.id] == 'undefined') {
                    traveller.sharename[traveller.id] = []
                }
                traveller.sharename[traveller.id].push(`${sharer.first_name} ${sharer.last_name}`)
                that.debug>2 && console.log('BFA: setRoomShare for ', room, traveller.id, traveller.first_name, sharer.first_name)
                that.room_selection[traveller.id] = room
                that.others = that.othertravellers(sharer.id)
                const reducedGroup = that.group.filter(t => {
                    return t.id != sharer.id
                })
                that.group = reducedGroup

                that.$forceUpdate()
            })
            bus.$emit('loadOthers', that.group)
        },
        init() {
            let that = this
            // this.traveller.shares.length = 0
            // this.traveller.length = 0
            // this.group.length = 0
            this.group = this.others = this.travellers
            this.group.map(t => {
                if (t != undefined) {
                    this.debug>4 && console.log('init', t, t.sharename, t.shares)
                    if (t.sharename != undefined) {
                        t.sharename.map(s => s = []);
                    }
                    if (t.shares != undefined) {
                        t.shares.map(s => s = []);
                    }
                    t.shared = false
                }
            })
            
            // this.getAccommodationOptions()
        },
        reset() {
            // this works well enough for now
            // but we can not store/restore selections for edits
            const href=window.location.href
            window.location.assign(href)
            return

            // having problem with the array in the AccommodationRoomSelector 
            // not reinitialising
            // Object.assign(this.$data, initialState())
            // this.init()
            // bus.$emit('AccommodationRoomSelectorReset', this.group)
            // this.showAccommodation = true
            // this.$forceUpdate()
            // this.setup()
        },
        register() {
            console.log('register ... travellers', this.travellers)
            this.travellers.map(traveller => {
                const data = {}
                data.shares = []
                data.shared = []
                if (traveller.shares != undefined) {
                    const shares = traveller.shares.filter(s => s!=null).map(i => i.map(t => t.id))
                    data.shares = shares[0]
                }
                if (traveller.sharename != undefined) {
                    const id = traveller.id
                    const sharename = traveller.sharename.filter(s => s!=null)
                    data.shared = { id: id, sharename: sharename[0] }
                }
                data.customer_id = traveller.id
                data.order_id = this.order_id
                data.room = traveller.room_selected //this.room_selection[traveller.id]
                console.log('register data', data)
                axios.post('/api/booking/accommodation/reserve', {
                    type: 'accommodation',
                    traveller: data,
                    tour: this.tour,
                    reference: this.order_token
                })
                .then(response => console.log(response))
                .catch(error => console.log(error))
            })
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
            if (this.showAccommodation) {
                this.others = this.travellers
                this.getAccommodationOptions()
            }
        },
        countOthers(traveller) {
            const others = this.othertravellers(traveller)
            return others.length
        },
        othertravellers(traveller_id) {
            let group = this.others
            this.others = group.filter(t => t.id != traveller_id)
            return this.others
        },
        async getAccommodationOptions() {
            const that = this;
            const url = `/api/booking/accommodation/${this.tour.id}`
            await axios.get(url)
            .then((response) => {
                this.debug>3 && console.log('getAccommodationOptions', response.data)
                that.accommodations = response.data.accommodations
                that.accommodations.map(
                    (accommodation) => {
                        that.occupancy[accommodation.accommodation_id] =
                        accommodation.maximum_occupancy
                });
            })
            .catch((error) => console.log(error))
        },
    }
}
</script>
<style scoped lang="scss">
.accommodation_traveller {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: space-between;
    &__name {
        width: 18rem;
        background: var(--light-grey);
    }
    &__options {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        &--share-with {
            width: 20rem;
            margin-left: 0;
        }
        &--single,
        &--share {
            width: 15rem;
        }
        &--labels {
            display: flex;
            flex-direction: row;
            div {
                width: 10rem;
            }
        }
    }
    @media screen and (min-width: 576px) {
        margin-left: 2rem;
        &__options {
            align-items: flex-end;
            &--single,
            &--share {
                width: 12rem;
            }
            &--labels {
                display: flex;
                flex-direction: row;
                div {
                    width: 12rem;
                }
            }
        }
    }
    @media screen and (min-width: 768px) {
        margin-left: 4rem;
        &__options {
            align-items: flex-end;
            &--single,
            &--share {
                width: 15rem;
            }
            &--labels {
                display: flex;
                flex-direction: row;
                div {
                    width: 15rem;
                }
            }
        }
    }
}
</style>