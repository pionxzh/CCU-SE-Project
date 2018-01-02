<template lang="pug">
    v-app.foreienBg(light)
        v-content
            v-container(fluid fill-height)
                v-layout(justify-center align-center)
                    div.cards-wrapper(:class='{selected: type !== 0}')
                        .identifyCard
                            p.text-xs-center.white--text {{ $t('login.chooseIdentify') }}
                            .select-card.student(ripple :class='{active: type === 1}' @click='setIdentify(1)')
                                .select-card-text
                                    i.fa.fa-graduation-cap
                                    span &nbsp;{{ $t('common.student') }}
                            .select-card.company(ripple :class='{active: type === 2}' @click='setIdentify(2)')
                                .select-card-text
                                    i.fa.fa-id-card-o
                                    span &nbsp;{{ $t('common.company') }}
                        v-card.transCard(dark :class='{show: type !== 0}')
                            v-card-text
                                v-avatar.center-item.mb-0(size='180')
                                    img(src='http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png')
                                p.text-xs-center.mb-3(style='font-size: 20px;') {{ $t('common.register') }}
                                v-text-field(type='text' name='email' label='Email' v-model.trim='email' dark)
                                v-text-field(type='password' name='password' :label='$t("login.password")' v-model.trim='password' @keyup.enter='regist' dark)
                                //v-checkbox(label="我已詳細閱讀並同意《授權條款》" v-model="agreement" dark)

                                v-btn.wide-btn.mb-4(color='primary' style='margin-left: 0px;' @click.prevent='regist' :loading="loading" :disabled="loading") {{ $t('common.submit') }}
                            router-link.no-decoration.right.mb-2(:to="{name: 'Login'}") {{ $t('login.alreadyHaveAcc') }}


</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        email: '',
        password: '',
        type: 0,
        agreement: false,
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
                title: this.$t('common.hint'),
                text: msg,
                buttons: [{ title: this.$t('common.close') }]
            })
        },
        showAgreemwnt() {
            this.$modal.show('dialog', {
                title: this.$t('login.agreementTitle'),
                text: this.$t('login.agreementContent'),
                buttons: [
                    { title: this.$t('common.no') },
                    {
                        title: this.$t('common.agree'),
                        handler: () => {
                            this.agreement = true
                            this.$modal.hide('dialog')
                        }
                    }
                ]
            })
        },
        checkForm() {
            if (this.password.length < 6 || this.password.length > 15) {
                this.showDialog('密碼長度須為6-15字元')
                return true
            }
            if (!this.agreement) {
                this.showAgreemwnt()
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
                userType: this.type
                // _csrf: window.csrfToken
            })
                .then(response => {
                    this.loading = false
                    let msg = ['資料格式錯誤', '註冊成功!請至信箱收取認證信，三秒後將自動轉向登入頁面', '帳號已存在']
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
