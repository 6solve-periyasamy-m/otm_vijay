<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">OTM Booking Form version 0.2.0 PRERELEASE - Lead/Additional/Flights/Custom Flights</div>
                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <h1 v-if="event != null">{{event.name}}</h1>
                            <h2 v-if="tour != null">{{tour.name}} From {{ startDate(event) }} To {{endDate(event) }}</h2>
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
                                        <booking-form-flights :order_token="token" :tour="tour" :order_id="order_id"></booking-form-flights>
                                        <booking-form-accommodation :order_token="token" :tour="tour" :order_id="order_id"></booking-form-accommodation>
                                        <booking-form-activity></booking-form-activity>
                                        <booking-form-transport></booking-form-transport>
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
import { bus } from '../bus'
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
    data() {
        return {
            debug: true,
            formInfo: false,
            token: '',
            travellers: [],
            orders: [],
            order_id: 0,
            booked: {},
            selectOrder: [],
            order_selected: null,
            customer: {},
            bookingOrderToken: ''
        }
    },
    async created() {
        let that = this
        this.debug && console.log('BookingForm created, tour:', this.tour)
        bus.$emit('debugOverride', this.debug)

        that.bookingOrderToken = getCookie('OTM_booking_order_token')
        if (typeof that.bookingOrderToken != 'undefined' && that.bookingOrderToken.length) {
            this.debug && console.log('bookingOrderToken', that.bookingOrderToken)
            await axios.get(`/api/booking/customer/${that.bookingOrderToken}`)
                .then(response => {
                    that.debug && console.log('>>>> customer orders found', response)
                    that.orders = response.data
                    that.debug && console.log('Orders = ', that.orders)
                    if (that.orders.length < 1) {
                        console.log('*** expired order cookie', that.bookingOrderToken)
                        that.order_id = null
                        that.getOrderId()
                        alert('Your order appears to have expired, please rebook or contact us.')
                    } else {
                        if (that.orders.length > 1) {
                            that.selectOrder = that.orders
                        } else {
                            that.order_selected = that.orders[0].id
                        }
                        that.debug && console.log('order selected = ', that.order_selected, that.orders)
                        bus.$emit('customerLoaded', that.orders[0].customer, that.bookingOrderToken)
                        bus.$emit('additionalTravellersLoaded', that.orders[0].customers)
                        that.order_id = that.order_selected
                        that.token = that.orders[0].token
                        that.debug && console.log('^^^^^ BookingForm set token & order_id', that.token, that.order_id)
                        bus.$emit('setOrderToken', that.token, that.order_id)
                    }
                })
                .catch(error => {
                    console.log('get current customer', error)
                })
        } else {
            console.log('bookingOrderToken NOT detected', that.bookingOrderToken)
        }
        // if (typeof this.orders == 'undefined' || !this.orders.length) {
        //     this.getOrderId();
        //     this.debug && console.log('created order = ', this.orders)
        // } else {
        //     this.debug && console.log('BOOKING FORM existing order = ', this.orders)
        // }
    },
    methods: {
        async getOrderId() {
            let that = this
            console.log('BOOKING FORM: getOrderId call') 
            await axios.post('/api/booking/create-order', {
                tour: this.tour.id,
                event: this.event.id
            })
            .then(response => {
                that.debug && console.log('bookingForm - create order_id', response)
                that.order_id = response.data.order.id
                that.token = response.data.order.token
                bus.$emit('setOrderToken', that.token, that.order_id)
            })
            .catch(err => {
                console.log('error creating an order', e)
            })
        },
        startDate(event) {
            return dates.makeDateFromString(event.starts_at)
        },
        endDate(event) {
            return dates.makeDateFromString(event.ends_at)
        },
    }
}
</script>
