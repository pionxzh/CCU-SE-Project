<template lang="pug">
    v-content
            v-layout(row justify-space-between)
                    v-flex(xs0)
                    v-flex(xs10 lg6)
                        section.mb-5
                            v-breadcrumbs(divider="/")
                                v-breadcrumbs-item(v-for="item in footprint" :key="item.text" :disabled="item.disabled") {{ item.text }}
                            p.page-title 徵才訊息詳情 (完成度{{ recruit.is_complete ? 'O' : 'X' }})
                                v-btn.right(color='primary' @click='$router.push({name: "Recruit"})') 返回列表

                            p.recruit-edit-title 徵才資訊
                                v-btn.white--text(flat icon color='primary' @click='edit.field = true' v-if='!edit.field')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.field = false' v-if='edit.field')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='saveField' :loading="loading" :disabled="loading" v-if='edit.field')
                                    v-icon done
                            .recruit-edit-field(v-if='!edit.field')
                                v-flex(xs12 md6)
                                    p.recruit-show-info 標題: {{ recruit.title || '尚未填寫'}}
                                    p.recruit-show-info 職位名稱: {{ recruit.jobname || '尚未填寫'}}
                                    p.recruit-show-info 工作類型: {{ recruit.jobtype === 0 ? '兼職' : '全職' }}
                                    p.recruit-show-info 語言需求: {{ recruit.lang || '尚未填寫'}}
                                    p.recruit-show-info 薪資條件: {{ recruit.dpay }} ~ {{recruit.upay}}
                            .recruit-edit-field(v-if='edit.field')
                                v-flex(xs12 md6)
                                    v-text-field(type='text' label='標題' v-model.trim='recruit.title')
                                    v-text-field(type='text' label='職位名稱' v-model.trim='recruit.jobname')
                                    v-text-field(type='text' label='語言條件' v-model.trim='recruit.lang')
                                    v-layout(wrap)
                                        v-flex(xs5)
                                            v-text-field(type='number' label='最低薪資' v-model.trim='recruit.dpay')
                                        v-flex(xs0) 
                                            | ~
                                        v-flex(xs5)
                                            v-text-field(type='number' label='最高薪資' v-model.trim='recruit.upay')

                            p.recruit-edit-title 工作內容
                                v-btn.white--text(flat icon color='primary' @click='edit.jobinfo = true' v-if='!edit.jobinfo')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.jobinfo = false' v-if='edit.jobinfo')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("jobinfo")' :loading="loading" :disabled="loading" v-if='edit.jobinfo')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.jobinfo' v-html='recruit.jobinfo')
                            quill-editor(:content="recruit.jobinfo" :options="editorOption" @change="onEditorChange($event, 'jobinfo')" v-if='edit.jobinfo')

                            p.recruit-edit-title 條件要求
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

                            p.recruit-edit-title 公司福利
                                v-btn.white--text(flat icon color='primary' @click='edit.benefits = true' v-if='!edit.benefits')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.benefits = false' v-if='edit.benefits')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("benefits")' :loading="loading" :disabled="loading" v-if='edit.benefits')
                                    v-icon done

                                p.recruit-edit-content.ql-editor(v-if='!edit.benefits' v-html='recruit.benefits')
                            quill-editor(:content="recruit.benefits" :options="editorOption" @change="onEditorChange($event, 'benefits')" v-if='edit.benefits')

                            p.recruit-edit-title 聯絡方式
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
                title: '提示',
                text: msg,
                buttons: [{title: '關閉'}]
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
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    if (response.data.is_complete != null) {
                        this.recruit.is_complete = response.data.is_complete
                        if (response.data.is_complete) msg = '所有資訊已填寫完整! 徵才訊息將很快公開'
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
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    if (response.data.is_complete != null) {
                        this.recruit.is_complete = response.data.is_complete
                        if (response.data.is_complete) msg = '所有資訊已填寫完整! 徵才訊息將很快公開'
                    }
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
