
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import VueRouter from 'vue-router'
import ProductPage from './components/ProductPage.vue'
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('Alert', require('./components/Alert.vue'));
Vue.component('remote-data-table', require('./components/RemoteDataTable.vue'));


const routes = [
    { path: '/product/:id', component: require('./components/ProductPage.vue') },
]

const router = new VueRouter({
    routes // kurz für 'routes: routes'
})
Vue.use(VueRouter)
const app = new Vue({
    router
}).$mount('#app');