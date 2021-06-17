
require('./bootstrap');
import router from "./routes";
import VueRouter from 'vue-router';
import Vuex from 'vuex';    
import Index from './Index';
import moment from  'moment';
import Vue from "vue";
import StarRating from './shared/components/StarRating';
import FatalError from './shared/components/FatalError';
import ValidationErrors from './shared/components/ValidationErrors';
import Success from './shared/components/Success';
import StoreDefination from './store';
import axios from "axios";

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(Vuex);


Vue.component('star-rating', StarRating);
Vue.component('fatal-error', FatalError);
Vue.component('v-error', ValidationErrors);
Vue.component('success', Success);

Vue.filter('fromNow', value => {
    return moment(value).fromNow(); 
});

const store = new Vuex.Store(StoreDefination)
window.axios.interceptors.response.use(
    response => {
        response
    },
    error => {
        if (error.response.status == 401) {
            store.dispatch('logout')
        }

        return Promise.reject()
    }
)

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { Index },
    async beforeCreate() {
        this.$store.dispatch('loadLocalstorage')

        // await axios.get('/sanctum/csrf-cookie')

        // await axios.post('/login', {
        //     email: '@example.net',
        //     password: 'password'
        // })

        // await axios.get('/user')
        
        this.$store.dispatch('loadUser')

    }
});
