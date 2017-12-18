<template lang='pug'>
    div    
        v-content
            v-layout(row='' justify-space-between='')
                    v-flex(xs0='')
                    v-flex(xs6='')
                        section
                            .page-title 配對結果
                            v-card
                                v-list(two-line='')
                                    template(v-for='(item, index) in items')
                                        router-link.no-decoration(:to="{path: '/resume/' + item.uid + '?rid' + $router.params.id}")
                                            v-list-tile(avatar='', ripple='', :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.lastName }}** · {{ genderList[item.gender] }} · {{item.nation}}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.birth }}{{ $t('recruit.yearsOld') }}
                                                    v-list-tile-sub-title  {{ item.background }}

                                            v-divider(v-if='index + 1 < items.length', :key='item.id')
                    v-flex(xs0='')

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: [],
        genderList: [],
        loading: false
    }),
    created() {
        this.genderList.push('')
        this.genderList.push(this.$t('gender.male'))
        this.genderList.push(this.$t('gender.female'))
        this.genderList.push(this.$t('gender.other'))
    },
    activated() {
        this.getMatchResult()
    },
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
        checkPermission() {
            if (this.$root.user.userType !== 2 || !this.$root.user.emailState || !this.$root.user.verify) {
                this.$router.push({name: 'Main'})
            }
        },
        getMatchResult() {
            this.checkPermission()
            axios.post('/api/recruit/match', {rid: this.$route.params.id})
                .then(response => {
                    console.log('Match Result', response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
