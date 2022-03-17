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
                <div v-if="showSummary" class="summary">
                    <div v-if="booking != undefined">
                        <div v-if="booking.travellers != undefined">
                            <div class="travellers" v-for="traveller in booking.travellers" :key="traveller.customer_id">
                                {{traveller.customer.first_name}} {{traveller.customer.last_name}}
                            </div>
                        </div>
                        <div v-if="booking.accommodation != undefined">
                            <h3>Accommodations</h3>
                            <div class="block accommodations" v-for="accommodation in booking.accommodations" :key="accommodation.accmoodation_inventory_tour_id">
                              {{accommodation.customer_id}} {{accommodation.accommodation_name}} {{accommodation.room_type_name}} {{accommodation.board_type_name}}
                            </div>
                        </div>
                        <div v-if="booking.flights != undefined">
                            <h3>Group Flights Booking</h3>
                            <div class="block flights" v-for="flight in booking.flights" :key="flight.id">
                                 <div v-for="f in flight">
                                    {{f.flight_type}} {{f.travel_class}} {{f.tour_component_type}} {{priceFormat(f.sales_price)}}
                                </div>
                            </div>
                        </div>
                        <div v-if="booking.activities != undefined">
                            <h3>Activities</h3>
                            <div class="block activities" v-for="activity in booking.activities" :key="activity.id">
                                {{activity.name}} {{activity.ticket_type_name}}
                                Starts {{formatDate(activity.starts_at)}} Ends {{formatDate(activity.ends_at)}} 
                                <br>
                                {{activity.description}}
                            </div>
                        </div>
                        <div v-if="booking.transports != undefined">
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
                </div>
            </div>
            <div v-if="priceBreakdown">
                <div class="row">
                    <div class="price">
                        Accommodation
                    </div>
                    <div class="price_amount">
                        {{priceFormat(totals.accommodations)}} x {{travellerCount}} = {{priceFormat(travellerCount * totals.accommodations)}}
                    </div>
                </div>
                <div class="row">
                    <div class="price">
                        Flights
                    </div>
                    <div class="price_amount">
                        {{priceFormat(totals.flights)}} x {{travellerCount}} = {{priceFormat(travellerCount * totals.flights)}}
                    </div>
                </div>
                <div class="row">
                    <div class="price">
                        Activities
                    </div>
                    <div class="price_amount">
                        {{priceFormat(totals.activities)}} x {{travellerCount}} = {{priceFormat(travellerCount * totals.activities)}}
                    </div>
                </div>
                <div class="row">
                    <div class="price">
                        Transport
                    </div>
                    <div class="price_amount">
                        {{priceFormat(totals.transports)}} x {{(travellerCount)}} = {{priceFormat(travellerCount * totals.transports)}}
                    </div>
                </div>
                <div class="total row">
                    <div class="price">
                        Tour Costs total
                    </div>
                    <div class="price_amount">
                        {{priceFormat(totalPrice)}} x {{travellerCount}} = {{priceFormat((travellerCount) * totalPrice)}}
                    </div>
                </div>
            </div>
            <div class="charges-summary">
                <div class="summary row">
                    <div class="price">
                        Tour Price
                    </div>
                    <div class="price_amount">
                        {{priceFormat(tourPrice)}} x {{travellerCount}} = {{priceFormat(calculateTourPrice)}}
                    </div>
                </div>
                <div class="summary row" v-if="calculateSingleRooms">
                    <div class="price">
                        Single Room Surcharge
                    </div>
                    <div class="price_amount">
                        {{priceFormat(singleOccupancySurcharge)}} x {{calculateSingleRooms}} = {{priceFormat(calculateSingleRooms * singleOccupancySurcharge)}}
                    </div>
                </div>
                <div class="summary row">
                    <div class="price">
                        Total 
                    </div>
                    <div class="price_amount">
                       {{priceFormat(totalPrice)}} 
                    </div>
                </div>
                <div class="summary row">
                    <div class="price">
                        Deposit 
                    </div>
                    <div class="price_amount">
                       {{priceFormat(deposit * (travellerCount))}} 
                    </div>
                </div>

            </div>
            <div class="row">
              <hr>
              <div class="deposit">
                 <div v-if="deposit == 0" class="deposit-amount">
                     <p>To place your order you must agree to our <a href="/termsandconditions" target="_blank">Terms and Conditions</a>.</p>
                     <p>I have read and agree to the full terms and conditions and wish to make a deposit to confirm my order.</p>
                     <input type="checkbox" v-model="agreement" name="agreement">
                     <button :disabled="!agreement" @click="calcDeposit" class="btn btn-primary">Confirm</button> 
                 </div>
                 <div v-if="agreement && deposit>0" class="deposit-amount">
                    <p>You have agreed to our Terms and Conditions.</p>
                    <p>To book your tour, a deposit of {{priceFormat(deposit * travellerCount)}} is now payable.</p>
                    <form method="post" action="/booking/deposit/payment">
                      <input type="hidden" name="_token" :value="csrf_token" />
                      <input type="hidden" name="token" :value="booking_token" />
                      <input type="text" name="amount" readonly :value="priceFormat(deposit * travellerCount)" />
                      <input type="submit" class="btn btn-primary" value="Pay Deposit" />
                    </form>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "axios"
