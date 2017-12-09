<template lang="pug">
    v-app(light='')
        v-toolbar.primary(color="blue darken-3" dark="" app="" clipped-left="" fixed="")
            v-toolbar-title iTalent
            v-spacer
            v-text-field(light="" solo prepend-icon="search" placeholder="Search" style="max-width: 500px; min-width: 128px")
            v-spacer
            .d-flex(align-center="" style="margin-left: auto")
                router-link(:to="{name: 'Login'}")
                    v-btn 登入
                    
                v-btn(icon="")
                    v-icon notifications
        v-content
            section
                v-parallax(src='https://i.imgur.com/zhgtd7S.jpg', height='600')
                    v-layout.white--text(column='', align-center='', justify-center='')
                        h1.white--text.mb-2.display-1.text-xs-center 國際人才資料庫
                        .subheading.mb-3.text-xs-center International learnTsai database
                        v-btn.blue.lighten-2.mt-5(dark='', large='', href='/pre-made-themes') 開始使用
            section
                v-container(grid-list-xl='')
                    v-layout.my-5(row='', wrap='', justify-center='')
                        v-flex(xs12='', sm4='')
                            v-card.elevation-0.transparent
                                v-card-title.layout.justify-center(primary-title='')
                                    .headline Company info
                                v-card-text
                                    | Cras facilisis mi vitae nunc lobortis pharetra. Nulla volutpat tincidunt ornare.
                                    | Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                                    | Nullam in aliquet odio. Aliquam eu est vitae tellus bibendum tincidunt. Suspendisse potenti.
                        v-flex(xs12='', sm4='', offset-sm1='')
                            v-card.elevation-0.transparent
                                v-card-title.layout.justify-center(primary-title='')
                                    .headline Contact us
                                v-card-text
                                    | Cras facilisis mi vitae nunc lobortis pharetra. Nulla volutpat tincidunt ornare.
                                v-list.transparent
                                    v-list-tile
                                        v-list-tile-action
                                            v-icon.blue--text.text--lighten-2 phone
                                        v-list-tile-content
                                            v-list-tile-title 777-867-5309
                                    v-list-tile
                                        v-list-tile-action
                                            v-icon.blue--text.text--lighten-2 place
                                        v-list-tile-content
                                            v-list-tile-title Chayi, Taiwan
                                    v-list-tile
                                        v-list-tile-action
                                            v-icon.blue--text.text--lighten-2 email
                                        v-list-tile-content
                                            v-list-tile-title test@google.com
            v-footer.grey.darken-2
                v-layout(row='', wrap='', align-center='')
                    v-flex(xs12='')
                        .black--text.ml-3
                            | CopyRight @2017

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        user: {
            logined: false,
            username: '用戶名不存在',
            user_type: 0
        },
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
        getUser: function() {
            this.loading = true
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data
                    this.$root.user = response.data
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
