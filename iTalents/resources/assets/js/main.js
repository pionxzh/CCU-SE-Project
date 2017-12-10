import Vue from 'vue'
import Vuetify from 'vuetify'
import Router from 'vue-router'
import Vmodal from 'vue-js-modal'

import App from './App.vue'
import Nav from './Nav.vue'
import Main from './components/Main.vue'
import Login from './components/Login.vue'
import Recruit from './components/Recruit.vue'
import Register from './components/Register.vue'

Vue.component('navbar', Nav)

Vue.use(Vuetify)
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
        name: 'Recruit',
        path: '/recruit',
        component: Recruit
    }, {
        path: '/*',
        redirect: '/main'
    }]
})

new Vue({
    el: '#app',
    router,
    render: create => create(App)
})
