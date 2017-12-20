<template lang="pug">
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                v-flex(xs0)
                v-flex(xs10 lg6)
                    section(v-if='recruit')
                        p.page-title {{ recruit.title }}
                            v-btn.hidden.right.hide(color='primary' @click='$router.push({name: "Recruit"})' :loading="loading" :disabled="loading") {{ $t('common.back') }}
                            v-btn(large color='yellow' @click='throwResume')
                                v-icon receipt
                                | &nbsp;{{ $t('recruit.throw') }}
                        .recruit-edit-field

                        p.recruit-show-title {{ $t('recruit.JobOpening') }}
                        .recruit-edit-field
                            v-flex(xs12 md6)
                                p.recruit-show-info {{ $t('recruit.jobName') }}: {{ recruit.jobname || $t('recruit.notCompleted') }}
                                p.recruit-show-info {{ $t('recruit.jobType') }}: {{recruit.jobtype === 0 ?  $t('recruit.partTime') : $t('recruit.fullTime') }}
                                p.recruit-show-info {{ $t('recruit.requireLanguage') }}: {{ recruit.lang || $t('recruit.notCompleted') }}
                                p.recruit-show-info {{ $t('recruit.salary') }}: {{ recruit.dpay }} ~ {{recruit.upay}}
                        p.recruit-show-title {{ $t('recruit.jobDescription') }}
                            p.recruit-edit-content.ql-editor(v-html='recruit.jobinfo || $t("recruit.notCompleted")')

                        p.recruit-show-title {{ $t('recruit.jobRequire') }}
                            p.recruit-edit-content.ql-editor(v-html='recruit.jobrequire || $t("recruit.notCompleted")')

                        p.recruit-show-title {{ $t('recruit.benefits') }}
                            p.recruit-edit-content.ql-editor(v-html='recruit.benefits || $t("recruit.notCompleted")')

                        p.recruit-show-title {{ $t('recruit.contact') }}
                            p.recruit-edit-content.ql-editor(v-html='recruit.contact || $t("recruit.notCompleted")')
                v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        recruit: null,
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
                    } else {
                        this.showDialog(this.$t('recruit.notExist'))
                    }
                })
                .catch(e => this.errHandler(e))
        },
        throwResume() {
            axios.post(`/throw`, {rid: this.$route.params.id})
                .then(response => {
                    console.log('Throw Result', response.data)
                    let msg = response.data.stat ? this.$t('alert.submitSuccess') : this.$t('alert.submitFail')
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
