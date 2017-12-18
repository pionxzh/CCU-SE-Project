<template lang="pug">
    div
        v-content
            v-layout(row justify-space-between)
                    v-flex(xs)
                    v-flex(xs10 lg6)
                        section
                            p.page-title 履歷
                                router-link.no-decoration(:to="{name: 'ResumeEdit'}")
                                    v-btn.hide.ml-4(color='primary' v-if='!$route.params.id')
                                        v-icon edit
                                        | 編輯
                                v-btn.ml-4(large color='yellow' v-if='$route.params.id' @click='invite')
                                    v-icon card_giftcard
                                    | &nbsp;寄出邀請

                            p.recruit-show-title 基本資料
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('resume.name') }}: {{ resume.lastName }} {{ resume.firstName }}
                                    p.recruit-show-info {{ $t('resume.gender') }}: {{ gender }}
                                    p.recruit-show-info {{ $t('resume.birth') }}: {{ resume.birth }}
                                    p.recruit-show-info {{ $t('resume.nation') }}: {{ resume.nation }}
                                    p.recruit-show-info {{ $t('resume.email') }}: {{ resume.email }}
                                    p.recruit-show-info {{ $t('resume.phone') }}: {{ resume.phone }}
                            p.recruit-show-title {{ $t('resume.jobInfo') }}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('resume.expectedJobName') }}: {{ resume.expectedJobName }}
                                    p.recruit-show-info {{ $t('resume.salaryRequest') }}: {{ resume.salaryFrom }}&nbsp;~&nbsp;{{ resume.salaryTo }}

                            p.recruit-show-title {{ $t('resume.language') }}
                                div.language-show
                                        v-chip(v-for="(value, key) in resume.language" :key='key')
                                            v-avatar(:class='langColor[value]')
                                                span.white--text.headline {{langLevel[value]}}
                                            | {{langMap[key]}}

                            p.recruit-show-title {{ $t('resume.background') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.background')

                            p.recruit-show-title {{ $t('resume.skill') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.skill')

                            p.recruit-show-title {{ $t('resume.certificate') }}
                                p.recruit-edit-content.ql-editor(v-html='resume.certificate')

                            p.recruit-show-title {{ $t('resume.bio') }}自傳
                                p.recruit-edit-content.ql-editor(v-html='resume.bio')
                    v-flex(xs0)
        p-footer

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        resume: {
            firstName: '',
            lastName: '',
            gender: null,
            birth: null,
            nation: '',
            email: '',
            phone: '',

            expectedJobName: '',
            salaryFrom: null,
            salaryTo: null,

            background: '',
            language: null,
            skill: '',
            certificate: '',
            bio: ''
        },
        genderList: ['', '男', '女', '其他'],
        langColor: ['light-blue', 'light-green', 'yellow accent-4', 'deep-orange', 'pink'],
        langMap: {en: '英語', ch: '中文', jp: '日文', fr: '法語'},
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
                    console.log(response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                        this.resume.language = response.data.language
                    }
                })
                .catch(e => this.errHandler(e))
        },
        invite() {
            this.loading = true
            axios.post(`/invite/${this.$route.params.id}`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.resume.language = response.data.language
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
