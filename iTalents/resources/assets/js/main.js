import Vue from 'vue'
import Router from 'vue-router'
import Vmodal from 'vue-js-modal'

import App from './App'
import Login from './components/Login'
import Register from './components/Register'
import Email from './components/Email'

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

const app = new Vue({
    el: '#app',
    router,
    render: create => create(App)
})
