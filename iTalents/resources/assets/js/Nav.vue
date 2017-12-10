<template lang="pug">
    v-toolbar.primary(color="blue darken-3" dark="" app="" clipped-left="" fixed="")
        router-link.no-decoration(:to="{name: 'Main'}")
            v-toolbar-title iTalent
        v-spacer
        v-text-field(light="" solo prepend-icon="search" placeholder="Search" style="max-width: 500px; min-width: 128px")
        v-spacer
        .d-flex(align-center="" style="margin-left: auto" v-if='!loggedIn')
            router-link(:to="{name: 'Login'}")
                v-btn 登入/註冊
        v-menu(v-if="loggedIn")
            v-btn.mr-5(slot="activator") {{user.username}}
            v-list
                v-list-tile.list__tile--link
                    v-list-tile-title 會員中心
                v-divider
                v-list-tile.list__tile--link(@click='logout')
                    v-list-tile-title 登出

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        user: {},
        loggedIn: false,
        loading: false
    }),
    created() {
        this.getUser()
    },
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
            axios.get('/api/user')
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.loggedIn = true
                        this.user = response.data
                        this.user.username = this.user.username.split('@', 1)[0]
                    }
                })
                .catch(e => this.errHandler(e))
        },
        logout: function() {
            axios.get('/logout')
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.user = {}
                        this.loggedIn = false
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
