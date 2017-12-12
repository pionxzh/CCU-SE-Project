<template lang="pug">
    div    
        v-content
            v-layout(row justify-space-between)
                    v-flex(xs4)
                        v-navigation-drawer(permanent, light)
                            v-toolbar(flat)
                                v-list
                                    v-list-tile
                                        v-list-tile-title.title 類別
                            v-divider
                            v-list.pt-0(dense)
                                v-list-tile(v-for='(item, key) in items', :key='item.title' @click='toggleTab(key)')
                                    v-list-tile-action
                                        v-icon {{ item.icon }}
                                    v-list-tile-content
                                        v-list-tile-title {{ item.title }}

                    v-flex(xs10 lg6)
                        section
                            v-flex(xs12 md6)
                                p.page-title 修改履歷
                                    v-btn.right(color='primary' @click='$router.push({name: "ResumeShow"})') 返回
                            .recruit-edit-field
                            div(v-for='(item, key) in dataList', :key='item.title')
                            div(v-if='tabIndex === 0')
                                p.recruit-edit-title 基本資料
                                    v-btn.ml-4(color='teal' @click='save("basic")' :loading="loading" :disabled="loading" dark) 保存
                                .recruit-edit-field
                                    v-flex(xs12 md6)
                                        v-text-field(type='text' label='姓氏' v-model.trim='resume.lastName')
                                        v-text-field(type='text' label='名字' v-model.trim='resume.firstName')
                                        v-radio-group(v-model="resume.gender" row)
                                            v-radio(label="男" value=1)
                                            v-radio(label="女" value=2)
                                            v-radio(label="第三性" value=3)
                                        v-menu(lazy :close-on-content-click="false" v-model="menu" transition="scale-transition" offset-y full-width :nudge-right="40" max-width="290px" min-width="290px")
                                            v-text-field(slot="activator" label="生日" v-model="resume.birth" prepend-icon="event" readonly)
                                                v-date-picker(v-model="resume.birth" scrollable actions landscape)
                                                    template(slot-scope="{ save, cancel }")
                                                        v-card-actions
                                                            v-spacer
                                                            v-btn(flat color="primary" @click="cancel") Cancel
                                                            v-btn(flat color="primary" @click="save") OK

                                        v-text-field(type='text' label='國籍' v-model.trim='resume.nation')
                                        v-text-field(type='text' label='信箱' v-model.trim='resume.email')
                                        v-text-field(type='text' label='手機' v-model.trim='resume.phone')

                            div(v-if='tabIndex === 1')
                                p.recruit-edit-title 學歷經驗
                                    v-btn.white--text(flat color='primary' icon @click='edit.background = true' v-if='!edit.background')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("background")' :loading="loading" :disabled="loading" v-if='edit.background') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.background' v-html='resume.background')
                                quill-editor(:content="resume.background" :options="editorOption" @change="onEditorChange($event, 'background')" v-if='edit.background')

                            div(v-if='tabIndex === 2')
                                p.recruit-edit-title 求職條件
                                    v-btn.right(color='primary' @click='save("condition")' :loading="loading" :disabled="loading") 保存
                                .recruit-edit-field
                                    v-flex(xs12 md6)
                                        v-text-field(type='text' label='希望職位' v-model.trim='resume.expectedJobName')
                                        p 薪資條件
                                        v-layout(wrap)
                                            v-flex(xs5)
                                                v-text-field(type='number' label='最低薪資' v-model.trim='resume.salaryFrom')
                                            v-flex(xs0) 
                                                | ~
                                            v-flex(xs5)
                                                v-text-field(type='number' label='最高薪資' v-model.trim='resume.salaryTo')

                                p.recruit-edit-title 語言能力
                                    v-btn.white--text(flat color='primary' icon @click='edit.language = true' v-if='!edit.language')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("language")' :loading="loading" :disabled="loading" v-if='edit.language') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.language' v-html='resume.language')
                                quill-editor(:content="resume.language" :options="editorOption" @change="onEditorChange($event, 'language')" v-if='edit.language')

                            div(v-if='tabIndex === 3')
                                p.recruit-edit-title 技能
                                    v-btn.white--text(flat color='primary' icon @click='edit.skill = true' v-if='!edit.skill')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("skill")' :loading="loading" :disabled="loading" v-if='edit.skill') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.skill' v-html='resume.skill')
                                quill-editor(:content="resume.skill" :options="editorOption" @change="onEditorChange($event, 'skill')" v-if='edit.skill')

                                p.recruit-edit-title 證照
                                    v-btn.white--text(flat color='primary' icon @click='edit.certificate = true' v-if='!edit.certificate')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("certificate")' :loading="loading" :disabled="loading" v-if='edit.certificate') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.certificate' v-html='resume.certificate')
                                quill-editor(:content="resume.certificate" :options="editorOption" @change="onEditorChange($event, 'certificate')" v-if='edit.certificate')

                            div(v-if='tabIndex === 4')
                                p.recruit-edit-title 自傳
                                    v-btn.white--text(flat color='primary' icon @click='edit.bio = true' v-if='!edit.bio')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("bio")' :loading="loading" :disabled="loading" v-if='edit.bio') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.bio' v-html='resume.bio')
                                quill-editor(:content="resume.bio" :options="editorOption" @change="onEditorChange($event, 'bio')" v-if='edit.bio')
                    v-flex(xs0)
        p-footer

