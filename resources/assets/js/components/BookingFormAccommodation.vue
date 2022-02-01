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
                <div v-if="showRegistered">
                    <h4>Accommodation Registered</h4>
                    <div v-for="accommodation in accommodations" class="row">
                        <div class="col">{{accommodation.first_name}} {{accommodation.last_name}}</div>
                        <div class="col">{{accommodation.room ? accommodation.room['room_type_name'] : ''}}</div>
                        <div class="col" :class="`colorise-${accommodation.accommodation_inventory_tour_id} % 4`">{{accommodation.accommodation_name}} {{accommodation.maximum_occupancy}}</div>
                        <div class="col">{{accommodation.room_type_name}} {{accommodation.board_type_name}} {{accommodation.shared?'sharer':''}}</div>
                        <div class="col">{{accommodation.shared !== undefined && accommodation.shared.sharename !== null ? accommodation.shared.sharename : '-'}}</div>
                        <div class="col">{{accommodation.shared !== undefined && accommodation.shared.sharename !== undefined && accommodation.shared.sharename.length ? accommodation.shared.sharename.join(',') : '-'}}</div>
                    </div>
                    <button class="btn btn-default" @click="resetButton">Reset</button>
                </div>
                <div v-else>
                    <h4>Accommodation options</h4>
                    <div class="accommodation_travellers" v-for="(traveller, index) in group" v-bind:key="index">
                        <div class="accommodation_traveller">
                            <div class="accommodation_traveller__name">
                                {{traveller.first_name}} {{traveller.last_name}}
                            </div>
                            <div class="accommodation_traveller__options--labels compress">
                                <accommodation-room-selection 
                                    :token="booking_token"
                                    :tour="tour" 
                                    :traveller="traveller" 
                                    :group="group">
                                </accommodation-room-selection>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" @click="register">Register</button>
                    <button class="btn btn-default" @click="resetButton">Reset</button>
                    <p>Set your preferred accommodation selections and register to save settings. Availability of your settings is confirmed when the booking is completed.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { bus } from "../bus";
import dates from "../utilities";
import AccommodationRoomSelection from './AccommodationRoomSelection.vue'
/**
 * accommodation is found related to the tour
 */
