require('./bootstrap');
import Vue from 'vue'
import { bus } from './bus'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret, faFutbol, faTrain, faListAlt, faPlane, faHome} from '@fortawesome/free-solid-svg-icons'
import { faFacebook, faFacebookSquare, faInstagramSquare, faTwitterSquare } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

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
// Vue.component('booking-store', require('./components/BookingStore.vue').default);

Vue.component('payment-schedule', require('./components/PaymentSchedule.vue').default);
Vue.component('payment-installments', require('./components/PaymentInstallments.vue').default);

Vue.component('vue-test', require('./components/VueTest.vue').default);

//  Vue.component('phonenumber-validation', require('./components/PhonenumberValidation.vue').default);
//  Vue.component('donut-menu', require('./components/donut-menu.vue').default);
//  Vue.component('tour-menu', require('./components/tour-menu.vue').default);
//  Vue.component('details-menu', require('./components/details-menu.vue').default);
//  Vue.component('extras-menu', require('./components/extras-menu.vue').default);
Vue.component('finance-menu', require('./components/finance-menu.vue').default);
Vue.component('bookingform-header', require('./components/BookingFormHeader.vue').default);
Vue.component('bookingform-footer', require('./components/bookingform-footer.vue').default);
//  Vue.component('example-cdomponent', require('./components/ExampleComponent.vue').default);

Vue.component('AtolCertificate', require('./components/AtolCertificate.vue').default);
 // deprecated
 // Vue.component('booking-form-details', require('./components/BookingFormDetails.vue').default);
 // Vue.component('x-accommodation-details', require('./components/x-accommodation-details.vue').default);

const app = new Vue({
    el: '#app'
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// operational 
bus.$on('setOrderToken', function(token, order_id) {
    console.log('Event Bus: setting token cookie for order_id', token, order_id)
    setCookie('OTM_booking_order_token', token);
    bus.$emit('setOrderToken2', token, order_id)
})

const busEventLogging = true

bus.$on('click', function(id) {
    busEventLogging && console.log('added traveller', id)
})

bus.$on('saveLeadCustomer', function(name) {
     bus.booking.name = name
 })

bus.$on('customerLoaded', function(customer) {
    busEventLogging && console.log('Event Bus: Customer Loaded', customer)
})

bus.$on('leadTravellerLoaded', function(customer) {
    busEventLogging && console.log('Event Bus: leadTravellerLoaded', customer)
})

bus.$on('removeTraveller', function(id) {
     busEventLogging && console.log('Event Bus: removed traveller ',id)
})

bus.$on('accommodationBookingsLoaded', function() {
    busEventLogging && console.log('accommodationBookingsLoaded')
})

let othertravellers = []
// initialises the external array
bus.$on('loadOthers', function(others) {
    othertravellers = others //.map(t => t.id)
    //console.log('init others', others)
})

bus.$on('setRoomShare', function(t, share, room) {
    let others = othertravellers
    console.log('setRoomShare (global) ', t.id, share.id, room)
    others = others.filter(o => {
        return share.id != o.id
    })
    others = others.filter(o => {
        console.log('filtering out traveller', t.first_name)
        return t.id != o.id
    })
    console.log('global filter from from', othertravellers, ' to ', others)
    othertravellers = others
    bus.$emit('setOthers', others, t)
})
