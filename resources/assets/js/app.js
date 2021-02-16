require('./bootstrap');
window.axios = require('axios');
window.Vue = require('vue');
window.lodash = require('lodash');

window.axios.defaults.headers.common = {
     'X-Requested-With': 'XMLHttpRequest',
     'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
     'Access-Control-Allow-Methods' : 'HEAD, GET, POST, PUT, PATCH, DELETE'
 };

 Vue.component('booking-form', require('./components/BookingForm.vue').default);
 Vue.component('booking-form-details', require('./components/BookingFormDetails.vue').default);
 Vue.component('flight-information', require('./components/FlightInformation.vue').default);
 Vue.component('accommodation-details', require('./components/AccommodationDetails.vue').default);

 Vue.component('payment-schedule', require('./components/PaymentSchedule.vue').default);
 Vue.component('payment-installments', require('./components/PaymentInstallments.vue').default);

 Vue.component('donut-menu', require('./components/donut-menu.vue').default);
 Vue.component('tour-menu', require('./components/tour-menu.vue').default);
 Vue.component('details-menu', require('./components/details-menu.vue').default);
 Vue.component('extras-menu', require('./components/extras-menu.vue').default);
 Vue.component('finance-menu', require('./components/finance-menu.vue').default);
 Vue.component('bookingform-header', require('./components/bookingform-header.vue').default);
 Vue.component('bookingform-footer', require('./components/bookingform-footer.vue').default);
 Vue.component('bookingform-lead', require('./components/bookingform-lead.vue').default);
 Vue.component('bookingform-additional', require('./components/bookingform-additional.vue').default);
 Vue.component('bookingform-addtemplate', require('./components/bookingform-addtemplate.vue').default);
 Vue.component('example-cdomponent', require('./components/ExampleComponent.vue').default);
 Vue.component('bookingform-terms', require('./components/bookingform-terms.vue').default);
 const app = new Vue({
     el: '#app',
});
