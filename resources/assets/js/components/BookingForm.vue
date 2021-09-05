<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        OTM Booking Form version 0.4.2 PRERELEASE - Lead/Additionals/Flights/Accommodation
                        <button class="btn btn-small btn-themed default" @click="changeTheme('')">None</button>
                        <button class="btn btn-small btn-themed cool" @click="changeTheme('cool')">Cool</button>
                        <button class="btn btn-small btn-themed warm" @click="changeTheme('warm')">Warm</button>
                        <button class="btn btn-small btn-themed action" @click="changeTheme('action')">Action</button>
                    </div>
                    <bookingform-header :event="event" :tour="tour"></bookingform-header>
                    <div id="booking-form" class="card-body">
                            <div v-if="selectOrder.length>1 && order_selected === null">
                                <select v-for="(order, key) in selectOrder" v-bind:key="key" v-model="order_selected">
                                    <option value="">Select an Order</option>
                                    <option :value="key">{{order.id}}</option>
                                </select>
                            </div>
                            <div v-else>
                                    <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                                    <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                                    <booking-form-lead :form_info="formInfo" :order_id="order_id" :tour="tour" :booked="booked"></booking-form-lead>
                                    <booking-form-additional :form_info="formInfo" :order_id="order_id" :tour="tour"></booking-form-additional>
                                    <div v-if="tour && token">
                                        <booking-form-flights :leadTraveller="leadTraveller" :travellers="travellers" :token="token" :tour="tour" :order_id="order_id"></booking-form-flights>
                                        <booking-form-accommodation :travellers="travellers" :order_token="token" :tour="tour" :order_id="order_id"></booking-form-accommodation>
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
            debug: false,
            formInfo: false,
            token: '',
            leadTraveller: {},
            travellers: [],
            orders: [],
            order_id: 0,
            booked: {},
            selectOrder: [],
            order_selected: null,
            bookingOrderToken: ''
        }
    },
    created() {
        let that = this
        this.debug && console.log('BookingForm created for tour:', this.tour, this.order_id, this.order_id.length)
        bus.$emit('debugOverride', this.debug)

        that.bookingOrderToken = getCookie('OTM_booking_order_token')
        this.debug && console.log('Cookie read:', that.bookingOrderToken)
        if (typeof that.bookingOrderToken != 'undefined' && that.bookingOrderToken.length) {
            this.debug>1 && console.log('BookingOrderToken cookie found', that.bookingOrderToken)
            axios.get(`/api/booking/customer/${that.bookingOrderToken}`)
                .then(response => {
                    that.orderData = response.data.orders
                    that.debug>2 && console.log('Found booking orders = ', that.orderData)
                    if (typeof that.orderData === 'undefined' || that.orderData == null || that.orderData.length == 0) {
                        deleteCookie('OTM_booking_order_token')
                        that.order_id = null
                        that.createOrderId();
                        console.log('bft=',that.bookingOrderToken)
                        //bus.$on('createOrder', that.createOrderId());
                        alert('No orders found for your access code, please rebook or contact us.')
                    } else {
                        that.order_selected = that.orderData.id
                        that.order_id = that.order_selected
                        that.token = that.orderData.token
                        that.debug>1 && 
                            console.log('Booking '+that.token+' continuing with current order selected = ',
                            'order_selected='+that.order_selected, 
                            that.orderData)
                        that.debug>3 && console.log('BookingForm emit setOrderToken', that.token)
                        bus.$emit('setOrderToken', that.token, that.order_id)
                        //bus.$emit('customerLoaded', that.orderData.customer, that.orderData.token)
                        bus.$emit('leadTravellerLoaded', that.orderData.customer, that.orderData.token)
                        bus.$emit('additionalTravellersLoaded', that.orderData.customers)
                        that.leadTraveller = that.orderData.customer
                        that.travellers = that.orderData.customers
                    }
                })
                .catch(error => {
                    console.log('get current customer', error)
                })
        } else {
            that.order_id = null
            that.createOrderId()
            console.log('bookingOrderToken NOT detected CREATED ', that.bookingOrderToken)
        }
    },
    mounted() {
        let that = this

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
        async createOrderId() {
            let that = this
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
