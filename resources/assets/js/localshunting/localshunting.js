import { RouterSingleton } from './vuerouter'
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('alert', require('../components/core/alert.vue'));
Vue.component('loader', require('../components/core/loader.vue'));
Vue.component('core-nav', require('../components/layout/nav.vue'));


Vue.component('ls-nav', require('./layout/nav.vue'));


const app = new RouterSingleton.getRouter();