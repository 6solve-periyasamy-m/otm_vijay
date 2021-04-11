<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">OTM Booking Form version 0.2.0 PRERELEASE - Lead/Additional/Flights/Custom Flights</div>
                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <h1 v-if="event != null">{{event.event_title}}</h1>
                            <h2 v-if="tour != null">{{tour.title}} From {{ startDate(event) }} To {{endDate(event) }}</h2>
                            <div v-if="selectOrder.length>1 && order_selected === null">
                                <select v-for="(order, key) in selectOrder" v-bind:key="key" v-model="order_selected" >
                                    <option value="">Select an Order</option>
                                    <option :value="key">{{order.id}}</option>
                                </select>
                            </div>
                            <div v-else>
                                    <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                                    <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                                    <booking-form-lead :customer="customer" :order_id="order_id" :tour="tour" :booked="booked"></booking-form-lead>
                                    <booking-form-additional :order_id="order_id" :tour="tour"></booking-form-additional>
                                    <div v-if="tour">
                                        <booking-form-flights :tour="tour" :booked="booked" :order_id="order_id"></booking-form-flights>
                                        <booking-form-accommodation></booking-form-accommodation>
                                        <booking-form-payment></booking-form-payment>
                                        <booking-form-terms></booking-form-terms>
                                    </div>
                            </div>
                            <bookingform-footer></bookingform-footer>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BookingFormTour from './BookingFormTour.vue'
import dates from '../utilities'
import { bus, booking } from '../bus'
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
export default {
    props: ['tour', 'event', 'name'],
    components: { BookingFormTour },
    async created() {
        
        console.log('BookingForm mounted.')
        this.debug && console.log('>>>> tour', this.tour)

        let currentCustomer = getCookie('OTM_booking_order_token')
        if (typeof currentCustomer != 'undefined' && currentCustomer.length) {
            this.debug && console.log('currentCustomer', currentCustomer)
            await axios.get(`/api/booking/customer/${currentCustomer}`)
                .then(response => {
                    this.debug && console.log('customer orders found', response);
                    this.orders = response.data
                    this.debug && console.log('Orders = ', this.orders)
                    if (this.orders.length > 1) {
                        this.selectOrder = this.orders
                    } else {
                        this.order_selected = this.orders[0].id
                    }
                    this.debug && console.log('order selected is ', this.order_selected)
                    bus.$emit('customerLoaded', this.orders[0].customer)
                })
                .catch(error => {
                    console.log(error)
                })
        } else if (typeof orders == 'undefined' || !orders.length) {
            this.getOrderId();
            this.debug && console.log('created order = ', this.orders)
        }
    },
    // watch: {
    //     order_selected: function() {
    //         this.customer = this.orders[this.order_selected].customer
    //         this.debug && console.log('An order has been selected', this.order_selected)
    //         this.debug && console.log('this order has a customer', this.customer)
    //     }
    // },
    data() {
        return {
            debug: true,
            token: '',
            travellers: [],
            orders: [],
            order_id: 0,
            booked: booking,
            selectOrder: [],
            order_selected: null,
            customer: {}
        }
    },
    // created() {
    //     bus.$on('selectFlight', (data) => {
    //         this.flight = data
    //     })
    // },
    methods: {
        getOrderId() {
            axios.post('/api/booking/create-order', {
                tour: this.tour.id,
                event: this.event.id
            })
            .then(response => {
                this.debug && console.log('bookingForm - create order_id', response)
                this.order_id = response.data.order.id
                this.token = response.data.order.token
                bus.$emit('setOrderToken', this.token)
                this.debug && console.log(response)
            })
            .catch(err => {
                console.log('error creating an order', e)
            })
        },
        startDate(event) {
            this.debug && console.log(event.event_start_date)
            return dates.makeDateFromString(event.event_start_date)
        },
        endDate(event) {
            return dates.makeDateFromString(event.event_end_date)
        },
    }
}
</script>
