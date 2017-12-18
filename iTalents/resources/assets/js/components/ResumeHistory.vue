<template lang='pug'>
    div    
        v-content
            v-layout(row='' justify-space-between='')
                    v-flex(xs0='')
                    v-flex(xs6='')
                        section
                            .page-title 投遞紀錄
                            v-card
                                v-list(two-line='')
                                    template(v-for='(item, index) in items')
                                        router-link.no-decoration(:to="{path: 'recruit/' + item.rid}")
                                            v-list-tile(avatar='', ripple='', :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.title || '尚未填寫' }}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.jobname || '尚未填寫' }}
                                                    v-list-tile-sub-title 薪資: {{ item.dpay || '0' }}&nbsp;~&nbsp;{{ item.upay || '0' }}

                                            v-divider(v-if='index + 1 < items.length', :key='item.id')
                    v-flex(xs0='')

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: [],
        loading: false
    }),
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
            if (this.$root.user.userType !== 1 || !this.$root.user.emailState) {
                this.$router.push({name: 'Main'})
            }
        },
        getMatchResult() {
            this.checkPermission()
            axios.post('/api/resume/history', {})
                .then(response => {
                    console.log('Match History', response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
