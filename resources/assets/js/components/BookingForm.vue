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
                                    <booking-form-lead :form_info="formInfo" :customer="customer" :order_id="order_id" :tour="tour" :booked="booked"></booking-form-lead>
                                    <booking-form-additional :form_info="formInfo" :order_id="order_id" :tour="tour"></booking-form-additional>
                                    <div v-if="tour">
                                        <booking-form-flights :token="token" :tour="tour" :booked="booked" :order_id="order_id"></booking-form-flights>
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
        let that = this
        this.debug && console.log('BookingForm created, tour:', this.tour)

        that.currentCustomer = getCookie('OTM_booking_order_token')
        if (typeof that.currentCustomer != 'undefined' && that.currentCustomer.length) {
            this.debug && console.log('this.currentCustomer', that.currentCustomer)
            await axios.get(`/api/booking/customer/${that.currentCustomer}`)
                .then(response => {
                    that.debug && console.log('>>>> customer orders found', response);
                    that.orders = response.data
                    that.debug && console.log('Orders = ', that.orders)
                    if (that.orders.length < 1) {
                        console.log('*** expired order cookie', that.currentCustomer)
                        that.order_id = null
                        //that.getOrderId()
                        alert('Your order appears to have expired, please rebook or contact us.')
                    } else {
                        if (that.orders.length > 1) {
                            that.selectOrder = that.orders
                        } else {
                            that.order_selected = that.orders[0].id
                        }
                        that.debug && console.log('order selected = ', that.order_selected, that.orders)
                        bus.$emit('customerLoaded', that.orders[0].customer, that.currentCustomer)
                        bus.$emit('additionalTravellersLoaded', that.orders[0].customers)
                        that.order_id = that.order_selected
                        that.token = that.orders[0].token
                    }
                })
                .catch(error => {
                    console.log('get current customer', error)
                })
        } else {
            console.log('currentCustomer detected', that.currentCustomer)
        }
        if (typeof this.orders == 'undefined' || !this.orders.length) {
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
            formInfo: false,
            token: '',
            travellers: [],
            orders: [],
            order_id: 0,
            booked: booking,
            selectOrder: [],
            order_selected: null,
            customer: {},
            currentCustomer: ''
        }
    },
    // created() {
    //     bus.$on('selectFlight', (data) => {
    //         this.flight = data
    //     })
    // },
    methods: {
        async getOrderId() {
            axios.post('/api/booking/create-order', {
                tour: this.tour.id,
                event: this.event.id
            })
            .then(response => {
                this.debug && console.log('bookingForm - create order_id', response)
                this.order_id = response.data.order.id
                this.token = response.data.order.token
                bus.$emit('setOrderToken', this.token)
                this.debug('EMIT setOrderToken', this.token)
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
