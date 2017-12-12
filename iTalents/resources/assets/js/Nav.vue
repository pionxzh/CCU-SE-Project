<template lang="pug">
    v-toolbar.primary(color="blue darken-3" dark="" app="" clipped-left="" fixed="")
        router-link.no-decoration(:to="{name: 'Main'}")
            v-toolbar-title.f27 iTalent
        v-spacer
        v-text-field(light="" solo prepend-icon="search" placeholder="Search" style="max-width: 500px; min-width: 128px")
        v-spacer
        .d-flex(align-center="" style="margin-left: auto" v-if='!$root.user.username.length')
            router-link.no-decoration(:to="{name: 'Login'}")
                v-btn 登入/註冊
        v-menu(v-if='$root.user.username.length' offset-y)
            v-btn.mr-5(slot="activator") {{ username }}
            v-list
                router-link.no-decoration(:to="{name: 'User'}")
                    v-list-tile.list__tile--link
                        v-list-tile-title 會員中心
                router-link.no-decoration(:to="{name: 'ResumeShow'}" v-if='$root.user.userType === 1')
                    v-list-tile.list__tile--link
                        v-list-tile-title 個人履歷
                router-link.no-decoration(:to="{name: 'Recruit'}" v-if='$root.user.userType === 2')
                    v-list-tile.list__tile--link
                        v-list-tile-title 徵才列表
                v-divider
                v-list-tile.list__tile--link(@click='logout')
                    v-list-tile-title 登出

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        loggedIn: false
    }),
    computed: {
        username() {
            return this.$root.user.username.length ? this.$root.user.username.split('@', 1)[0] : ''
        }
    },
    methods: {
        errHandler (e) {
            console.log(e.message)
        },
        logout: function() {
            axios.get('/logout')
                .then(response => {
                    if (response.data.stat) {
                        window.location.href = '/main'
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
