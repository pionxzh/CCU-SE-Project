import Vue from 'vue'
import Router from 'vue-router'

import App from './App.vue'
import Test from './components/Test.vue'

Vue.use(Router)

window.Vue = require('vue')

const router = new Router({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [{
        name: 'test',
        path: '/test',
        component: Test
    }]
})

const app = new Vue({
    el: '#app',
    router,
    render: create => create(App)
})
