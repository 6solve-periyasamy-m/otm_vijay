<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">Accommodation</button>
                </h5>
                <p class="caption">
                    <font-awesome-icon icon="arrow-right" />
                    Accommodation options for your tour group including single or shared rooms requirements. 
                    Sharing is designated by selection of a group.
                </p>
            </div>
            <div v-if="showAccommodation" class="card-body ept-form">
                <div v-if="showRegistered">
                    <h4>Accommodation Registered</h4>
                    <div class="row">
                       <div class="col">traveller name</div>
                       <div class="col">room type</div>
                       <div class="col">share group</div>
                    </div>
                    <div v-for="traveller in alltravellers" class="row">
                        <input type="hidden" readonly :value="traveller.id">
                        <div class="col">
                            <input type="text" readonly :value="`${traveller.first_name} ${traveller.last_name}`">
                        </div>
                        <div class="col">
                            <select v-model="traveller.room_type">
                                <option default value="0">Select a room type</option>
                                <option v-for="room in room_types" :value="room.id" :key="room.id">{{room.name}}</option>
                            </select>
                        </div>
                        <div class="col">
                            <span v-if="occupancy(traveller.room_type) > 1">
                                <select v-model="traveller.group">
                                    <option default value="0">Share group selection</option>
                                    <option v-for="group in groups" :value="group.id" :key="group.id">{{group.name}}</option>
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
            leadTraveller: {},
            travellers: [],
            initTravelers: [],
            room_types: ['Single', 'Twin', 'Double', 'Shared'],
            room_type: {},
            groups: []
        }
    },
    created() {
        const that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
        })
        bus.$on('leadTravellerLoaded', t => that.leadTraveller = t)
        bus.$on("AdditionalTravelersLoaded", (travellers, init = false) => {
            that.debug>2 && console.log("Accommodation: travellers loaded", travellers, this.travellers, that.travellers);
            if (init) {
                that.travellers = [];
            }
            travellers.map(traveller => that.travellers.push(traveller));
            that.loadAccommodationBooking()
            that.initTravelers = that.travellers
        })
        bus.$on("AddedTraveler", traveler => {
            console.log('added traveller!', traveler)
            that.travellers.push(traveler)
            this.loadAccommodationBooking()
        })
        bus.$on("reloadTravelers", () => {
            console.log('reloadTraveller (accommodation)')
            this.loadAccommodationBooking()
        })
    },
    computed: {
        alltravellers: function() {
            const travellers = this.travellers
            travellers.unshift(this.leadTraveller)
            return travellers
        }
    },
    mounted() {
      this.getAccommodationOptions()
      this.getAccommodationGroups()
    },
    methods: {
        setAccommodation() {
          let that = this
          // booking the accommodation options in the booking_accommodations table
            this.travellers.map(t => {
              if (t.room_type === 1) {
                t.group = 0;
              }
            })
            this.debug>1 && console.log('BookingFormAccommodation: setAccommodation', this.booking_token, this.travellers)
            axios.post(`/api/booking/accommodation/reserve`, {
                token : this.booking_token,
                travellers :  this.travellers 
              })
              .then(response => {
                that.showAccommodation = false
                that.debug>3 && console.log('BookingFormAccommodation: accommodation reserve response', response)
                // TODO: do something with response?
              })
              .catch(error => console.log(error))
        },
        resetTravellers() {
            this.travellers = this.initTravelers
            this.travellers.map(t => Vue.set(t, 'group', '0'))
            this.travellers.map(t => Vue.set(t, 'room_type', '0'))
        },
        resetAccommodation() {
            this.resetTravellers()
            axios.post('/api/booking/accommodation/reset', {
                token: this.booking_token,
                travellers: this.travellers
              })
              .then(response => {
                  this.travellers = this.initTravelers
                  this.loadAccommodationBooking()
              })
              .catch(error => console.log(error))
        },
        occupancy(id) {
          const item = this.room_types.filter(type => type.id === id);
          const record = item.find(i => i.id === id)
          if (record) {
             return record.maximum_occupancy
          } else {
             return 1
          }
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
        },
        getAccommodationGroups() {
          const that = this
          axios.get('/api/booking/accommodation/groups')
              .then(response => that.groups = response.data.groups)
              .catch(error => console.log('error getting groups', error))
        },
        getAccommodationOptions() {
            const that = this
            const url = `/api/booking/accommodation/rooms/tour/${this.tour.id}`
            axios.get(url)
                .then(response => {
                    that.debug && console.log('BookingFormAccommodation: getAccommodationOptions: response: ', response)
                    that.room_types = response.data.rooms
                    that.debug>1 && console.log('BookingFormAccommodation: getAccommodationOptions: roomtypes: ', that.room_types)
                })
                .catch(error => console.log(error))
        },
        loadAccommodationBooking() {
            const that = this;
            let url = `/api/booking/accommodation/booking/${this.booking_token}/tour/${this.tour.id}`           
            axios.get(url)
                .then((response) => {
                    const bookings = response.data.bookings
                    if (bookings == undefined || !bookings.length) {
                      return
                    }
                    that.travellers = [], 

                    that.resetTravellers()

                    that.debug > 5 && console.log('BookingFormAccommodations: travellers', that.travellers, bookings)
                    bookings.map((booking, index) => {
                       that.debug>7 && console.log('BookingFormAccommodations: booking data debug ', index, booking.room_type_id, booking.group_id)
                       if (booking.room_type_id) {
                           Vue.set(that.travellers[index], 'room_type', booking.room_type_id)
                       }
                       Vue.set(that.travellers[index], 'group', booking.group_id)
                    })
                    that.debug>5 && console.log('BookingFormAccommodation: travellers', that.travellers, bookings)
                })
                .catch((error) => console.log(error));
        },
    }
}
</script>
<style scoped lang="scss">
</style>

