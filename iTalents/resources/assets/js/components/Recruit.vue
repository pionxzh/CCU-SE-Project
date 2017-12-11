<template lang="pug">
    div    
        v-content
            v-layout(row='' justify-space-between='')

                    v-flex(xs0='')
                    v-flex(xs6='')
                        section
                            .page-title 徵才總覽
                                v-btn.right(color='primary' @click='postNewRecruit' :loading="loading" :disabled="loading") 新增徵才訊息
                            v-card
                                v-list(two-line='')
                                    template(v-for='(item, index) in items')
                                        router-link.no-decoration(:to="{path: 'recruit/edit/' + item.id}")
                                            v-list-tile(avatar='', ripple='', :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.title || '尚未填寫' }}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 
                                                        | {{ item.dpay || '0' }}&nbsp;~&nbsp;{{ item.upay || '0' }}
                                                    v-list-tile-sub-title {{ item.jobname }} 
                                                v-list-tile-action
                                                    v-icon(:color="item.active ? 'teal' : 'grey'") chat_bubble
                                            v-divider(v-if='index + 1 < items.length', :key='item.id')
                    v-flex(xs0='')
        p-footer

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: [],
        loading: false
    }),
    activated() {
        this.checkPermission()
        this.getRecruitList()
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
        checkPermission() {
            console.log(this.$root.user)
            if (this.$root.user.userType !== 2 || !this.$root.user.emailState || !this.$root.user.verify) {
                this.$router.push({name: 'Main'})
            }
        },
        getRecruitList() {
            axios.get('/api/recruit')
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        },
        postNewRecruit() {
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
