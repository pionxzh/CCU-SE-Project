import Vue from 'vue'
import Router from 'vue-router'
import Vmodal from 'vue-js-modal'

import App from './App.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import Email from './components/Email.vue'

Vue.use(Router)
Vue.use(Vmodal, {
    dialog: true
})

window.Vue = require('vue')

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [{
        name: 'Login',
        path: '/login',
        component: Login
    }, {
        name: 'Register',
        path: '/register',
        component: Register
    }, {
        name: 'Email',
        path: '/emailVerify',
        component: Email
    }, {
        path: '/*',
        redirect: '/login'
    }]
})

new Vue({
    el: '#app',
    router,
    render: create => create(App)
})
