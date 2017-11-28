import Vue from 'vue'
import Router from 'vue-router'
import Vmodal from 'vue-js-modal'

import App from './App.vue'
import Main from './components/Main.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'

Vue.use(Router)
Vue.use(Vmodal, {
    dialog: true
})

window.Vue = require('vue')

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [{
        name: 'Main',
        path: '/',
        component: Main
    }, {
        path: '/main',
        component: Main
    }, {
        name: 'Login',
        path: '/login',
        component: Login
    }, {
        name: 'Register',
        path: '/register',
        component: Register
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