</template>

<script>
import axios from 'axios'
import { quillEditor } from 'vue-quill-editor'

export default {
    components: {
        quillEditor
    },
    data: () => ({
        items: [
            {title: '基本資料', icon: 'dashboard'},
            {title: '學歷經驗', icon: 'dashboard'},
            {title: '求職條件 / 語言能力', icon: 'dashboard'},
            {title: '技能與證照 ', icon: 'dashboard'},
            {title: '自傳', icon: 'dashboard'}
        ],
        menu: false,
        tabIndex: 0,
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
            language: '',
            skill: '',
            certificate: '',
            bio: ''
        },
        dataList: {
            basic: ['firstName', 'lastName', 'gender', 'birth', 'nation', 'email', 'phone'],
            condition: ['expectedJobName', 'salaryFrom', 'salaryTo']

        },
        edit: {
            basic: false,
            background: false,
            language: false,
            condition: false,
            skill: false,
            certificate: false,
            bio: false
        },
        editorOption: {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{'size': ['small', false, 'large']}],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, false] }],
                    ['link', 'image']
                ]
            },
            placeholder: 'Type here...'
        },
        loading: false
    }),
    activated() {
        this.checkPermission()
        this.getResumeInfo()
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
            if (this.$root.user.userType !== 1 || !this.$root.user.emailState) {
                this.$router.push({name: 'Main'})
            }
        },
        onEditorChange(event, fieldName) {
            this.resume[fieldName] = event.html
        },
        toggleTab(index) {
            this.tabIndex = index
        },
        getResumeInfo() {
            axios.get(`/api/resume`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                        this.resume.gender = '' + this.resume.gender
                    }
                })
                .catch(e => this.errHandler(e))
        },
        save(fieldName) {
            this.loading = true
            let tmp = {}
            if (this.dataList[fieldName]) {
                this.dataList[fieldName].forEach(element => {
                    tmp[element] = this.resume[element]
                })
                if (fieldName === 'basic') {
                    tmp.gender = parseInt(tmp.gender)
                }
            } else {
                tmp.data = this.resume[fieldName]
            }
            axios.post(`/resume/${fieldName}`, tmp)
                .then(response => {
                    this.loading = false
                    this.edit[fieldName] = false
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        },
        saveBasic() {
            this.loading = true
            axios.post(`/resume/basic`, {
                firstName: this.resume.firstName,
                lastName: this.resume.lastName,
                gender: this.resume.gender,
                birth: this.resume.birth,
                nation: this.resume.nation,
                email: this.resume.email,
                phone: this.resume.phone
            })
                .then(response => {
                    this.loading = false
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        },
        saveRequest() {
            this.loading = true
            axios.post(`/resume/condition`, {
                expectedJobName: this.resume.expectedJobName,
                salaryFrom: this.resume.salaryFrom,
                salaryTo: this.resume.salaryTo
            })
                .then(response => {
                    this.loading = false
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    if (response.data.is_complete != null) {
                        this.resume.is_complete = response.data.is_complete
                        if (response.data.is_complete) msg = '所有資訊已填寫完整! 徵才訊息將很快公開'
                    }
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
