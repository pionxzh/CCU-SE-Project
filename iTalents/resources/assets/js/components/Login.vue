<template lang="pug">
    v-app.foreienBg(light)
        v-content
            v-layout(row justify-space-between)
                v-flex(xs0)
                v-flex(xs0)
                    .identifyCard(:class='{selected: type !== 0}')
                        p.text-xs-center.white--text - 選擇您的身分 -
                        .select-card.student(ripple :class='{active: type === 1}' @click='setIdentify(1)')
                            .select-card-text
                                i.fa.fa-graduation-cap
                                span &nbsp;學生
                        .select-card.company(ripple :class='{active: type === 2}' @click='setIdentify(2)')
                            .select-card-text
                                i.fa.fa-id-card-o
                                span &nbsp;廠商
                v-flex(xs0)
            v-layout(row justify-space-between)
                v-flex(xs0)
                v-flex(xs0)
                    v-card.transCard(dark :class='{show: type !== 0}')
                        v-card-text
                            v-avatar.center-item(size='180' style='margin-bottom: 10px;')
                                img(src='http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png')
                            p.text-xs-center.mb-3(style='font-size: 20px;') 登入
                            v-text-field(type='text' name='email' label='Email' v-model.trim='email' dark)
                            v-text-field(type='password' name='password' label='密碼' v-model.trim='password' @keyup.enter='login' dark)

                            v-btn.wide-btn.mb-4(color='primary' style='margin-left: 0px;' @click.prevent='login' :loading="loading" :disabled="loading") 送出
                        router-link.no-decoration.mb-2(:to="{name: 'Register'}") 還沒有帳號? 立刻註冊
                        router-link.no-decoration.right.mb-2(:to="{name: 'Register'}") 忘記密碼 ?
                v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        email: '',
        password: '',
        type: 0,
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
            this.type = this.type === type ? 0 : type
        },
        login: function() {
            this.loading = true
            axios.post('/login', {
                email: this.email,
                password: this.password,
                userType: this.type
                // _csrf: window.csrfToken
            })
                .then(response => {
                    this.loading = false
                    let modalMsg = response.data.stat ? `歡迎回來，用戶 ${this.email.split('@', 1)[0]}，現在將轉回首頁!` : '帳號密碼組合錯誤'
                    if (response.data.stat) {
                        setTimeout(function() {
                            window.location.href = '/main'
                        }, 2000)
                    }
                    this.showDialog(modalMsg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
