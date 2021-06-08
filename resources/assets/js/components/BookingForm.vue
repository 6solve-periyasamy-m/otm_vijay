<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        OTM Booking Form version 0.4.0 PRERELEASE - Lead/Additionals/Flights/Accommodation
                        <button class="btn btn-small btn-themed default" @click="changeTheme('')">None</button>
                        <button class="btn btn-small btn-themed cool" @click="changeTheme('cool')">Cool</button>
                        <button class="btn btn-small btn-themed warm" @click="changeTheme('warm')">Warm</button>
                        <button class="btn btn-small btn-themed action" @click="changeTheme('action')">Action</button>
                    </div>
                    <bookingform-header :event="event" :tour="tour"></bookingform-header>
                    <div id="booking-form" class="card-body">
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
function deleteCookie( name, path, domain ) {
  if (getCookie(name) ) {
      console.log('delte cookie found ', name)
    document.cookie = name + "=" +
      ((path) ? ";path="+path:"")+
      ((domain)?";domain="+domain:"") +
      ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
  }
}
export default {
    props: ['tour', 'event', 'name'],
    components: { BookingFormTour },
    data() {
        return {
            debug: 6,
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
        this.debug && console.log('BookingForm created for tour:', this.tour, this.order_id, this.order_id.length)
        bus.$emit('debugOverride', this.debug)

        that.bookingOrderToken = getCookie('OTM_booking_order_token')
        console.log('Cookie read:', that.bookingOrderToken)
        if (typeof that.bookingOrderToken != 'undefined' && that.bookingOrderToken.length) {
            this.debug>3 && console.log('BookingOrderToken', that.bookingOrderToken)
            await axios.get(`/api/booking/customer/${that.bookingOrderToken}`)
                .then(response => {
                    that.orders = response.data.orders
                    that.debug>1 && console.log('Orders = ', that.orders)
                    if (typeof that.orders === 'undefined') {
                        deleteCookie('OTM_booking_order_token')
                        that.order_id = null
                        that.getOrderId()
                        alert('Your order appears to have expired, please rebook or contact us.')
                    } else {
                        that.order_selected = that.orders.id
                        bus.$emit('customerLoaded', that.orders.customer, that.bookingOrderToken)
                        bus.$emit('additionalTravellersLoaded', that.orders.customers)
                        that.order_id = that.order_selected
                        that.token = that.orders.token
                        bus.$emit('setOrderToken', that.token, that.order_id)

                        that.debug && console.log('order selected = ', that.order_selected, that.orders)
                        that.debug>3 && console.log('BookingForm set token', that.token)
                    }
                })
                .catch(error => {
                    console.log('get current customer', error)
                })
        } else {
            console.log('bookingOrderToken NOT detected', that.bookingOrderToken)
        }
    },
    methods: {
        changeTheme(theme) {
            const bookingForm = document.querySelector('#booking-form')
            bookingForm.classList.remove('cool-theme')
            bookingForm.classList.remove('warm-theme')
            bookingForm.classList.remove('action-theme')
            switch (theme) {
                case 'cool':
                    bookingForm.classList.add('cool-theme')
                    break;
                case 'warm':
                    bookingForm.classList.add('warm-theme')
                    break;
                case 'action':
                    bookingForm.classList.add('action-theme')
                    break;

                default: 
                    break;
            }
        },
        async getOrderId() {
            let that = this
            console.log('BOOKING FORM: getOrderId call') 
            axios.post('/api/booking/create-order', {
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
    }
}
</script>
<style scoped>
    .cool-theme {
        --payment-button-color: #007bff;
        --card-background: #c2e2c5;
        --card-body-background: #72a7c2;
        --booking-form-background: #e1e7c9;
    }
    .warm-theme {
        --payment-button-color: #007bff;
        --card-background: #ff7d7d;
        --card-body-background: #fdde88;
        --booking-form-background: #ff9c2b;
    }
    .action-theme {
        --payment-button-color: #007bff;
        --card-background: #ffaf04;
        --card-body-background: #4281ff;
        --booking-form-background: #ffffff;
    }
    button.btn-themed.cool {
        background: #7efafa;
    }
    button.btn-themed.warm {
        background: #f38181;
    }
    button.btn-themed.action {
        background: #2c89f3;
    }

</style>
