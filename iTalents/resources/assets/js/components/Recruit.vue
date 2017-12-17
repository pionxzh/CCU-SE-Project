notCompleted<template lang='pug'>
    div
        v-content
            v-layout(row='' justify-space-between='')

                    v-flex(xs0='')
                    v-flex(xs6='')
                        section
                            .page-title {{ $t('common.allRecruit') }}
                                v-btn.right(color='primary' @click='askNewRecruit' :loading='loading' :disabled='loading') {{ $t('common.postJob') }}
                            v-card
                                v-list(two-line='')
                                    template(v-for='(item, index) in items')
                                        router-link.no-decoration(:to="{path: 'recruit/edit/' + item.id}")
                                            v-list-tile(avatar='', ripple='', :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.title || {{ $t('common.notCompleted') }} }}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.jobname || {{ $t('common.notCompleted') }} }}
                                                    v-list-tile-sub-title {{ $t('common.salary') }}: {{ item.dpay || '0' }}&nbsp;~&nbsp;{{ item.upay || '0' }}
                                                v-list-tile-action
                                                    v-tooltip(top)
                                                        v-btn(flat icon dark color='primary' slot='activator')
                                                            v-icon(:color="item.active ? 'teal' : 'grey'") chat_bubble
                                                        span 配對結果 / 新進履歷
                                                    v-tooltip(top)
                                                        v-btn(flat icon dark color='red' slot='activator')
                                                            v-icon(color='red') delete
                                                        span {{ $t('common.delete') }}

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
        this.getRecruitList()
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
        getRecruitList() {
            this.checkPermission()
            axios.get('/api/recruit')
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        },
        askNewRecruit() {
            this.loading = true
            axios.post('/api/recruit', {})
                .then(response => {
                    this.loading = false
                    if (response.data.stat) {
                        this.$router.push(`recruit/edit/${response.data.rid}`)
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
