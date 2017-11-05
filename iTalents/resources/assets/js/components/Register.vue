<template>
  <form>
    <div class="z-depth-4 card-panel socialCard center">
      <p class="center"> - 選擇您的身分 - </p>
      <div class="row" style="margin-bottom: 0">
        <button class="btn waves-effect waves-light col s5 bounce animated student" :class="{active: type === 1, disable: type === 2}" style="height: 70px;" @click.prevent="setIdentify(1)">
          <i class="fa fa-graduation-cap"></i>
          <span>外籍生</span>
        </button>
        <button class="btn waves-effect waves-light col s5 push-s2 bounce animated company" :class="{active: type === 2, disable: type === 1}" style="height: 70px;" @click.prevent="setIdentify(2)">
          <i class="fa fa-id-card-o"></i>
          <span>廠商</span>
        </button>
      </div>
    </div>
    <div class="z-depth-4 card-panel transCard">
        <div class="row">
        <div class="input-field col s12 center"><img class="circle responsive-img" src="http://demo.geekslabs.com/materialize/v3.1/images/login-logo.png"/>
            <p class="center login-form-text f27">註冊</p>
        </div>
        </div>
        <div class="row margin">
        <div class="input-field col s12">
            <input id="email" type="text" v-model.trim="email" @blur="checkEmail" :class="{invalid: sameEmail===true, valid: sameEmail===false}"/>
            <label>Email</label>
        </div>
        <div class="input-field col s12">
            <input id="password" type="password" v-model.trim="password" @keyup.enter="regist"/>
            <label for="password">密碼</label>
        </div>
        </div>
        <div class="row">
        <div class="input-field col s12">
            <button class="btn waves-effect waves-light col s12" @click.prevent="regist"><i class="fa fa-cog fa-spin fa-fw" v-if="loading"></i><span v-if="!loading">送出</span></button>
        </div>
        </div>
        <div class="row">
        <div class="input-field col s12">
            <router-link class="margin" :to="{name: 'Login'}">已經有帳號? 立刻登入<br></router-link>
        </div>
        </div>
    </div>
    </form>
</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        email: '',
        password: '',
        type: null,
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
            this.type = type
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
                    let msg = ['資料格式錯誤', '註冊成功!']
                    let modalMsg = msg[response.data.stat]
                    if (response.data.stat === 1) {
                        setTimeout(() => {
                            this.$router.push({name: 'Email'})
                        }, 1500)
                    }
                    this.showDialog(modalMsg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
