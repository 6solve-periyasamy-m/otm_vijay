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
                        <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                        <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                        <booking-form-lead :tour="tour" :booked="booked"></booking-form-lead>
                        <div v-if="tour && bookingToken && leadTraveller">
                            <booking-form-additional :tour="tour"></booking-form-additional>
                        </div>
                        {{leadTraveller}}
                        <div v-if="tour && bookingToken && leadTraveller">
                            {{tour}} {{bookingToken}} {{leadTraveller}}}
                            <booking-form-flights :tour="tour" :lead_traveller="leadTraveller"></booking-form-flights>
                            <booking-form-accommodation :tour="tour"></booking-form-accommodation>
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

function setCookie(cname, cvalue, exdays = 7) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function deleteCookie(name) {
  if (getCookie(name)) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    /*
    document.cookie = name + "=" +
      ((path) ? ";path="+path:"")+
      ((domain)?";domain="+domain:"") +
      ";expires=Thu, 01 Jan 1970 00:00:00 GMT";
    */
  }
}
export default {
    props: {
        tour: Object,
        event: Object,
        name: String
    },
    components: { BookingFormTour },
    data() {
        return {
            debug: 5,
            formInfo: false,
            bookingId: '',
            leadTraveller: null,
            home_address: {},
            billing_address: {},
            travellers: [],
            orders: [],
            order_id: 0,
            booked: {},
            selectOrder: [],
            order_selected: null,
            bookingToken: '',
            login: '',
            password: '',
            authenticated: false,
            tokenName: 'OTM_booking_token'
        }
    },
    async created() {
        let that = this
        this.debug && console.log('1) BookingForm created for tour:', this.tour)
        bus.$emit('debugOverride', this.debug)

        that.bookingToken = getCookie(that.tokenName); 
        this.debug && console.log('Cookie read:', that.bookingToken)

        if (typeof that.bookingToken != 'undefined' && that.bookingToken.length) {

            this.debug>1 && console.log('2) BookingOrderToken cookie was set', that.bookingToken)

            await axios.get(`/api/booking/token/${that.bookingToken}`)
                .then(response => {
                    that.debug && console.log('3) BookingForm: booking loaded from token', response)
                    if (response.data.success) {
                        that.leadTraveller = response.data.booking.customer
                    }
                    if (!response.data.success || !that.leadTraveller) {
                            console.log('>>> 4) booking token did not find a lead traveller', response)
                            that.bookingToken = null
                    } else {
                        bus.$emit('setBookingToken', response.data.booking.token)
                        // TODO: are these events really needed?
                        console.log('leadTravellerLoaded event emit', that.leadTraveller)
                        bus.$emit('leadTravellerLoaded', that.leadTraveller)
                        bus.$emit('homeAddressLoaded', response.data.booking.customer.home_address)
                        bus.$emit('billingAddressLoaded', response.data.booking.customer.billing_address)
                        // const customer = response.data.customer
                        // console.log('customer retrieved', customer)
                        // if (typeof customer === 'undefined' || customer == null || customer.length == 0) {
                        //     console.log('cookie found no customer. removing ', that.tokenName)
                        //     deleteCookie(that.tokenName)
                        //     that.authenticated = false
                        //     that.login = ''
                        // } else {
                        //     that.leadTraveller = customer
                        //     const home_address = response.data.home_address
                        //     const billing_address = response.data.billing_address
                        // console.log(response.data)
                        //     bus.$emit('setBookingToken', that.bookingToken)
                        //     bus.$emit('leadTravellerLoaded', that.leadTraveller)
                        //     bus.$emit('homeAddressLoaded', home_address)
                        //     if (customer.billing_address_id !== customer.home_address_id && customer.billing_address_id) {
                        //         console.log('BILLING loading...', customer, billing_address)
                        //         bus.$emit('billingAddressLoaded', billing_address)
                        //     }
                        //     that.login = that.leadTraveller.email_address
                        // }
                    }
                })
                .catch(error => {
                    console.log('error getting booking from token', error)
                })
        }
        if (that.bookingToken == undefined || that.bookingToken == null || that.bookingToken.length === 0) {
            that.bookingToken = Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2)
            console.log('>>>> 5) creating a random new booking token', that.bookingToken)
            setCookie('OTM_booking_token', that.bookingToken)
            bus.$emit('setBookingToken', that.bookingToken)
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
        }
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
