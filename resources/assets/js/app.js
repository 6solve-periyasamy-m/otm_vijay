// import $ from 'jquery';
// import 'jquery-ui/themes/base/core.css';
// import 'jquery-ui/themes/base/theme.css';
// import 'jquery-ui/themes/base/selectable.css';
// import 'jquery-ui/ui/core';
// import 'jquery-ui/ui/widgets/selectable';
require('./bootstrap');
require('./script');
window.axios = require('axios');
window.Vue = require('vue');
window.lodash = require('lodash');

window.axios.defaults.headers.common = {
     'X-Requested-With': 'XMLHttpRequest',
     'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
     'Access-Control-Allow-Methods' : 'HEAD, GET, POST, PUT, PATCH, DELETE'
 };

 Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 const app = new Vue({
     el: '#app'
});
