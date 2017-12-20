<template lang='pug'>
    v-content
        v-container(fluid fill-height)
            v-layout(row='' justify-space-between='')
                    v-flex(xs0='')
                    v-flex(xs6='')
                        section
                            .page-title {{ $t('resume.match') }}
                            v-btn(color='yellow' @click='getMatchResult' v-if='showMatchBtn')
                                v-icon done
                                | {{ $t('resume.showMatch') }}
                            v-card(v-if='matches')
                                v-list(two-line='')
                                    template(v-for='(item, index) in matches')
                                        router-link.no-decoration(:to="{path: 'recruit/' + item.rid}")
                                            v-list-tile(avatar='', ripple='', :key='item.title')
                                                v-list-tile-content
                                                    v-list-tile-title {{ item.title || $t("common.notFill") }}
                                                    v-list-tile-sub-title.grey--text.text--darken-4 {{ item.jobname || $t("common.notFill") }}
                                                    v-list-tile-sub-title {{ $t('resume.pay') }}: {{ item.dpay || '0' }}&nbsp;~&nbsp;{{ item.upay || '0' }}

                                            v-divider(v-if='index + 1 < matches.length', :key='item.id')
                            .page-title {{ $t('resume.manageRecord') }}
                            v-data-table.elevation-1(v-if='items' v-bind:headers="headers" :items="items" hide-actions)
                                template(slot="items" slot-scope="props")
                                    td.text-xs-left {{ props.item.title }}
                                    td.text-xs-left {{ props.item.jobname }}
                                    td.text-xs-left(v-html='props.item.dpay + "<br>&nbsp;~&nbsp;<br>" +  props.item.upay')
                                    td.text-xs-left.green--text {{ props.item.stat }}
                                    td.text-xs-left.red--text.text--darken-4 {{ props.item.updated_at }}
                                    td.text-xs-left
                                        router-link.no-decoration(:to="{path: '/recruit/' + props.item.rid}")
                                            v-tooltip(top)
                                                v-btn(flat icon color='primary' slot='activator')
                                                    v-icon visibility
                                                span {{ $t('common.see') }}
                                        v-tooltip(top)
                                            v-btn(icon dark color='green' @click='acceptInvite(props.index, props.item.rid)' v-if='props.item.gotInvite' slot='activator')
                                                v-icon done
                                            span {{ $t('resume.acceptInvite') }}
                                        v-tooltip(top)
                                            v-btn(icon dark color='red' @click='refuseInvite(props.index, props.item.rid)' v-if='props.item.gotInvite' slot='activator')
                                                v-icon clear
                                            span {{ $t('resume.refuseInvite') }}
                    v-flex(xs0='')
        p-footer

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        items: null,
        matches: null,
        showMatchBtn: true,
        headers: [
            {text: '標題', value: 'title', align: 'left'},
            {text: '職位', value: 'jobname', align: 'left'},
            {text: '薪資', value: 'dpay', align: 'left'},
            {text: '狀態', value: 'stat', align: 'left'},
            {text: '操作紀錄', value: 'updated_at', align: 'left'},
            {text: '動作', sortable: false, align: 'left'}
        ],
        loading: false
    }),
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
            if (this.$root.user.userType !== 1 || !this.$root.user.emailState) {
                this.$router.push({name: 'Main'})
            }
        },
        getHistory() {
            this.checkPermission()
            axios.post('/api/resume/history', {})
                .then(response => {
                    console.log('History', response.data)
                    if (response.data.stat) {
                        this.items = response.data.data
                        this.items.forEach(element => {
                            element.updated_at = this.getDateDiff(element.updated_at)
                            if (element.employeeCheck === 1 && element.employerCheck === 1) element.stat = '履歷已送出'
                            else if (element.employeeCheck === 0 && element.employerCheck === 1) element.stat = '已婉拒邀約'
                            else if (element.employeeCheck === -1 && element.employerCheck === 1) {
                                element.gotInvite = true
                                element.stat = '收到廠商邀請'
                            }
                        })
                    }
                })
                .catch(e => this.errHandler(e))
        },
        getMatchResult() {
            axios.post('/api/resume/match', {})
                .then(response => {
                    console.log('Match Result', response.data)
                    if (response.data.stat) {
                        this.showMatchBtn = false
                        this.matches = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        },
        acceptInvite(index, id) {
            if (!confirm(this.$t('alert.sureAccept'))) return
            axios.post(`/throw`, {rid: id})
                .then(response => {
                    console.log('acceptInvite', response.data)
                    let msg = response.data.stat ? this.$t('alert.acceptInviteSuccess') : this.$t('alert.acceptInviteFail')
                    this.showDialog(msg)
                    if (response.data.stat) {
                        this.items[index].gotInvite = false
                        this.items[index].stat = '履歷已送出'
                        this.$forceUpdate()
                    }
                })
                .catch(e => this.errHandler(e))
        },
        refuseInvite(index, id) {
            if (!confirm(this.$t('alert.sureRefuse'))) return
            axios.post(`/refuse`, {rid: id})
                .then(response => {
                    console.log('refuseInvite', response.data)
                    let msg = response.data.stat ? this.$t('alert.refuseInviteSuccess') : this.$t('alert.refuseInviteFail')
                    this.showDialog(msg)
                    if (response.data.stat) {
                        this.items[index].gotInvite = false
                        this.items[index].stat = '已婉拒邀約'
                        this.$forceUpdate()
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
            else if (hourC >= 1) result = '' + parseInt(hourC) + '小時前'
            else if (minC >= 1) result = '' + parseInt(minC) + '分鐘前'
            else result = '刚刚'

            return result
        }
    }
}
</script>
