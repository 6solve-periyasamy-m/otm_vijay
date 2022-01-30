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
                <div class="price">
                    Accommodation
                </div>
                <div class="price_amount">
                    {{priceFormat(totals.accommodations)}} x {{travellers}} = {{priceFormat(travellers * totals.accommodations)}}
                </div>
            </div>
            <div class="row">
                <div class="price">
                    Flights
                </div>
                <div class="price_amount">
                    {{priceFormat(totals.flights)}} x {{travellers}} = {{priceFormat(travellers * totals.flights)}}
                </div>
            </div>
            <div class="row">
                <div class="price">
                    Activities
                </div>
                <div class="price_amount">
                    {{priceFormat(totals.activities)}} x {{travellers}} = {{priceFormat(travellers * totals.activities)}}
                </div>
            </div>
            <div class="row">
                <div class="price">
                    Transport
                </div>
                <div class="price_amount">
                    {{priceFormat(totals.transports)}} x {{travellers}} = {{priceFormat(travellers * totals.transports)}}
                </div>

            </div>
            <div class="row">
                <div class="price">
                    Tour Price total
                </div>
                <div class="price_amount">
                    {{priceFormat(totalPrice)}} x {{travellers}} = {{priceFormat(travellers * totalPrice)}}
                </div>
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
    props: ['tour', 'systemCurrency'],
    data() {
        return {
            moduleName: 'Payments',
            currency: this.systemCurrency || 'GBP',
            booking_token: null,
            debug: 3,
            paymentsActive: false,
            booking: {},
            totals: {},
            totalPrice: 0
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
                this.calcPrice()
            } else {
                console.log('Payment ignored: ', token);
            }
        })
        console.log('payment created')
    },
    mounted() {
        console.log('payment mounted')
        this.loadBooking(this.booking_token)
    },
    computed: {
        travellers: function() {
            return this.booking.travellers.length
        }
    },
    methods: {
        priceFormat(a) {
            const currency = this.currency 
            // Create our number formatter.
            let formatter = new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: currency
            })
            return formatter.format(a)
  // These options are needed to round to whole numbers if that's what you want.
  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
  //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
        },
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
        calcPrice() {
            let price = 0
            this.totals = {accommodations:0, flights:0, activities:0, transports:0}
            this.totalPrice = 0
            console.log('calcPrice', this.booking)
            if (this.booking.accommodations != undefined) {
                this.booking.accommodations.map(a => {
                    if (a.tour_sales_price > 0) {
                        price += a.tour_sales_price
                    } else {
                        price += a.sales_price
                    }
                })
            }
            this.totals.accommodations = price
            this.totalPrice += price

            price = 0
            if (this.booking.flights != undefined) {
                this.booking.flights.inbound.map(f => {
                    if (f.tour_sales_price > 0) {
                        price += f.tour_sales_price
                    } else {
                        price += f.sales_price
                    }
                })
                this.booking.flights.outbound.map(f => {
                    if (f.tour_sales_price > 0) {
                        price += f.tour_sales_price
                    } else {
                        price += f.sales_price
                    }
                })
            }
            this.totals.flights = price
            this.totalPrice += price

            price = 0
            if (this.booking.activities != undefined) {
                this.booking.activities.map(a => {
                    if (a.tour_sales_price > 0) {
                        price += a.tour_sales_price
                    } else {
                        price += a.sales_price
                    }
                })
            }
            this.totals.activities = price
            this.totalPrice += price
            price = 0
            if (this.booking.transports != undefined) {
                this.booking.accommodations.map(a => {
                    if (a.tour_sales_price > 0) {
                        price += a.tour_sales_price
                    } else {
                        price += a.sales_price
                    }
                })
            }
            this.totals.transports += price
            this.totalPrice += price
        },
        loadBooking(token) {
            let that = this
            axios.get(`/api/booking/summary/${token}/gather`)
                .then(response => {
                    console.log('>>>> booking data ', response.data)
                    that.booking = response.data.booking
                    that.calcPrice()
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
.price {
    width: 20rem;
    margin-left: 10rem;
}
.price_amount {
    width: 20rem;
    text-align: right;
}
</style>
