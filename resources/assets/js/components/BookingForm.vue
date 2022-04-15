<template>
    <div class="booking-form">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default card-container">
                    <div class="bookingform-header">
                        <div class="bookingform-header__controls">
                            <label for="booking_name">{{company}} booking for </label>
                            <input type="text" name="booking_name" title="You can change the name of this form" v-model="bookingName" @change="updateBookingName" />
                        </div>
                        <bookingform-control :token_label="tokenName"></bookingform-control>
                    </div>
                    <bookingform-header :company="company" :logo="logo" :event="event" :tour="tour"></bookingform-header>
                    <div id="booking-form" class="card-body">
                        <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                        <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                        <booking-form-lead :tour="tour" :booked="booked"></booking-form-lead>
                        <div v-if="tour && bookingToken">
                            <div v-show="bookingToken && leadTraveller">
                                <booking-form-additional :tour="tour"></booking-form-additional>
                                <booking-form-flights :tour="tour"></booking-form-flights>
                                <booking-form-accommodation :tour="tour"></booking-form-accommodation>
                                <booking-form-activity :tour="tour"></booking-form-activity>
                                <booking-form-transport :tour="tour"></booking-form-transport>
                                <booking-form-terms :tour="tour"></booking-form-terms>
                                <booking-form-payment :tour="tour" v-show="termsaccepted"></booking-form-payment>
                            </div>
                        </div>
                    </div>
                    <bookingform-footer :logo="logo" :company="company" systemcurrency="GBP"></bookingform-footer>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import BookingFormTour from './BookingFormTour.vue'
import { bus } from '../bus'
import { setCookie, getCookie, deleteCookie } from '../cookies'
import axios from 'axios'
import { stringify } from 'querystring'

export default {
    props: {
        auth_user: Object,
        tour: Object,
        event: Object,
        name: String,
        company: String,
        logo: String
    },
    components: { BookingFormTour },
    data() {
        return {
            debug: false,
            formInfo: false,
            bookingName: '',
            agencyName: this.company,
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

        this.debug && console.log('1) BookingForm created '+that.bookingToken,' for tour: ', this.tour)
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

        bus.$on('bookingCreated', booking => {
            that.bookingName = booking.name
        })

        bus.$on('resetBookingToken', () => {
            that.resetToken()
        })
        bus.$on('retrieveUserData', token => {
            that.retrieveUserdata(token)
        }) 

        that.bookingToken = getCookie(that.tokenName)
        this.debug && console.log('BOOKINGFORM Cookie read:', that.bookingToken)
        this.retrieveUserdata(that.bookingToken)
        if (typeof that.bookingToken != 'undefined' && that.bookingToken.length) {
            this.debug && console.log('BookingForm: loading booking data with token:', that.bookingToken)
            this.retrieveUserdata(that.bookingToken)
        } else {
            // If booking form has no token may mean consent for cookies is granted but cookies are not permitted?
            alert('Booking form can not be created, we need your consent to store cookies or please make your booking by phone')
        }
    },
    methods: {
        retrieveUserdata(token) {
            let that = this
            axios.get(`/api/booking/token/${token}`, {
                validateStatus: (status) => {
                    console.log('Backend returns ', status)
                    if (status == 429) {
                        alert('Booking for got too busy!  Give me a minute and refresh the browser');
                    }
                    return status < 300;
                }
            })
            .then(response => {
                if (response.data.success) {
                    const data = response.data
                    that.debug && console.log(`BookingForm: booking loaded `, data)
                    if (data.success == false) {
                        alert('error loading booking!')
                    } else {
                        that.bookingName = data.booking.name
                        that.leadTraveller = data.customer
                        // alert('setting token')
                        if (token === data.booking.token) {
                            bus.$emit('setBookingToken', data.booking.token)
                            // TODO: are these events really needed?
                            bus.$emit('leadTravellerLoaded', that.leadTraveller)
                            bus.$emit('homeAddressLoaded', data.customer.home_address)
                            bus.$emit('billingAddressLoaded', data.customer.billing_address)
                        } else {
                            // the token is not registered
                            console.log('BookingForm: no booking yet for that token, creating booking for ', that.bookingToken)
                            // alert('No BookingForm yet'+that.bookingToken)
                            //that.resetToken()
                        }
                    }
                }
            })
            .catch(error => {
                console.log('get current customer', error)
                window.document.reload()
            })
        },
        isset(obj) {
            if (typeof obj !== 'undefined' && obj !== null) {
                return Object.keys(obj).length > 0
            }
        },
        updateBookingName() {
            const name = this.bookingName
            const token = this.bookingToken
            console.log('update', name)
            axios.post('/api/booking/name/update', {name: name, token: token})
                .then(response => {
                    console.log('updateBookingName response', response)
                    bus.$emit('controlLoadBookings')
                })
                .catch(error => console.log(error))
        },
        // todo integrate with login - list and activate tokens
        resetToken() {
            
            let that = this
            
            const token = getCookie(this.tokenName)
            // alert('reset Token ' + token)
            that.bookingToken = Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
            setCookie(that.tokenName, that.bookingToken)
            that.bookingToken = getCookie(that.tokenName);
            that.debug && console.log('bookingToken reset ', that.bookingToken)

            bus.$emit('setBookingToken', that.bookingToken)
        },
        // resetForm() {
        //     this.resetToken()
        //     window.history.go()
        // },
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
.card-container {
  width: 100%;
}
</style>
