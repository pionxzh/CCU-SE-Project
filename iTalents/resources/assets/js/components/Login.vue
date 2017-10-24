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
          <p class="center login-form-text f27">登入</p>
        </div>
      </div>
      <div class="row margin"> 
        <div class="input-field col s12">
          <input id="email" type="text" v-model.trim="email"/>
          <label for="email">Email</label>
        </div>
        <div class="input-field col s12">
          <input id="password" type="password" v-model.trim="password" @keyup.enter="login"/>
          <label for="password">密碼</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <button class="btn waves-effect waves-light col s12" @click.prevent="login"><i class="fa fa-cog fa-spin fa-fw" v-if="loading"></i><span v-if="!loading">送出</span></button>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s8">
          <router-link class="margin" :to="{name: 'Register'}">還沒有帳號? 立刻註冊</router-link>
        </div>
        <div class="input-field col s4">
          <router-link class="margin right" :to="{name: 'Register'}">忘記密碼 ?</router-link>
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
                    setTimeout(function() {
                        window.location.href = '/'
                    }, 1500)
                }
                this.showDialog(modalMsg)
            })
            .catch(e => this.errHandler(e))
        }
    }
}
</script>
