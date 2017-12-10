import Vue from 'vue'
import axios from 'axios'
import Vuetify from 'vuetify'
import Router from 'vue-router'
import Vmodal from 'vue-js-modal'

import App from './App.vue'
import Nav from './Nav.vue'
import Footer from './Footer.vue'
import Main from './components/Main.vue'
import Login from './components/Login.vue'
import ResumeEdit from './components/ResumeEdit.vue'
import Recruit from './components/Recruit.vue'
import RecruitShow from './components/RecruitShow.vue'
import RecruitEdit from './components/RecruitEdit.vue'
import Register from './components/Register.vue'

Vue.component('navbar', Nav)
Vue.component('p-footer', Footer)

Vue.use(Vuetify)
Vue.use(Router)
Vue.use(Vmodal, {
    dialog: true
})

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
        name: 'ResumeEdit',
        path: '/resume/edit',
        component: ResumeEdit
    }, {
        name: 'Recruit',
        path: '/recruit',
        component: Recruit
    }, {
        name: 'RecruitShow',
        path: '/recruit/:id',
        component: RecruitShow
    }, {
        name: 'RecruitEdit',
        path: '/recruit/edit/:id',
        component: RecruitEdit
    }, {
        path: '/*',
        redirect: '/main'
    }]
})

axios.get('/api/user')
    .then(response => {
        console.log(response.data)
        Vue.prototype.user = {username: ''}
        if (response.data.stat) Vue.prototype.user = response.data

        new Vue({
            el: '#app',
            router,
            render: create => create(App)
        })
    })
    .catch(err => console.log(err))
