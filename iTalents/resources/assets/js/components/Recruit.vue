<template lang="pug">
    div    
        v-content
            section
                v-card
                    v-list(two-line='')
                        template(v-for='(item, index) in items')
                            v-list-tile(avatar='', ripple='', :key='item.title')
                                v-list-tile-content
                                    v-list-tile-title {{ item.title }}
                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.headline }}
                                    v-list-tile-sub-title {{ item.subtitle }}
                                v-list-tile-action
                                    v-list-tile-action-text {{ item.action }}
                                    v-icon(:color="item.active ? 'teal' : 'grey'") chat_bubble
                            v-divider(v-if='index + 1 < items.length', :key='item.title')

        v-footer.grey.darken-2
                v-layout(row='', wrap='', align-center='')
                    v-flex(xs12='')
                        .black--text.ml-3 CopyRight @2017

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: [],
        loading: false
    }),
    created() {
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
        getRecruitList() {
            axios.get('/api/recruit')
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        console.log(response.data)
                        this.items = response.data
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
