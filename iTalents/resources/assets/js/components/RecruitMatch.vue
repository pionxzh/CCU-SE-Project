<template lang='pug'>
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                    v-flex.hidden-sm-and-down(xs0)
                    v-flex(xs10 lg6)
                        section
                            .page-title {{ $t('recruit.match') }}
                            v-btn(color='yellow' @click='getMatchResult' v-if='showMatchBtn')
                                v-icon done
                                | {{ $t('recruit.showMatch') }}
                            v-card(v-if='matches')
                                v-list(two-line)
                                    template(v-for='(item, index) in matches')
                                        router-link.no-decoration(:to="{path: '/resume/' + item.uid + '?rid=' + $route.params.id}")
                                            v-list-tile(avatar ripple, :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.lastName }}** · {{ genderList[item.gender] }} · {{item.nation}}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.birth }}{{ $t('recruit.yearsOld') }}
                                                    v-list-tile-sub-title  {{ item.background }}

                                            v-divider(v-if='index + 1 < matches.length' :key='item.id')
                            
                            .page-title 徵才記錄管理
                            v-data-table.elevation-1(v-if='items' v-bind:headers="headers" :items="items" hide-actions)
                                template(slot="items" slot-scope="props")
                                    td.text-xs-left {{ props.item.lastName }}**
                                    td.text-xs-left {{ genderList[props.item.gender] }}
                                    td.text-xs-left {{ props.item.birth }}{{ $t('recruit.yearsOld') }}
                                    td.text-xs-left(v-html='props.item.background')
                                    td.text-xs-left.green--text {{ props.item.stat }}
                                    td.text-xs-left.red--text.text--darken-4 {{ props.item.updated_at }}
                                    td.text-xs-left
                                        router-link.no-decoration(:to="{path: '/resume/' + props.item.uid}")
                                            v-btn(dark color='primary') {{ $t('common.see') }}
                            
                    v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: null,
        matches: null,
        showMatchBtn: true,
        headers: [
            {text: '求職者', value: 'name', align: 'left'},
            {text: '性別', value: 'gender', align: 'left'},
            {text: '年紀', value: 'age', align: 'left'},
            {text: '背景', value: 'background', align: 'left'},
            {text: '狀態', value: 'stat', align: 'left'},
            {text: '操作紀錄', value: 'updated_at', align: 'left'},
            {text: '動作', sortable: false, align: 'left'}
        ],
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
        this.getHistory()
    },
    computed: {
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
        getHistory() {
            this.checkPermission()
            axios.post('/api/recruit/history', {rid: this.$route.params.id})
                .then(response => {
                    console.log('History', response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                        this.items.forEach(element => {
                            element.updated_at = this.getDateDiff(element.updated_at)
                            if (element.employeeCheck === 1 && element.employerCheck === 1) element.stat = '邀請已收到履歷回覆'
                            else if (element.employeeCheck === -1 && element.employerCheck === 1) element.stat = '等待對方回應中'
                            else if (element.employeeCheck === 0 && element.employerCheck === 1) element.stat = '尷尬'
                        })
                    }
                })
                .catch(e => this.errHandler(e))
        },
        getMatchResult() {
            axios.post('/api/recruit/match', {rid: this.$route.params.id})
                .then(response => {
                    console.log('Match Result', response.data)
                    if (response.data.stat) {
                        this.showMatchBtn = false
                        if (response.data.data.length) this.matches = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        },
        getDateDiff(dateTimeStamp) {
            let minute = 60000
            let hour = minute * 60
            let day = hour * 24
            let month = day * 30
            let now = new Date().getTime()
            dateTimeStamp = new Date(dateTimeStamp).getTime()
            let diffValue = now - dateTimeStamp
            if (diffValue < 0) return

            let monthC = diffValue / month
            let weekC = diffValue / (7 * day)
            let dayC = diffValue / day
            let hourC = diffValue / hour
            let minC = diffValue / minute
            let result = ''
            if (monthC >= 1) result = '' + parseInt(monthC) + '月前'
            else if (weekC >= 1) result = '' + parseInt(weekC) + '周前'
            else if (dayC >= 1) result = '' + parseInt(dayC) + '天前'
            else if (hourC >= 1) result = '' + parseInt(hourC) + '小时前'
            else if (minC >= 1) result = '' + parseInt(minC) + '分钟前'
            else result = '刚刚'

            return result
        }
    }
}
</script>
