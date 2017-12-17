<template lang="pug">
    div
        v-content
            v-layout(row justify-space-between)
                    v-flex(xs0)
                    v-flex(xs10 lg6)
                        section
                            p.page-title {{ recruit.title }}
                                v-btn.hidden.right.hide(color='primary' @click='$router.push({name: "Recruit"})' :loading="loading" :disabled="loading") {{ $t('common.back') }}
                                v-btn(color='yellow' @click='$router.push({name: "Recruit"})' large)
                                    v-icon receipt
                                    | &nbsp;投履歷
                            .recruit-edit-field

                            p.recruit-show-title {{ $t('common.JobOpening') }}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('common.jobName') }}: {{ recruit.jobname || {{ $t('common.notCompleted') }} }}
                                    p.recruit-show-info {{ $t('common.jobType') }}: {{ recruit.jobtype === 0 ?  {{ $t('common.parttime') }}: {{ $t('common.fulltime') }} }}
                                    p.recruit-show-info {{ $t('common.requirementLang') }}: {{ recruit.lang || {{ $t('common.notCompleted') }} }}
                                    p.recruit-show-info {{ $t('common.salary') }}: {{ recruit.dpay }} ~ {{recruit.upay}}
                            p.recruit-show-title {{ $t('common.jobDescription') }}
                                p.recruit-edit-content.ql-editor(v-html='recruit.jobinfo')

                            p.recruit-show-title {{ $t('common.qualifications') }}
                                p.recruit-edit-content.ql-editor(v-html='recruit.jobrequire || {{ $t("common.notCompleted") }}')


                            p.recruit-show-title {{ $t('common.companyBenefits') }}
                                p.recruit-edit-content.ql-editor(v-html='recruit.benefits || {{ $t("common.notCompleted") }}')

                            p.recruit-show-title {{ $t('common.contactInform') }}
                                p.recruit-edit-content.ql-editor(v-html='recruit.contact || {{ $t("common.notCompleted") }}')
                    v-flex(xs0)
        p-footer

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        recruit: {
            title: '',
            jobname: '',
            jobtype: '',
            lang: '',
            upay: null,
            dpay: null,
            jobinfo: '',
            jobrequire: '',
            benefits: '',
            contact: '',
            is_complete: ''
        },
        loading: false
    }),
    activated() {
        this.getRecruitInfo()
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
        getRecruitInfo() {
            axios.get(`/api/recruit/${this.$route.params.id}`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.recruit = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
