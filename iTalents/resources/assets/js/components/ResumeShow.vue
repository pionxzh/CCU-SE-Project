<template lang="pug">
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                    v-flex(xs)
                    v-flex(xs10 lg6)
                        section(v-if='resume')
                            p.page-title {{ $t('resume.resume') }}
                                router-link.no-decoration(:to="{name: 'ResumeEdit'}")
                                    v-btn.hide.ml-4(color='primary' v-if='!$route.params.id')
                                        v-icon edit
                                        | &nbsp;{{ $t('common.edit') }}
                                v-btn.ml-4(large color='yellow' v-if='$route.params.id' @click='invite')
                                    v-icon card_giftcard
                                    | &nbsp;{{ $t('resume.sendInvite') }}

                            p.recruit-show-title {{ $t('resume.basic') }}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('resume.name') }}: {{ resume.lastName }}{{ resume.firstName || $t("common.notFill") }}
                                    p.recruit-show-info {{ $t('resume.gender') }}: {{ gender || $t("common.notFill") }}
                                    p.recruit-show-info(v-if='($route.params.id && resume.birth) || !$route.params.id') {{ $t('resume.birth') }}: {{ resume.birth || $t("common.notFill") }}
                                    p.recruit-show-info {{ $t('resume.nation') }}: {{ resume.nation || $t("common.notFill") }}
                                    p.recruit-show-info(v-if='($route.params.id && resume.email) || !$route.params.id') {{ $t('resume.email') }}: {{ resume.email || $t("common.notFill") }}
                                    p.recruit-show-info(v-if='($route.params.id && resume.phone) || !$route.params.id') {{ $t('resume.phone') }}: {{ resume.phone || $t("common.notFill") }}
                            p.recruit-show-title {{ $t('resume.jobInfo') }}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('resume.expectedJobName') }}: {{ resume.expectedJobName }}
                                    p.recruit-show-info {{ $t('resume.salaryRequest') }}: {{ resume.salaryFrom }}&nbsp;~&nbsp;{{ resume.salaryTo }}

                            p.recruit-show-title {{ $t('resume.language') }}
                                div.language-show(v-if='resume.language')
                                    div(v-for="(value, key) in resume.language" :key='key' v-if='value')
                                        v-tooltip(top)
                                            v-chip(slot='activator')
                                                v-avatar(:class='langColor[value]')
                                                    span.white--text.headline {{langLevel[value]}}
                                                | {{langMap[key]}}
                                            span {{ abilityLevel[value] }}
                                        
                                p.recruit-edit-content.ql-editor(v-html='$t("common.notFill")' v-if='!resume.language')

                            p.recruit-show-title {{ $t('resume.background') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.background || $t("common.notFill")')

                            p.recruit-show-title {{ $t('resume.skill') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.skill || $t("common.notFill")')

                            p.recruit-show-title {{ $t('resume.certificate') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.certificate || $t("common.notFill")')

                            p.recruit-show-title {{ $t('resume.bio') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.bio || $t("common.notFill")')
                    v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        resume: null,
        genderList: [],
        langColor: ['light-blue', 'light-green', 'yellow accent-4', 'deep-orange', 'pink'],
        langMap: {en: '英語', ch: '中文', jp: '日文', fr: '法語'},
        abilityLevel: ['', '略懂', '中等', '熟練', '精通'],
        langLevel: ['D', 'C', 'B', 'A', 'S'],
        loading: false
    }),
    created() {
        this.genderList.push('')
        this.genderList.push(this.$t('gender.male'))
        this.genderList.push(this.$t('gender.female'))
        this.genderList.push(this.$t('gender.other'))
    },
    activated() {
        this.getResumeInfo()
    },
    computed: {
        gender() {
            return this.genderList[this.resume.gender]
        }
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
            if (!this.$route.params.id) {
                if (this.$root.user.userType !== 1 || !this.$root.user.emailState) {
                    this.$router.push({name: 'Main'})
                }
            } else {
                if (this.$root.user.userType !== 2 || !this.$root.user.emailState || !this.$root.user.verify) {
                    this.$router.push({name: 'Main'})
                }
            }
        },
        getResumeInfo() {
            this.checkPermission()
            let url = this.$route.params.id ? this.$route.params.id : ''
            axios.get(`/api/resume/${url}`)
                .then(response => {
                    console.log('ResumeInfo', response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                        this.resume.language = response.data.language
                    } else {
                        this.showDialog(this.$t('resume.notExist'))
                    }
                })
                .catch(e => this.errHandler(e))
        },
        invite() {
            this.loading = true
            if (!this.$route.query.rid) return this.showDialog('沒有RID~')
            axios.post(`/invite/${this.$route.params.id}`, {rid: this.$route.query.rid})
                .then(response => {
                    console.log(response.data)
                    let msg = response.data.stat ? this.$t('alert.submitSuccess') : this.$t('alert.submitFail')
                    this.showDialog(msg)
                    if (response.data.stat) {
                        this.resume.language = response.data.language
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
