<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="togglePayments">
                    Payment
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="paymentsActive">
            <div class="row">
                <div class="summary">
                    
                    <div class="travellers" v-for="traveller in booking.travellers" :key="traveller.customer_id">
                        {{traveller.customer.first_name}} {{traveller.customer.last_name}}
                    </div>
                    <h3>Accommodations</h3>
                    <div class="block accommodations" v-for="accommodation in booking.accommodations" :key="accommodation.accmoodation_inventory_tour_id">
               {{accommodation.customer_id}} {{accommodation.accommodation_name}} {{accommodation.room_type_name}} {{accommodation.board_type_name}}
                    </div>
                    <h3>Group Flights Booking</h3>
                    <div class="block flights" v-for="flight in booking.flights" :key="flight.id">
                        <div v-for="f in flight">
                            <div>{{f.flight_type}} {{f.travel_class}}</div>
                        </div>
                        <!-- <div v-for="(flight,name) in flight_type" key="flight.id">
                            <h4>{{name}}</h4>
                            {{flight.travel_class}} {{flight.flight_type}} {{flight.sales_price}}
                        </div> -->
                    </div>
                    <h3>Activities</h3>
                    <div class="block activities" v-for="activity in booking.activities" :key="activity.id">
                        {{activity.name}} {{activity.ticket_type_name}}
                        Starts {{formatDate(activity.starts_at)}} Ends {{formatDate(activity.ends_at)}} 
                        <br>
                        {{activity.description}}
                    </div>
                    <h3>Transports</h3>
                    <div class="block transports" v-for="transport in booking.transports" :key="transport.id">
                        {{transport.is_domestic ? 'Domestic' : 'International'}} {{transport.name }}
                        <br>
                        {{transport.departs_from}} {{transport.departure_address}}
                        <br>
                        {{formatDate(transport.arrives_at)}} {{transport.arrival_address}}
                    </div>

                </div>
            </div>
            <div class="row">
    
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "axios"
import { bus } from '../bus'
import dates from '../utilities'
export default {
    props: ['tour'],
    data() {
        return {
            moduleName: 'Payments',
            booking_token: null,
            debug: 3,
            paymentsActive: false,
            booking: {}
        }    
    },
    created() {
        let that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module, booking ${that.booking_token}`)
            that.loadBooking(that.booking_token)
        })
        bus.$on("ReloadBooking", (token) => {
            this.debug>2 && console.log(">>>> Payment: booking reloaded", token);
            if (this.booking_token != undefined && this.booking_token.length && this.booking_token === token) {
                this.loadBooking(this.booking_token)
            } else {
                console.log('Payment ignored: ', token);
            }
        })
    },
    mounted() {

    },
    methods: {
        formatDate(s) {
            return dates.makeDateFromString(s)
        },
        togglePayments() {
            this.paymentsActive = !this.paymentsActive
        },
        loadPaymentSchedule() {
            let that = this
            axios.get(`/api/bookings/payment-schedules`)
                .then(response => {
                    that.paymentSchedule = response.data.paymentSchedule
                })
                .catch(error => {
                    console.log(error)
                })
        },
        loadBooking(token) {
            let that = this
            axios.get(`/api/booking/summary/${token}/gather`)
                .then(response => {
                    console.log('>>>> booking data ', response.data)
                    that.booking = response.data.booking
                    // that.booking.accommodation = response.data.accommodation
                    // that.booking.customer = response.data.customer
                    // that.booking.travellers = response.data.travellers
                    // that.booking.flights = response.data.flights
                    // that.booking.activities = response.data.activities
                    // that.booking.transports = response.data.transports
                    
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }

}
</script>

<style scoped lang="scss">
.block {
    margin-bottom: 1rem;
}
</style>