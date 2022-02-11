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
                        <div>traveller name</div>
                        <div>room type</div>
                        <div>share group</div>
                    {{accommodations}}
                    <div v-for="accommodation in accommodations" class="row">
                        <div><input type="text" readonly :value="accommodations.traveller"></div>
                        <div>
                            <select v-model="room_type">
                                <option>Select a room type</option>
                                <option v-for="room_type in room_types" :value="room_type.name"></option>
                            </select>
                        </div>
                        <div>
                            <select v-model="group_id">
                                <option>Share group selection</option>
                                <option v-for="group in assignGroups"></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from '../bus'
export default {
    props: ['tour'],
    data() {
        return {
            debug: 9,
            moduleName: 'Accommodation',
            booking_token: null,
            showAccommodation: false,
            showRegistered: true,
            accommodations: [],
            travellers: [],
            room_types: ['Single', 'Twin', 'Double', 'Shared'],
            groups: []
        }
    },
    created() {
        const that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
        })
        bus.$on("TravellersLoaded", (travellers) => {
            this.debug>2 && console.log("Accommodation: travellers loaded", travellers, this.travellers, that.travellers);
            travellers.map(traveller => this.travellers.push(traveller));
            this.loadAccommodationBooking(this.travellers)
        })
    },
    mounted() {
      this.getAccommodationOptions()
    },
    methods: {
        assignGroups() {
            return [1,2,3,4,5]
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
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
            console.log('url', url)
            axios.get(url)
                .then((response) => {
                    const bookings = response.data.bookings
                    that.debug > 7 && console.log('Accommodation: loadBooking response ', response)
                    if (bookings == undefined || !bookings.length) {
                      return
                    }
                    that.debug > 3 && console.log('Accommodation: loadBookings ', bookings)

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
</style>
