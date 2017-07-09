import 'whatwg-fetch'
import VueRouter from 'vue-router'
import { http, showAlert, prepareTable, formatDate, confirmPopup, hasPermission, mapToFormData } from './core';
window.http = http;
window.alert2 = showAlert;
window.prepareTable = prepareTable;
window._date2 = formatDate;
window.confirm2 = confirmPopup;
window.can = hasPermission;
window.mapToFormData = mapToFormData;

window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('select2');

require('bootstrap-sass');
require('datatables.net');
require('datatables.net-bs');
require('datatables.net-buttons');
require('datatables.net-buttons-bs');

require('datatables.net-buttons/js/buttons.colVis.js');
require('datatables.net-buttons/js/buttons.html5.js');
require('datatables.net-buttons/js/buttons.flash.js');
require('datatables.net-buttons/js/buttons.print.js');

require('bootstrap-datepicker');
require('moment');

$.fn.select2.defaults.set("theme", "bootstrap");

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

let vue = require('vue');
vue.use(VueRouter);

window.Vue = vue;
window.VueRouter = VueRouter;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
