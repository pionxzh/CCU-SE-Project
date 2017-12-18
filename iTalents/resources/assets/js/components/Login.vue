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
                                v-text-field(type='password' name='password' :label='$t("common.password")' v-model.trim='password' @keyup.enter='login' dark)

                                v-btn.wide-btn.mb-4(color='primary' style='margin-left: 0px;' @click.prevent='login' :loading="loading" :disabled="loading") {{ $t('common.submit') }}
                            router-link.no-decoration.mb-2(:to="{name: 'Register'}") {{ $t('login.registerNow') }}
                            router-link.no-decoration.right.mb-2(:to="{name: 'Register'}") {{ $t('login.forgetAccount') }}
                            
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
                title: this.$t('common.hint'),
                text: msg,
                buttons: [{ title: this.$t('common.close') }]
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
                    let modalMsg = response.data.stat ? `歡迎回來，用戶 ${this.email.split('@', 1)[0]}，現在將轉回首頁!` : this.$t('login.wrongAccount')
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
