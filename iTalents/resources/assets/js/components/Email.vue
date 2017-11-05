<template>
    <div>
    <div class="z-depth-4 card-panel transCard">
        <div class="row">
            <div class="input-field col s12 center"><img class="circle responsive-img" src="http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png"/>
                <p class="center login-form-text f27">信箱驗證</p>
            </div>
        </div>
        <div class="row margin center">
            <p>
                <span>将会发送验证信至以下邮箱，点击 </span>
                <span class="orange-text">發送</span>
            </p>
            <span class="ver-email">{{ email }}</span>
        <div class="input-field col s12" style="margin-top: 30px">
            <input id="verticode" type="text" v-model.trim="code"/>
            <label for="verticode">驗證碼</label>
        </div>
        <div class="col s6 push-s3" v-if="!sended">
            <button class="waves-effect waves-light btn" @click="askVerify"><i class="fa fa-cog fa-spin fa-fw" v-if="loading"></i><span v-if="!loading">發送</span></button>
        </div>
        <div class="col s6 push-s3" v-if="sended">
            <button class="waves-effect waves-light btn blue" @click="sendVerify" @keyup.enter="sendVerify"><i class="fa fa-cog fa-spin fa-fw" v-if="loading"></i><span v-if="!loading">送出</span></button>
        </div>
        </div>
    </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        data: () => ({
            code: '',
            email: 'test@gmail.com',
            loading: false,
            sended: false,
            stat: null
        }),
        methods: {
            errHandler(e) {
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
            askVerify() {
                if (this.loading) return
                this.loading = true
                setTimeout(() => {
                    this.loading = false
                    this.sended = true
                    this.showDialog('成功发送! 请至邮箱收取认证信件')
                }, 2000)
            },
            sendVerify() {
                if (this.loading) return
                this.loading = true
                axios.post('/api/verify/', {code: this.code})
                    .then(response => {
                        let sendMsg = ['验证码错误!', '恭喜!验证成功']
                        this.loading = false
                        this.showDialog(sendMsg[response.data.stat])
                        if (response.data.stat === 1) {
                            setTimeout(function() {
                                window.location.href = '/login'
                            }, 2000)
                        }
                    })
                    .catch(e => this.errHandler(e))
            }
        }
    }
</script>

