<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        OTM Booking Form version 0.5 PRERELEASE - Lead/Additionals/Flights/Accommodation
                    </div>
                    <bookingform-header :event="event" :tour="tour"></bookingform-header>
                    <div id="booking-form" class="card-body">
                            <div v-if="selectOrder.length>1 && order_selected === null">
                                <div class="orders-active">
                                    <h3>Active User</h3>
                                    <label for="email_address">Enter your email address</label>
                                    <input v-model="login">
                                    <input v-model="password">
                                    <div v-if="authenticated">
                                        <h4>Active Orders</h4>
                                        <select v-for="(order, key) in selectOrder" v-bind:key="key" v-model="order_selected">
                                            <option value="">Select an Order</option>
                                            <option :value="key">{{order.id}}</option>
                                        </select>
                                    </div>
                                    <div v-else>
                                        <h5>You need to login to access your active orders</h5>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                    <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                                    <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                                    <booking-form-lead :lead_traveller="leadTraveller" :home_address="home_address" :billing_address="billing_address" :booking_token="bookingOrderToken" :form_info="formInfo" :order_id="order_id" :tour="tour" :booked="booked"></booking-form-lead>
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
            debug: 4,
            formInfo: false,
            token: '',
            leadTraveller: {},
            home_address: {},
            billing_address: {},
            travellers: [],
            orders: [],
            order_id: 0,
            booked: {},
            selectOrder: [],
            order_selected: null,
            bookingOrderToken: '',
            login: '',
            password: '',
            authenticated: false,
            tokenId: 'OTM_booking_token'
        }
    },
    created() {
        let that = this
        this.debug && console.log('BookingForm created for tour:', this.tour)
        bus.$emit('debugOverride', this.debug)

        that.bookingOrderToken = getCookie(that.tokenId); 
        this.debug && console.log('Cookie read:', that.bookingOrderToken)
    
        if (typeof that.bookingOrderToken != 'undefined' && that.bookingOrderToken.length) {
            this.debug>1 && console.log('BookingOrderToken cookie found', that.bookingOrderToken)
            axios.get(`/api/booking/customer/${that.bookingOrderToken}`)
                .then(response => {
                    const customer = response.data.customer

                    console.log('customer retrieved', customer)
                    if (typeof customer === 'undefined' || customer == null || customer.length == 0) {
                        deleteCookie(that.tokenId)
                        that.authenticated = false
                        that.login = ''
                    } else {
                        that.leadTraveller = customer
                        const home_address = response.data.home_address
                        const billing_address = response.data.billing_address
console.log(response.data)
                        bus.$emit('setBookingToken', that.bookingOrderToken)
                        bus.$emit('leadTravellerLoaded', that.leadTraveller)
                        bus.$emit('homeAddressLoaded', home_address)

                        if (customer.billing_address_id !== customer.home_address_id && customer.billing_address_id) {
                            console.log('BILLING loading...', customer, billing_address)
                            bus.$emit('billingAddressLoaded', billing_address)
                        }
                        that.login = that.leadTraveller.email_address
                    }
                })
                .catch(error => {
                    console.log('get current customer', error)
                })
        } else {
            that.token = Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
            bus.$emit('setBookingToken', that.token)
            that.bookingOrderToken = getCookie(that.tokenId);
            console.log('bookingOrderToken CREATED ', that.bookingOrderToken)
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
        // TODO: do not create an order until we place an order at end of booking
        async createOrderId() {
            alert('createOrderId call!')
            return;
            
            let that = this
            axios.post('/api/booking/create-order', {
                tour: this.tour.id,
                event: this.event.id
            })
            .then(response => {
                that.debug && console.log('bookingForm - create order_id', response)
                that.order_id = response.data.order.id
                that.token = response.data.order.token
                bus.$emit('setBookingToken', that.token)
            })
            .catch(err => {
                console.log('error creating an order', err)
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
