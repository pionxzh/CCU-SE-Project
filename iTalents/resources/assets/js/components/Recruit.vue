<template lang='pug'>
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                    v-flex(xs0)
                    v-flex(xs10 lg6)
                        section
                            .page-title {{ $t('recruit.allRecruit') }}
                                v-btn.right(color='primary' @click='askNewRecruit' :loading='loading' :disabled='loading') {{ $t('recruit.postJob') }}
                            v-card(v-if='items')
                                v-list(two-line)
                                    template(v-for='(item, index) in items')
                                        router-link.no-decoration(:to="{path: 'recruit/edit/' + item.id}")
                                            v-list-tile(avatar, ripple, :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.title || $t('recruit.notCompleted') }}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.jobname || $t('recruit.notCompleted') }}
                                                    v-list-tile-sub-title {{ $t('recruit.salary') }}: {{ item.dpay || '0' }}&nbsp;~&nbsp;{{ item.upay || '0' }}
                                                v-list-tile-action
                                                    router-link.no-decoration(:to="{name: 'RecruitMatch', params: {id: item.id}}")
                                                        v-tooltip(top)
                                                            v-btn(flat icon dark color='primary' slot='activator')
                                                                v-icon(:color="!item.active ? 'teal' : 'grey'") chat_bubble
                                                            span {{ $t('recruit.match') }} / {{ $t('recruit.manageRecord') }}
                                                    v-tooltip(top)
                                                        v-btn(flat icon dark color='red' slot='activator' @click.stop='deleteRecruit(item.id)')
                                                            v-icon(color='red') delete
                                                        span {{ $t('common.delete') }}

                                            v-divider(v-if='index + 1 < items.length', :key='item.id')
                    v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: null,
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
        },
        deleteRecruit(id) {
            if (!confirm(this.$t('alert.sureDelete'))) return
            this.loading = true
            axios.post(`/api/recruit/delete/${id}`, {})
                .then(response => {
                    this.loading = false
                    let msg = response.data.stat ? this.$t('recruit.deleteSuccess') : this.$t('recruit.deleteFail')
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
