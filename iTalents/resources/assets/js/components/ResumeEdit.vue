<template lang="pug">
    div    
        v-content
            v-layout(row='' justify-space-between='')
                    v-flex(xs4='')
                        v-navigation-drawer(permanent='', light='')
                            v-toolbar(flat='')
                                v-list
                                    v-list-tile
                                        v-list-tile-title.title Application
                            v-divider
                            v-list.pt-0(dense='')
                                v-list-tile(v-for='(item, key) in items', :key='item.title', @click='toggleTab(key)')
                                    v-list-tile-action
                                        v-icon {{ item.icon }}
                                    v-list-tile-content
                                        v-list-tile-title {{ item.title }}

                    v-flex(xs10='' lg6='')
                        section
                            p.page-title 修改履歷
                                v-btn.right(color='primary' @click='$router.push({name: "Recruit"})' :loading="loading" :disabled="loading") 返回列表
                            .recruit-edit-field

                            div(v-if='tabIndex === 0')
                                p.recruit-edit-title 基本資料
                                    v-btn.right(color='primary' @click='saveBasic' :loading="loading" :disabled="loading") 保存
                                .recruit-edit-field
                                    v-flex(xs12='' md6='')
                                        v-text-field(type='text' label='名字' v-model.trim='resume.firstName')
                                        v-text-field(type='text' label='姓氏' v-model.trim='resume.lastName')
                                        v-text-field(type='text' label='身分證' v-model.trim='resume.pid')
                                        v-text-field(type='text' label='性別' v-model.trim='resume.gender')
                                        v-text-field(type='text' label='生日' v-model.trim='resume.birthday')
                                        v-text-field(type='text' label='國籍' v-model.trim='resume.nation')
                                        v-text-field(type='text' label='信箱' v-model.trim='resume.email')
                                        v-text-field(type='text' label='手機' v-model.trim='resume.cellphone')
                                        v-text-field(type='text' label='地址' v-model.trim='resume.address')

                            div(v-if='tabIndex === 1')
                                p.recruit-edit-title 學歷經驗
                                    v-btn.white--text(flat='' color='primary' icon='' @click='edit.background = true' v-if='!edit.background')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("background")' :loading="loading" :disabled="loading" v-if='edit.background') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.background' v-html='resume.background')
                                quill-editor(:content="resume.background" :options="editorOption" @change="onEditorChange($event, 'background')" v-if='edit.background')

                            div(v-if='tabIndex === 2')
                                p.recruit-edit-title 求職條件
                                    v-btn.right(color='primary' @click='saveRequest' :loading="loading" :disabled="loading") 保存
                                .recruit-edit-field
                                    v-flex(xs12='' md6='')
                                        v-text-field(type='text' label='希望職務名稱' v-model.trim='resume.expectedJobName')
                                        v-text-field(type='text' label='希望工作性質' v-model.trim='resume.expectedJobType')
                                        p 薪資條件
                                        v-layout(wrap='')
                                            v-flex(xs5='')
                                                v-text-field(type='number' label='最低薪資' v-model.trim='resume.salaryFrom')
                                            v-flex(xs0='') 
                                                | ~
                                            v-flex(xs5='')
                                                v-text-field(type='number' label='最高薪資' v-model.trim='resume.salaryTo')
                                        v-text-field(type='text' label='薪資條件' v-model.trim='resume.salaryFrom')
                                        v-text-field(type='text' label='標題' v-model.trim='resume.salaryTo')
                                        v-text-field(type='text' label='職位名稱' v-model.trim='resume.expectedJobDec')
                                        v-text-field(type='text' label='職位名稱' v-model.trim='resume.expectedJobCat')
                                        v-text-field(type='text' label='標題' v-model.trim='resume.expectedJobArea')
                                        v-text-field(type='text' label='職位名稱' v-model.trim='resume.expectedJobTime')
                                        v-text-field(type='text' label='標題' v-model.trim='resume.salaryType')

                            div(v-if='tabIndex === 3')
                                p.recruit-edit-title 語言能力
                                    v-btn.white--text(flat='' color='primary' icon='' @click='edit.language = true' v-if='!edit.language')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("language")' :loading="loading" :disabled="loading" v-if='edit.language') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.language' v-html='resume.language')
                                quill-editor(:content="resume.language" :options="editorOption" @change="onEditorChange($event, 'language')" v-if='edit.language')

                            div(v-if='tabIndex === 4')
                                p.recruit-edit-title 技能與證照
                                    v-btn.white--text(flat='' color='primary' icon='' @click='edit.skill = true' v-if='!edit.skill')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("skill")' :loading="loading" :disabled="loading" v-if='edit.skill') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.skill' v-html='resume.skill')
                                quill-editor(:content="resume.skill" :options="editorOption" @change="onEditorChange($event, 'skill')" v-if='edit.skill')

                            div(v-if='tabIndex === 5')
                                p.recruit-edit-title 自傳
                                    v-btn.white--text(flat='' color='primary' icon='' @click='edit.bio = true' v-if='!edit.bio')
                                        v-icon edit
                                    v-btn.right(color='primary' @click='save("bio")' :loading="loading" :disabled="loading" v-if='edit.bio') 保存
                                    p.recruit-edit-content.ql-editor(v-if='!edit.bio' v-html='resume.bio')
                                quill-editor(:content="resume.bio" :options="editorOption" @change="onEditorChange($event, 'bio')" v-if='edit.bio')
                    v-flex(xs0='')
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
            {title: '求職條件', icon: 'dashboard'},
            {title: '語言能力', icon: 'dashboard'},
            {title: '技能與證照', icon: 'dashboard'},
            {title: '自傳', icon: 'dashboard'}
        ],
        tabIndex: 0,
        resume: {
            firstName: '',
            lastName: '',
            pid: '',
            gender: '',
            birthday: '',
            nation: '',
            email: '',
            cellphone: null,
            address: '',

            expectedJobName: '',
            expectedJobType: '',
            salaryFrom: '',
            salaryTo: '',
            expectedJobDec: '',
            expectedJobCat: '',
            expectedJobArea: '',
            expectedJobTime: '',
            salaryType: '',

            background: '',
            language: '',
            skill: '',
            bio: ''
        },
        edit: {
            basic: false,
            background: false,
            language: false,
            condition: false,
            skill: false,
            bio: false
        },
        editorOption: {
            placeholder: 'Type here...'
        },
        loading: false
    }),
    activated() {
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
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        },
        saveBasic() {
            this.loading = true
            axios.post(`/recruit/${this.$route.params.id}/basic`, {
                firstName: this.resume.firstName,
                lastName: this.resume.lastName,
                pid: this.resume.pid,
                gender: this.resume.gender,
                birthday: this.resume.birthday,
                nation: this.resume.nation,
                email: this.resume.email,
                cellphone: this.resume.cellphone,
                address: this.resume.address
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
            axios.post(`/recruit/${this.$route.params.id}/condition`, {
                expectedJobName: this.resume.expectedJobName,
                expectedJobType: this.resume.expectedJobType,
                salaryFrom: this.resume.salaryFrom,
                salaryTo: this.resume.lasalaryTong,
                expectedJobDec: this.resume.expectedJobDec,
                expectedJobCat: this.resume.expectedJobCat,
                expectedJobArea: this.resume.expectedJobArea,
                expectedJobTime: this.resume.expectedJobTime,
                salaryType: this.resume.salaryType
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
