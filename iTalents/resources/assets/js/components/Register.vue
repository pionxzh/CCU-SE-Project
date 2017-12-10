<template lang="pug">
    v-app.foreienBg(light='')
        v-content
            v-layout(row='' justify-space-between='')
                v-flex(xs0='')
                v-flex(xs0='')
                    .identifyCard(:class='{selected: type !== 0}')
                        p.text-xs-center.white--text - 選擇您的身分 -
                        .select-card.student(ripple='' :class='{active: type === 1}' @click='setIdentify(1)')
                            .select-card-text
                                i.fa.fa-graduation-cap
                                span &nbsp;學生
                        .select-card.company(ripple='' :class='{active: type === 2}' @click='setIdentify(2)')
                            .select-card-text
                                i.fa.fa-id-card-o
                                span &nbsp;廠商
                v-flex(xs0='')
            v-layout(row='' justify-space-between='')
                v-flex(xs0='')
                v-flex(xs0='')
                    v-card.transCard(dark='' :class='{show: type !== 0}')
                        v-card-text
                            v-avatar.center-item.mb-0(size='180')
                                img(src='http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png')
                            p.text-xs-center.mb-3(style='font-size: 20px;') 註冊
                            v-text-field(type='text' name='email' label='Email' v-model.trim='email' dark='')
                            v-text-field(type='password' name='password' label='密碼' v-model.trim='password' @keyup.enter='regist' dark='')

                            v-btn.wide-btn.mb-4(color='primary' style='margin-left: 0px;' @click.prevent='regist' :loading="loading" :disabled="loading") 送出
                        router-link.no-decoration.right.mb-2(:to="{name: 'Login'}") 已經有帳號? 立刻登入
                v-flex(xs0='')

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        email: '',
        password: '',
        type: 0,
        sameEmail: null,
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
        checkForm() {
            if (this.password.length < 6 || this.password.length > 15) {
                this.showDialog('密碼長度須為6-15字元')
                return true
            }
            return false
        },
        setIdentify(type) {
            this.type = this.type === type ? 0 : type
        },
        regist: function() {
            if (this.checkForm()) return
            this.loading = true
            axios.post('/register', {
                email: this.email,
                password: this.password,
                user_type: this.type
                // _csrf: window.csrfToken
            })
                .then(response => {
                    this.loading = false
                    let msg = ['資料格式錯誤', '註冊成功!請至信箱收取認證信，三秒後將自動轉向登入頁面']
                    let modalMsg = msg[response.data.stat]
                    if (response.data.stat === 1) {
                        let that = this
                        setTimeout(function() {
                            that.$router.push('login')
                        }, 2000)
                    }
                    this.showDialog(modalMsg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