import Vue from "vue";
import { bus } from '../bus'
import dates from '../utilities'
let csrf = document.querySelector('meta[name="csrf-token"]').content;
export default {
    props: ['tour', 'systemCurrency'],
    data() {
        return {
            debug: 9,
            moduleName: 'PaymentModule',
            currency: this.systemCurrency || 'GBP',
            booking_token: null,
            paymentsActive: true,
            booking: {},
            totals: {},
            deposit: 0,
            agreement: false,
            totalCosts: 0,
            csrf_token: '',
            showSummary: true,
            priceBreakdown: false,
            tourPrice: 0,
            travellers: [],
            singleOccupancySurcharge: 100,
            singleRooms: 0,
            deposit: 0,
            travellerCount: 0
        }    
    },
    created() {
        let that = this
        this.csrf_token = csrf
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`${that.moduleName} module, booking ${that.booking_token}`)
            that.loadBooking(that.booking_token)
        })
        bus.$on("ReloadBooking", (token) => {
            this.debug>2 && console.log("Payment: booking reloaded", token);
            if (this.booking_token != undefined && this.booking_token.length && this.booking_token === token) {
                this.loadBooking(this.booking_token)
            } else {
                console.log('Payment ignored: ', token);
            }
        })
         bus.$on("AdditionalTravelersLoaded", (travellers, init = false) => {
            this.debug>2 && console.log("Payment : travellers loaded", travellers, this.travellers, that.travellers);
            travellers.map(traveller => this.travellers.push(traveller));
            that.countTravellers()
        })
        bus.$on("TermsAgreed", (agreed) => {
          this.agreement = agreed
        })
    },
    mounted() {
        
        console.log('BookingFormPayment form mounted travellers:', this.travellerCount);
        
    },
    computed: {
        calculateTourPrice: function() {
          return this.tourPrice * this.travellerCount
        },
        totalPrice: function() {
          return this.calculateTourPrice + this.calculateSingleRooms * this.singleOccupancySurcharge;
        }, 
        calculateSingleRooms: function() {
            let rooms = 0
            this.travellers.map(t => {
              if (t.room_type === 1) {
                rooms++;
              }
            })
            return rooms
        },
        countBookingTravellers: function() {
            if (typeof this.booking.travellers !== 'undefined') {
              return this.booking.travellers.length
            } else {
              // this should not happen but to detect if there is a problem with this function
              console.log('ERROR: computed travellers counter does not have travellers in this booking: ',this.booking)
              return 1
            }
        }
    },
    methods: {
        countTravellers() {
            console.log('counting travellers', this.travellers.length + 1)
            this.travellerCount = this.travellers.length + 1
            return this.travellerCount
        },
        priceFormat(a) {
            const currency = this.currency
            let formatter = new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
            return formatter.format(a)
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
            this.totalCosts = 0
            // if the booking has not been received yet, do nothing yet
            if (this.booking == undefined) {
              return
            }
            if (typeof this.booking.accommodations !== 'undefined' && this.booking.accommodations !== null) {
                this.booking.accommodations.map(a => {
                    const tourSalesPrice = parseFloat(a.tour_sales_price)
                    if (tourSalesPrice > 0) {
                        price += tourSalesPrice
                    } else {
                        price += parseFloat(a.sales_price)
                    }
                })
            } else {
                console.log('BookingFormPrice: calcPrice() unexpected condition', this.booking.accommodations)
            }
            this.totals.accommodations = price
            this.totalCosts += price

            price = 0
            if (this.booking.flights != undefined) {
                this.booking.flights.inbound.map(f => {
                    const tourSalesPrice = parseFloat(f.tour_sales_price)
                    if (tourSalesPrice > 0) {
                        price += tourSalesPrice
                    } else {
                        price += parseFloat(f.sales_price)
                    }
                })
                this.booking.flights.outbound.map(f => {
                    const tourSalesPrice = parseFloat(f.tour_sales_price)
                    if (tourSalesPrice > 0) {
                        price += tourSalesPrice
                    } else {
                        price += parseFloat(f.sales_price)
                    }
                })
            }
            this.totals.flights = price
            this.totalCosts += price

            price = 0
            if (this.booking.activities != undefined) {
                this.booking.activities.map(a => {

                    const tourSalesPrice = parseFloat(a.tour_sales_price)
                    if (tourSalesPrice > 0) {
                        price += tourSalesPrice
                    } else {
                        price += parseFloat(a.sales_price)
                    }
                })
            }
            this.totals.activities = price
            this.totalCosts += price

            price = 0
            if (this.booking.transports != undefined) {
                this.booking.transports.map(t => {
                    const tourSalesPrice = parseFloat(t.tour_sales_price)
                    if (tourSalesPrice > 0) {
                        price += tourSalesPrice
                    } else {
                        price += parseFloat(t.sales_price)
                    }
                })
            }
            this.totals.transports += price
            this.totalCosts += price
        },
        calcTourPrice() {
            let that = this
            this.debug>1 && console.log('BookingFormPayment: calcTourPrice: tour', this.tour.name);
            axios.get(`/api/booking/tour/price/${this.tour.id}`)
                .then(response => {
                  that.debug>1 && console.log('>>>>>> BookingFormPayment: tour price data', response)
                  that.tourPrice = parseFloat(response.data.tour_price)
                  that.singleOccupancySurcharge = parseFloat(response.data.single_occupancy_surcharge)
                  that.deposit = parseFloat(response.data.deposit)
                  console.log('Tour Price loaded', that.tourPrice)
                })
                .catch(error => console.log('error getting tour price', error))
        },
        calcDeposit() {
            let that = this
            axios.post('/api/booking/deposit/calculate', {
                tour: this.tour,
                token: this.booking_token
            })
            .then(response => {
                const success = response.data.success
                if (success) {
                  const data = response.data
                  that.deposit = data.deposit
                } else {
                  that.deposit = 'please retry'
                }
            })
            .catch(error => {
                console.log(error)
            })
        },
        loadBooking(token) {
            let that = this
            if (token == undefined) {
              console.log('BookingFormPayment: ERROR: payment stage has no token')
              return
            }
            axios.get(`/api/booking/customer/${token}`)
                .then(response => {
                    that.debug>1 && console.log('BookingFormPrice get customer response:', response)
                    that.leadTraveller = response.data.customer
                    console.log('BookingFormPayment: gathering summary data for ', token)
                    axios.get(`/api/booking/summary/${token}/gather`)
                        .then(response => {
                            that.booking = response.data.booking
                            console.log('BookingPrice:', that.booking)
                            that.countTravellers()
                            that.calcPrice()
                            that.tourPrice = 9999.99
                            that.calcTourPrice()
                        })
                        .catch(error => {
                            console.log(error)
                        })
                    .catch(error => {
                        console.log(error)
                    })
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
.total {
    font-size: large;
    font-weight: bold;
    margin-top: 2rem;
}
</style>
