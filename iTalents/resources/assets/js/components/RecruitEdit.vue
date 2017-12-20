<template lang="pug">
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                    v-flex(xs0)
                    v-flex(xs10 lg6)
                        section.mb-5
                            v-breadcrumbs(divider="/")
                                v-breadcrumbs-item(v-for="item in footprint" :key="item.text" :disabled="item.disabled") {{ item.text }}
                            p.page-title {{ $t('recruit.JobOpening') }} ({{ $t('recruit.completeness') }} {{ recruit.is_complete ? 'O' : 'X' }})
                                v-btn.right(color='primary' @click='$router.push({name: "Recruit"})') {{ $t('common.back')}}

                            p.recruit-edit-title {{ $t('recruit.JobOpening') }}
                                v-btn.white--text(flat icon color='primary' @click='edit.field = true' v-if='!edit.field')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.field = false' v-if='edit.field')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='saveField' :loading="loading" :disabled="loading" v-if='edit.field')
                                    v-icon done
                            .recruit-edit-field(v-if='!edit.field')
                                v-flex(xs12 md6)
                                    p.recruit-show-info {{ $t('recruit.jobTitle') }}: {{ recruit.title || $t('recruit.notCompleted') }}
                                    p.recruit-show-info {{ $t('recruit.jobName') }}: {{ recruit.jobname || $t('recruit.notCompleted') }}
                                    p.recruit-show-info {{ $t('recruit.jobType') }}: {{ recruit.jobtype === 0 ? $t('recruit.partTime') : $t('recruit.fullTime') }}
                                    p.recruit-show-info {{ $t('recruit.requireLanguage') }}: {{ recruit.lang || $t('recruit.notCompleted') }}
                                    p.recruit-show-info {{ $t('recruit.salary') }}: {{ recruit.dpay }} ~ {{recruit.upay}}
                            .recruit-edit-field(v-if='edit.field')
                                v-flex(xs12 md6)
                                    v-text-field(type='text' label='$t("recruit.jobTitle")' v-model.trim='recruit.title')
                                    v-select(v-bind:items="jobList" v-model='recruit.jobname' :label='$t("recruit.jobName")' single-line bottom)
                                    v-text-field(type='text' label='$t("recruit.requireLanguage")' v-model.trim='recruit.lang')
                                    v-layout(wrap)
                                        v-flex(xs5)
                                            v-text-field(type='number' :label='$t("recruit.dpay")' v-model.trim='recruit.dpay')
                                        v-flex(xs0 style='margin=20px 0 0 20px;') ~
                                        v-flex(xs5)
                                            v-text-field(type='number' :label='$t("recruit.upay")' v-model.trim='recruit.upay')

                            p.recruit-edit-title {{ $t('recruit.jobinfo') }}
                                v-btn.white--text(flat icon color='primary' @click='edit.jobinfo = true' v-if='!edit.jobinfo')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.jobinfo = false' v-if='edit.jobinfo')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("jobinfo")' :loading="loading" :disabled="loading" v-if='edit.jobinfo')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.jobinfo' v-html='recruit.jobinfo')
                            quill-editor(:content="recruit.jobinfo" :options="editorOption" @change="onEditorChange($event, 'jobinfo')" v-if='edit.jobinfo')

                            p.recruit-edit-title {{ $t('recruit.jobRequire') }}
                                v-btn.white--text(flat icon color='primary' @click='edit.jobrequire = true' v-if='!edit.jobrequire')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.jobrequire = false' v-if='edit.jobrequire')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("jobrequire")' :loading="loading" :disabled="loading" v-if='edit.jobrequire')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.jobrequire' v-html='recruit.jobrequire')
                            quill-editor(:content="recruit.jobrequire"
                                :options="editorOption"
                                @change="onEditorChange($event, 'jobrequire')"
                                v-if='edit.jobrequire')

                            p.recruit-edit-title {{ $t('recruit.benefits') }}
                                v-btn.white--text(flat icon color='primary' @click='edit.benefits = true' v-if='!edit.benefits')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.benefits = false' v-if='edit.benefits')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("benefits")' :loading="loading" :disabled="loading" v-if='edit.benefits')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.benefits' v-html='recruit.benefits')
                            quill-editor(:content="recruit.benefits" :options="editorOption" @change="onEditorChange($event, 'benefits')" v-if='edit.benefits')

                            p.recruit-edit-title {{ $t('recruit.contact' )}}
                                v-btn.white--text(flat icon color='primary' @click='edit.contact = true' v-if='!edit.contact')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.contact = false' v-if='edit.contact')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("contact")' :loading="loading" :disabled="loading" v-if='edit.contact')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.contact' v-html='recruit.contact')
                            quill-editor(:content="recruit.contact" :options="editorOption" @change="onEditorChange($event, 'contact')" v-if='edit.contact')
                    v-flex(xs0)

</template>

<script>
import axios from 'axios'
import { quillEditor } from 'vue-quill-editor'

export default {
    components: {
        quillEditor
    },
    data: () => ({
        footprint: [
            {text: '首頁', link: 'Main', disabled: false},
            {text: '徵才列表', link: 'Recruit', disabled: false},
            {text: '修改徵才訊息', link: 'RecruitEdit', disabled: true}
        ],
        recruit: {
            title: '',
            jobname: '',
            lang: '',
            upay: null,
            dpay: null,
            jobinfo: '',
            jobrequire: '',
            benefits: '',
            contact: '',
            is_complete: ''
        },
        jobList: [
            {text: '網頁工程師', value: '網頁工程師'},
            {text: '電機工程技術員', value: '電機工程技術員'},
            {text: '心理學研究人員', value: '心理學研究人員'},
            {text: '會計師', value: '會計師'},
            {text: '金融營業員', value: '金融營業員'}
        ],
        edit: {
            field: false,
            jobinfo: false,
            jobrequire: false,
            benefits: false,
            contact: false
        },
        editorOption: {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{ 'size': [false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, false] }],
                    ['link', 'image']
                ]
            },
            placeholder: 'Type here...'
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
        checkPermission() {
            if (this.$root.user.userType !== 2 || !this.$root.user.emailState || !this.$root.user.verify) {
                this.$router.push({name: 'Main'})
            }
        },
        onEditorChange(event, fieldName) {
            this.recruit[fieldName] = event.html
        },
        getRecruitInfo() {
            this.checkPermission()
            axios.get(`/api/recruit/${this.$route.params.id}`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.recruit = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        },
        save(fieldName) {
            this.loading = true
            axios.post(`/recruit/${this.$route.params.id}/${fieldName}`, {data: this.recruit[fieldName]})
                .then(response => {
                    this.loading = false
                    this.edit[fieldName] = false
                    let msg = response.data.stat ? this.$t('common.saveSucess') : this.$t('common.saveFail')
                    if (response.data.is_complete != null) {
                        this.recruit.is_complete = response.data.is_complete
                        if (response.data.is_complete) msg = this.$t('common.dataCompleted')
                    }
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        },
        saveField() {
            this.loading = true
            axios.post(`/recruit/${this.$route.params.id}/field`, {
                title: this.recruit.title,
                jobname: this.recruit.jobname,
                lang: this.recruit.lang,
                upay: this.recruit.upay,
                dpay: this.recruit.dpay
            })
                .then(response => {
                    this.loading = false
                    let msg = response.data.stat ? this.$t('common.saveSucess') : this.$t('common.saveFail')
                    if (response.data.is_complete != null) {
                        this.recruit.is_complete = response.data.is_complete
                        if (response.data.is_complete) msg = this.$t('alert.dataCompleted')
                    }
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
