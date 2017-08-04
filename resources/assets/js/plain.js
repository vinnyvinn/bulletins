import 'whatwg-fetch'

import { http, showAlert, prepareTable, formatDate, confirmPopup, hasPermission, mapToFormData } from './core';
window.http = http;
window.alert2 = showAlert;
window.prepareTable = prepareTable;
window._date2 = formatDate;
window.confirm2 = confirmPopup;
window.can = hasPermission;
window.mapToFormData = mapToFormData;

window._ = require('lodash');

const Vue = require('vue');
window.Vue = Vue;

Vue.component('udf', require('./components/udfs/udf.vue'));
Vue.component('udf-create', require('./components/udfs/form.vue'));
Vue.component('show-udfs', require('./components/udfs/show.vue'));
Vue.component('driver-form', require('./components/drivers/form.vue'));

const app = new Vue({
    data: {
        currency: 'KES',
        isLoading: false,
        errors: [],
        showAlert: false,
        csrf: window.Laravel.csrfToken,
        level: 'info',
        user: JSON.parse(localStorage.getItem('fewuia32rfwe')),
        isLoggedIn: !! localStorage.getItem('foeiwafwfuwe')
    },
}).$mount('#app');
