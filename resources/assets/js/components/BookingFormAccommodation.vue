<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">Accommodation</button>
                    <p>Accommodation options for your tour group including indication of single or shared rooms requirements. Sharing is indicated by assignment of a group number.</p>
                </h5>
            </div>
            <div v-if="showAccommodation" class="card-body ept-form">
                <div v-if="showRegistered">
                    <h4>Accommodation Registered</h4>
                    <div class="row">
                       <div class="col">traveller name</div>
                       <div class="col">room type</div>
                       <div class="col">share group</div>
                    </div>
                    <div v-for="traveller in travellers" class="row">
                        <input type="hidden" readonly :value="traveller.id">
                        <div class="col">
                            <input type="text" readonly :value="`${traveller.first_name} ${traveller.last_name}`">
                        </div>
                        <div class="col">
                            <select v-model="traveller.room_type">
                                <option default value="0">Select a room type</option>
                                <option v-for="room in room_types" :value="room.name">{{room.name}}</option>
                            </select>
                        </div>
                        <div class="col">
                            <span v-if="occupancy(traveller.room_type) > 1">
                                <select v-model="traveller.group_id">
                                    <option default value="0">Share group selection</option>
                                    <option v-for="group in groups" :key="group">{{group}}</option>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
                <button @click="setAccommodation" class="btn btn-primary">Set Accommodation Options</button>
                <button @click="resetAccommodation" class="btn btn-default">Reset Accommodation</button>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from '../bus'
import Vue from 'vue'
export default {
    props: ['tour'],
    data() {
        return {
            debug: 9,
            moduleName: 'Accommodation',
            showAccommodation: false,
            booking_token: null,
            showRegistered: true,
            accommodations: [],
            travellers: [],
            initTravellers: [],
            room_types: ['Single', 'Twin', 'Double', 'Shared'],
            room_type: {},
            groups: [1,2,3,4,5,6,7,8,9]
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
            this.initTravellers = this.travellers
        })
    },
    mounted() {
      this.getAccommodationOptions()
    },
    methods: {
        setAccommodation() {
          let that = this
          // booking the accommodation options in the booking_accommodations table
            console.log('setAccommodation', this.travellers)
            axios.post(`/accommodation/${tour}/booking/${token}`, this.travellers)
              .then(response => {
                that.showAccommodation = false
                console.log(response)
              })
              .catch(error => console.log(error))
        },
        resetAccommodation() {
            this.travellers = this.initTravellers
            this.travellers.map(t => Vue.set(t, 'group_id', '0'))
            this.travellers.map(t => Vue.set(t, 'room_type', '0'))
            // console.log('resetAccommodation',this.travellers)
        },
        occupancy(name) {
          const item = this.room_types.filter(type => type.name == name);
          console.log('max=', item)
          const record = item.find(i => i.name === name)
          if (record) {
             return record.maximum_occupancy
          } else {
             return 1
          }
        },
        assignGroups() {
            return [1,2,3,4,5]
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
        },
        getAccommodationOptions() {
            const that = this;
            const url = `/api/booking/accommodation/options/${this.tour.id}`
            axios.get(url)
                .then(response => {
                    that.debug>3 && console.log('getAccommodationOptions', response.data)
                    that.room_types = response.data.options.room_types
                    console.log('accommodation: roomtypes: ', that.room_types)
                })
                .catch(error => console.log(error))
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