function initialState() {
    return {
        debug: false,
        moduleName: 'Accommodation',
        booking_token: null,
        showAccommodation: false,
        accommodations: [],
        travellers: [],
        booked: [],
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
        showRegistered: false,
        rootCSS: null
    }
}
export default {
    components: { AccommodationRoomSelection },
    props: ["tour"],
    data() {
        return initialState()
    },
    mounted() {
        // console.log('Accommodation component mounted')
    },
    created() {
        let that = this
        this.rootCSS = document.querySelector(':root')
        this.debug > 3 && console.log('Accommodation: this.tour=', this.tour, this.travellers)
        this.group = this.others = this.travellers
        this.setup()
        this.eventInit()

        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
        })
        bus.$on('bookingCreated', (booking) => {
            console.log('accommodation: booking created', booking)
        })
        bus.$on("TravellerBookingsLoaded", (travellers) => {
            this.debug>2 && console.log("Accommodation: travellers loaded", travellers);
            travellers.map(traveller => this.travellers.push(traveller));
            this.loadAccommodationBooking(this.travellers)
        })
        bus.$emit('loadOthers', this.group)
    },
    methods: {  
        setup() {
            if (this.group != undefined && this.group.length) {
                this.group.map(t => {
                    t.shared = false
                })
            } else {
                console.log('ACCOMODATION MODULE GROUP IS NOT DEFINED')
            }
            console.log('setup', this.group)
        },
        eventInit() {
            let that = this
            bus.$on('setRoomSelection', function(traveller, room) {
                that.travellers.filter(t => t.id == traveller.id).map(t => t.room_selected = room)
                const others = that.others.filter(t => t.id != traveller.id)
                bus.$emit('setOthers', others, traveller)
                bus.$emit('ReloadBooking', that.booking_token)
                that.others = others
            })
            bus.$on('setRoomShare', function(traveller, sharer, room) {
                that.debug > 2 && console.log('setRoomShare for traveller', traveller.first_name, sharer.first_name)
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
                that.debug > 2 && console.log('BFA: setRoomShare for ', room, traveller.id, traveller.first_name, sharer.first_name)
                that.room_selection[traveller.id] = room
                that.others = that.othertravellers(sharer.id)
                const reducedGroup = that.group.filter(t => {
                    return t.id != sharer.id
                })
                that.group = reducedGroup
                bus.$emit('BookingReload', that.booking_token)
                that.$forceUpdate()
            })
            bus.$on('AccommodationRoomSelectorReset', function(bookings, group) {
                // console.log('AccommodationRoomSelectorReset accommodations:',bookings, group)
                const url = '/api/booking/accommodation/delete'
                const groupIds = group.map(g => g.order_customer_id)
                const inventoryTourIds = that.room_selection.map(booking => {
                    // console.log('booking: ', booking)
                    return booking.inventory_tour_id
                })
                const data = {
                    inventoryTourIds: inventoryTourIds,
                    groupIds: groupIds
                }
                this.$emit('BookingReload', this.booking_token)
            })
            bus.$on('accommodationBookingsLoaded', function(booked) {
                that.booked = booked
                // console.log('---- that.booked set', that.booked)
            })
        },
        init() {
            let that = this
            this.group = this.others = this.travellers
            this.group.map(t => {
                if (t != undefined) {
                    this.debug > 4 && console.log('init', t, t.sharename, t.shares)
                    if (t.sharename != undefined) {
                        t.sharename.map(s => s = []);
                    }
                    if (t.shares != undefined) {
                        t.shares.map(s => s = []);
                    }
                    t.shared = false
                }
            })
            this.getAccommodationOptions()
        },
        resetButton() {
            // bus.$emit('AccommodationRoomSelectorReset', this.booked, this.group)
            // Object.assign(this.$data, initialState())
            this.init()
            this.$forceUpdate()
            this.setup()
            this.showAccommodation = true
            this.showRegistered = false
            this.$emit('ReloadBooking', this.booking_token)
            console.log('reset: ', this.travellers)

        },
        register() {
            console.log('Accommodation register ... travellers', this.travellers)
            this.travellers.map(traveller => {
                let that = this
                const data = {}
                data.shares = []
                data.shared = []
                if (traveller.shares != undefined) {
                    const shares = traveller.shares.filter(s => s != null).map(i => i.map(t => t.id))
                    data.shares = shares[0]
                }
                if (traveller.sharename != undefined) {
                    const id = traveller.id
                    const sharename = traveller.sharename.filter(s => s != null)
                    data.shared = { id: id, sharename: sharename[0] }
                }
                data.customer_id = traveller.id
                data.room = traveller.room_selected //this.room_selection[traveller.id]
                this.accommodations = []
                console.log('>><<>><<>><<>> booking reservation:', traveller, data)
                axios.post('/api/booking/accommodation/reserve', {
                        type: 'accommodation',
                        traveller: data,
                        tour: this.tour,
                        accommodation_inventory_tour_id: this.accommodation_inventory_tour_id,
                        token: this.booking_token
                    })
                    .then(response => {
                        that.debug > 4 && console.log('booking reservation: response', response)
                        let accommodation = response.data.accommodation
                        that.loadAccommodationBooking(that.travellers)
                        that.showRegistered = true
                        bus.$emit('ReloadBooking', that.booking_token)
                        //that.accommodations=accommodation
                        //console.log('registered accommodations', accommodation, accommodation.length > 0, accommodation.length > 1)
                        // if (response.data.accommodation) {
                        //     that.accommodations.push(response.data.accommodation);
                        // } else {
                        //     alert('nothing registered!')
                        // }
                    })
                    .catch(error => console.log(error))
            })
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
            // if (this.showAccommodation) {
            //     this.others = this.travellers
            //     this.getAccommodationOptions()
            // }
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
            const url = `/api/booking/accommodation/options/${this.tour.id}`
            await axios.get(url)
                .then((response) => {
                    this.debug>3 && console.log('getAccommodationOptions', response.data)
                    that.accommodations = response.data.accommodations
                    if (that.accommodations !== undefined && that.accommodations !== null) {
                        that.accommodations.map(
                            (accommodation) => {
                                that.occupancy[accommodation.accommodation_id] =
                                accommodation.maximum_occupancy
                        })
                    } else {
                        console.log('null data?', response)
                    }
                })
                .catch((error) => console.log(error))
        },
        loadAccommodationBooking(travellers) {
            const that = this;
            if (travellers == undefined || !travellers.length) {
                console.log('loadAccommodationBooking has no travellers to load')
                return
            }
            this.debug > 3 && console.log('***** loadAccommodationBooking started... travellers ', travellers)

            let url = `/api/booking/accommodation/booking/${this.booking_token}/tour/${this.tour.id}`           
            axios.get(url)
                .then((response) => {
                    const bookings = response.data.bookings
                    that.debug > 3 && console.log('Accommodation: loadBookings ', bookings)
                    that.debug > 7 && console.log('Accommodation: loadBooking response ', response)
                    
                    // TODO: marking of shared rooms is not quite right
                    let used = new Array(bookings.length).fill(0);
                    that.accomodations = bookings.map((b, i) => {
                        that.accommodations[i] = b
                        that.accommodations[i].shared = used.filter(m => m == b.accommodation_inventory_id).length > 0
                        if (that.accommodations[i].shared) {

                        }
                        used.push(b.accommodation_inventory_id)
                    })
                    that.accommodations.map((a,i) => {
                        const t = that.travellers.filter(t => t.id === a.customer_id)[0]
                        console.log('selected', t)
                        a.first_name = t.first_name
                        a.last_name = t.last_name
                    })
                    let noBooking = that.accommodations == null || typeof that.accommodations == 'undefined' || that.accommodations.length == 0
                    that.showRegistered = !noBooking
                    that.$forceUpdate()
                })
                .catch((error) => console.log(error));
        },
    }
}
</script>

<style scoped lang="scss">
.colorise-1 {
    background-color: lightblue;
}
.colorise-2 {
    background-color: lightcoral;
}
.colorise-3 {
    background-color: lightgreen;
}
.colorise-4 {
    background-color: lightcyan;
}
.accommodation_traveller {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: space-between;
    &__name {
        width: 8rem;
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
        margin-left: 0rem;
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
        margin-left: 0rem;
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
