require('./bootstrap');
window.axios = require('axios');
window.lodash = require('lodash');
import { BIconNodePlus } from 'bootstrap-vue';
//window.validPhone = require('./validphone');
import Vue from 'vue'
import { bus } from './bus'
//import { validPhone } from './validphone'

window.axios.defaults.headers.common = {
     'X-Requested-With': 'XMLHttpRequest',
     'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
     'Access-Control-Allow-Methods' : 'HEAD, GET, POST, PUT, PATCH, DELETE'
 };

 Vue.component('vue-test', require('./components/VueTest.vue').default);

 Vue.component('booking-form', require('./components/BookingForm.vue').default);
 Vue.component('booking-form-tour', require('./components/BookingFormTour.vue').default);
 Vue.component('booking-form-lead', require('./components/BookingFormLead.vue').default);
 Vue.component('booking-form-additional', require('./components/BookingFormAdditional.vue').default);
 Vue.component('booking-form-add-traveller', require('./components/BookingFormAddTraveller.vue').default);
 Vue.component('booking-form-flights', require('./components/BookingFormFlights.vue').default);
 Vue.component('booking-form-flight-select', require('./components/BookingFormFlightSelector.vue').default);
 Vue.component('booking-form-accommodation', require('./components/BookingFormAccommodation.vue').default);
 Vue.component('booking-form-activity', require('./components/BookingFormActivity.vue').default);
 Vue.component('booking-form-transport', require('./components/BookingFormTransport.vue').default);
 Vue.component('booking-form-payment', require('./components/BookingFormPayment.vue').default);
 Vue.component('booking-form-terms', require('./components/BookingFormTerms.vue').default);


// Vue.component('booking-store', require('./components/BookingStore.vue').default);

//  Vue.component('payment-schedule', require('./components/PaymentSchedule.vue').default);
//  Vue.component('payment-installments', require('./components/PaymentInstallments.vue').default);
//  Vue.component('phonenumber-validation', require('./components/PhonenumberValidation.vue').default);
//  Vue.component('donut-menu', require('./components/donut-menu.vue').default);
//  Vue.component('tour-menu', require('./components/tour-menu.vue').default);
//  Vue.component('details-menu', require('./components/details-menu.vue').default);
//  Vue.component('extras-menu', require('./components/extras-menu.vue').default);
//  Vue.component('finance-menu', require('./components/finance-menu.vue').default);
Vue.component('bookingform-header', require('./components/bookingform-header.vue').default);
Vue.component('bookingform-footer', require('./components/bookingform-footer.vue').default);
//  Vue.component('example-cdomponent', require('./components/ExampleComponent.vue').default);



 // deprecated
 // Vue.component('booking-form-details', require('./components/BookingFormDetails.vue').default);
 // Vue.component('x-accommodation-details', require('./components/x-accommodation-details.vue').default);

const app = new Vue({
    el: '#app'
});

 // event bus handlers
 // addTraveller 
 // removeTraveller
 function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
const busEventLogging = true
bus.$on('click', function(id) {
    busEventLogging && console.log('added traveller', id)
})
//  bus.$on('saveLeadCustomer', function(name) {
//      bus.booking.name = name
//  })
bus.$on('customerLoaded', function(customer) {
    busEventLogging && console.log('Event Bus: Customer Loaded', customer)
})
 bus.$on('removeTraveller', function(id) {
     busEventLogging && console.log('Event Bus: removed traveller ',id)
 })
bus.$on('setOrderToken', function(token, order_id) {
    busEventLogging && console.log('Event Bus: setOrderToken token, order_id', token, order_id)
    setCookie('OTM_booking_order_token', token);
})
