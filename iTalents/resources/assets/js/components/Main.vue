<template lang="pug">
        v-content
            section
                v-parallax(src='https://i.imgur.com/zhgtd7S.jpg' height='600')
                    v-layout.white--text(column align-center justify-center)
                        h1.white--text.mb-2.display-1.text-xs-center 國際人才資料庫
                        .subheading.mb-3.text-xs-center iTalents Database
                        router-link.no-decoration(:to="{name: 'ResumeShow'}" v-if='$root.user.userType === 1')
                            v-btn.green.lighten-2.mt-5(dark large) {{ $t('main.start') }}
                        router-link.no-decoration(:to="{name: 'Recruit'}" v-if='$root.user.userType === 2')
                            v-btn.blue.lighten-2.mt-5(dark large) {{ $t('main.start') }}
                        router-link.no-decoration(:to="{name: 'Login'}" v-if='!$root.user.username.length')
                            v-btn.blue.lighten-2.mt-5(dark large) {{ $t('main.start') }}
                        
            section
                v-container(grid-list-xl)
                    v-layout.my-5(row, wrap, justify-center)
                        v-flex(xs12, sm4)
                            v-card.elevation-0.transparent
                                v-card-title.layout.justify-center(primary-title)
                                    .headline 平台介紹
                                v-card-text
                                    | Cras facilisis mi vitae nunc lobortis pharetra. Nulla volutpat tincidunt ornare.
                                    | Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                                    | Nullam in aliquet odio. Aliquam eu est vitae tellus bibendum tincidunt. Suspendisse potenti.
                        v-flex(xs12 sm4 offset-sm1)
                            v-card.elevation-0.transparent
                                v-card-title.layout.justify-center(primary-title)
                                    .headline Contact us
                                v-card-text Cras facilisis mi vitae nunc lobortis pharetra. Nulla volutpat tincidunt ornare.
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

</template>

<script>
export default {
    data: () => ({
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
        }
    }
}
</script>
