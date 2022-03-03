<template>
    <div class="booking-form">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default card-container">
                    <div class="card-header bookingform-header">
                        <div> OTM Booking Form pre-release version 0.84</div>
                        <bookingform-control :token_label="tokenName"></bookingform-control>
                    </div>
                    <bookingform-header :event="event" :tour="tour"></bookingform-header>
                    <div id="booking-form" class="card-body">
                        <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                        <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                        <booking-form-lead :tour="tour" :booked="booked"></booking-form-lead>
                        <div v-if="tour && bookingToken">
                            <booking-form-additional :tour="tour"></booking-form-additional>
                            <booking-form-flights :tour="tour"></booking-form-flights>
                            <booking-form-accommodation :tour="tour"></booking-form-accommodation>
                            <booking-form-activity :tour="tour"></booking-form-activity>
                            <booking-form-transport :tour="tour"></booking-form-transport>
                            <booking-form-terms :tour="tour"></booking-form-terms>
                            <booking-form-payment :tour="tour" v-show="termsaccepted"></booking-form-payment>
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
import { setCookie, getCookie, deleteCookie } from '../cookies'

export default {
    props: {
        auth_user: Object,
        tour: Object,
        event: Object,
        name: String
    },
    components: { BookingFormTour },
    data() {
        return {
            debug: 9,
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
            tokenName: 'OTM_booking_token',
            termsaccepted: false
        }
    },
    created() {
        let that = this

        this.debug && console.log('1) BookingForm created for tour:', this.tour)
        bus.$emit('debugOverride', this.debug)
        bus.$on('initialiseForm', () => {
            this.resetToken()
            window.location.reload(true)
        })
        bus.$on('removeBookingCookie', token => {
            deleteCookie(that.tokenName)
            alert('Booking form clearance')
        })

        bus.$on('setLeadTraveller', customer => {
            that.leadTraveller = customer
        })
        bus.$on('TermsAgreed', function(state) {
          that.termsaccepted = state
        })

        that.bookingToken = getCookie(that.tokenName)
        this.debug && console.log('Cookie read:', that.bookingToken)

        if (typeof that.bookingToken != 'undefined' && that.bookingToken.length) {
            this.debug && console.log('Form Data requested with token:', that.bookingToken)
            axios.get(`/api/booking/token/${that.bookingToken}`)
            .then(response => {
                if (response.data.success) {
                    that.debug && console.log(`BookingForm: booking found by token`, response.data.booking)
                    that.leadTraveller = response.data.booking.customer
                    bus.$emit('setBookingToken', response.data.booking.token)

                    // TODO: are these events really needed?
                    bus.$emit('leadTravellerLoaded', that.leadTraveller)
                    bus.$emit('homeAddressLoaded', response.data.booking.customer.home_address)
                    bus.$emit('billingAddressLoaded', response.data.booking.customer.billing_address)
                } else {
                    // the token is not registered
                    console.log('**** requested token but no success, resetting it')
                    that.bookingToken = null
                    that.resetToken()
                    that.createBooking(that.bookingToken)
                }
            })
            .catch(error => {
                console.log('get current customer', error)
            })
        } else {
            console.log('BookingForm: no booking token set, resetting...')
            that.resetToken()
            that.createBooking(that.bookingToken)
        }
    },
    methods: {
        isset(obj) {
            if (typeof obj !== 'undefined' && obj !== null) {
                return Object.keys(obj).length > 0
            }
        },
        // todo integrate with login - list and activate tokens
        resetToken() {
            let that = this
            that.bookingToken = Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
            setCookie(that.tokenName, that.bookingToken)
            that.bookingToken = getCookie(that.tokenName);
            // that.debug && console.log('bookingToken reset ', that.bookingToken)
            // bus.$emit('setBookingToken', that.bookingToken)
        },
        resetForm() {
            this.resetToken()
            window.history.go()
        },
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
<style scoped lang="scss">
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
.card-header {
  display: flex;
  gap: 2rem;
  flex-direction: row;
  align-content: space-between;
}
.card-container {
  width: 100%;
}
</style>
