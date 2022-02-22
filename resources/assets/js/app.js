require('./bootstrap');
require('./cookies')

import Vue from 'vue'
import { bus } from './bus'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faArrowRight, faBookReader, faCheck } from '@fortawesome/free-solid-svg-icons'
import { faUserSecret, faFutbol, faTrain, faListAlt, faPlane, faHome} from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faFacebookSquare, faInstagramSquare, faTwitterSquare } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faArrowRight, faBookReader, faCheck)
library.add(faUserSecret)
library.add(faFutbol)
library.add(faTrain)
library.add(faListAlt)
library.add(faPlane)
library.add(faHome)
library.add(faFacebook)
library.add(faFacebookSquare)
library.add(faTwitterSquare)
library.add(faInstagramSquare)

Vue.config.productionTip = false

// jquery is working ... validation
$('.addredbordertest').addClass('red-border');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Access-Control-Allow-Methods' : 'HEAD, GET, POST, PUT, PATCH, DELETE'
};

const busEventLogging = true
bus.$on('saveLeadCustomer', function(name) {
    bus.booking.name = name
})

if (busEventLogging) {
    bus.$on('getLoginToken', () => {
        console.log('>>> getLoginToken cookie')
    })
    bus.$on('setBookingToken', token => {
        console.log('>>>> setBookingToken event monitor ', token);
    })
    bus.$on('bookingCreated', booking => {
        console.log('>>> booking created by lead traveller', booking)
    })
    bus.$on('click', function(id) {
        console.log('added traveller', id)
    })
    bus.$on('leadTravellerLoaded', function(customer) {
        console.log('Event Bus: leadTravellerLoaded', customer)
    })
    bus.$on('removeTraveller', function(id) {
        console.log('Event Bus: removed traveller ',id)
    })
    bus.$on('accommodationBookingsLoaded', function() {
        console.log('accommodationBookingsLoaded')
    })
}

// global array is needed for intermodule setting
let othertravellers = []
// initialises the external array
bus.$on('loadOthers', function(others) {
    othertravellers = others
})

const logRoomShare = false
bus.$on('setRoomShare', function(t, share, room) {
    let others = othertravellers
    logRoomShare && console.log('setRoomShare (global) ', t.id, share.id, room)
    others = others.filter(o => {
        return share.id != o.id
    })
    others = others.filter(o => {
        logRoomShare && console.log('filtering out traveller', t.first_name)
        return t.id != o.id
    })
    logRoomShare && console.log('global filter from from', othertravellers, ' to ', others)
    othertravellers = others
    bus.$emit('setOthers', others, t)
})

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('booking-form', require('./components/BookingForm.vue').default);
Vue.component('booking-form-tour', require('./components/BookingFormTour.vue').default);
Vue.component('booking-form-lead', require('./components/BookingFormLead.vue').default);
Vue.component('booking-form-additional', require('./components/BookingFormAdditional.vue').default);
Vue.component('booking-form-add-traveller', require('./components/BookingFormAddTraveller.vue').default);
Vue.component('booking-form-flights', require('./components/BookingFormFlights.vue').default);
Vue.component('booking-form-flight-select', require('./components/BookingFormFlightSelector.vue').default);
Vue.component('booking-form-accommodation', require('./components/BookingFormAccommodation.vue').default);
Vue.component('accommodation-room-selection', require('./components/AccommodationRoomSelection.vue').default);
Vue.component('booking-form-activity', require('./components/BookingFormActivity.vue').default);
Vue.component('booking-form-transport', require('./components/BookingFormTransport.vue').default);
Vue.component('booking-form-payment', require('./components/BookingFormPayment.vue').default);
Vue.component('booking-form-terms', require('./components/BookingFormTerms.vue').default);
Vue.component('validation-errors', require('./components/ValidationErrors.vue').default);
Vue.component('payment-installments', require('./components/PaymentInstallments.vue').default);
Vue.component('booking-info', require('./components/BookingInfo.vue').default);
Vue.component('vue-test', require('./components/VueTest.vue').default);
Vue.component('finance-menu', require('./components/finance-menu.vue').default);
Vue.component('bookingform-header', require('./components/BookingFormHeader.vue').default);
Vue.component('bookingform-footer', require('./components/bookingform-footer.vue').default);
Vue.component('AtolCertificate', require('./components/AtolCertificate.vue').default);
const app = new Vue({
    el: '#app'
});

// not used...? - NB it is in BookingForm.vue TODO: remove
// function setCookie(cname, cvalue, exdays) {
//     var d = new Date();
//     d.setTime(d.getTime() + (exdays*24*60*60*1000));
//     var expires = "expires="+ d.toUTCString();
//     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
// }
