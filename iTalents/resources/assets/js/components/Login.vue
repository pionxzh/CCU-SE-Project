<template lang="pug">
    #table-page
        #center-page
            form
                .z-depth-4.card-panel.socialCard.center
                    p.center  - 選擇您的身分 - 
                    .row(style='margin-bottom: 0')
                        button.btn.waves-effect.waves-light.col.s5.bounce.animated.student(:class='{active: type === 1, disable: type === 2}', style='height: 70px;', @click.prevent='setIdentify(1)')
                            i.fa.fa-graduation-cap
                            span 外籍生
                        button.btn.waves-effect.waves-light.col.s5.push-s2.bounce.animated.company(:class='{active: type === 2, disable: type === 1}', style='height: 70px;', @click.prevent='setIdentify(2)')
                            i.fa.fa-id-card-o
                            span 廠商
                .z-depth-4.card-panel.transCard
                    .row
                        .input-field.col.s12.center
                            img.circle.responsive-img(src='http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png')
                            p.center.login-form-text.f27 登入
                    .row.margin
                        .input-field.col.s12
                            input#email(type='text', v-model.trim='email')
                            label(for='email') Email
                        .input-field.col.s12
                            input#password(type='password', v-model.trim='password', @keyup.enter='login')
                            label(for='password') 密碼
                    .row
                        .input-field.col.s12
                            button.btn.waves-effect.waves-light.col.s12(@click.prevent='login')
                                i.fa.fa-cog.fa-spin.fa-fw(v-if='loading')
                                span(v-if='!loading') 送出
                    .row
                        .input-field.col.s8
                            router-link.margin(:to="{name: 'Register'}") 還沒有帳號? 立刻註冊
                        .input-field.col.s4
                            router-link.margin.right(:to="{name: 'Register'}") 忘記密碼 ?

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        email: '',
        password: '',
        type: null,
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
        setIdentify(type) {
            this.type = type
        },
        login: function() {
            this.loading = true
            axios.post('/login', {
                email: this.email,
                password: this.password,
                user_type: this.type
                // _csrf: window.csrfToken
            })
                .then(response => {
                    this.loading = false
                    let modalMsg = response.data.stat ? `歡迎回來，用戶 ${response.data.username}，現在將轉回首頁!` : '帳號密碼組合錯誤'
                    if (response.data.stat) {
                        let that = this
                        setTimeout(function() {
                            that.$router.push('main')
                        }, 3000)
                    }
                    this.showDialog(modalMsg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
