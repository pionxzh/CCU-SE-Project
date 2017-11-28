<template lang="pug">
    div
        ul#user-tab.dropdown-content
            li: a(href='#!') 我的履歷
            li: a(href='#!') 設定
            li.divider
            li: a(href='#!') 登出
        nav.indigo
            .nav-wrapper
                a.brand-logo(href='#') iTalent
                ul#nav-mobile.right.hide-on-med-and-down
                    li: router-link(to='/login') 登入
                    li: router-link(to='/register') 註冊
                    li: router-link(to='/login') 先佔位置
                    li(v-if='user.logined')
                        a.dropdown-button(href='#!', data-activates='user-tab') {{user.username}}
                            i.material-icons.right arrow_drop_down
        .container
            .test-center-dot
                i.material-icons add

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        user: {
            logined: false,
            username: '用戶名不存在',
            type: 0
        },
        loading: false
    }),
    methods: {
        errHandler (e) {
            console.log(e.message)
            this.loading = false
        },
        showDialog(msg) {
            this.$modal.show('dialog', {
                title: '提示',
                text: msg,
                buttons: [{title: '關閉'}]
            })
        },
        getUser: function() {
            this.loading = true
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
