<template lang="pug">
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
                                    v-btn.ml-4(color='green' @click='save("basic")' :loading="loading" :disabled="loading" dark) 保存
                                .recruit-edit-field
                                    v-flex(xs12 md6)
                                        v-text-field(type='text' label='姓氏' v-model.trim='resume.lastName')
                                        v-text-field(type='text' label='名字' v-model.trim='resume.firstName')
                                        v-radio-group(v-model="resume.gender" row)
                                            v-radio(label="男" value=1)
                                            v-radio(label="女" value=2)
                                            v-radio(label="其他" value=3)
                                        v-menu(lazy :close-on-content-click="false" v-model="menu" transition="scale-transition" offset-y full-width :nudge-right="40" max-width="290px" min-width="290px")
                                            v-text-field(slot="activator" label="生日" v-model="resume.birth" prepend-icon="event" readonly)
                                            v-date-picker(v-model="resume.birth" scrollable actions)
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
                                    v-btn.ml-4(fab dark small color='red' @click='edit.background = false' v-if='edit.background')
                                        v-icon clear
                                    v-btn.ml-4(fab dark small color='green' @click='save("background")' :loading="loading" :disabled="loading" v-if='edit.background')
                                        v-icon done
                                    p.recruit-edit-content.ql-editor(v-if='!edit.background' v-html='resume.background')
                                quill-editor(:content="resume.background" :options="editorOption" @change="onEditorChange($event, 'background')" v-if='edit.background')

                            div(v-if='tabIndex === 2')
                                p.recruit-edit-title 求職條件
                                    v-btn.ml-4(color='primary' @click='save("condition")' :loading="loading" :disabled="loading") 保存
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
                                    v-btn.white--text(flat icon color='primary' @click='edit.language = true' v-if='!edit.language')
                                        v-icon edit
                                    v-btn.ml-4(fab dark small color='red' @click='edit.language = false' v-if='edit.language')
                                        v-icon clear
                                    v-btn.ml-4(fab dark small color='green' @click='saveLanguage' :loading="loading" :disabled="loading" v-if='edit.language')
                                        v-icon done
                                    
                                    div.language-select(v-if='!edit.language')
                                        v-chip(v-for="item in lang.list" :key='item.id' v-if='item.stat')
                                            v-avatar(:class='langColor[item.ability]')
                                                span.white--text.headline {{langLevel[item.ability]}}
                                            | {{langMap[item.language]}}
                                    div.language-select(v-if='edit.language')
                                        v-chip(close v-for="item in lang.list" :key='item.id' v-model='item.stat')
                                            v-avatar(:class='langColor[item.ability]')
                                                span.white--text.headline {{langLevel[item.ability]}}
                                            | {{langMap[item.language]}}
                                        v-layout(wrap)
                                            v-flex(xs4)
                                                v-select(v-bind:items="languageOpt" v-model="lang.value" label="語言"  single-line bottom)
                                            v-flex(xs1)
                                            v-flex(xs4)
                                                v-select(v-bind:items="abilityOpt" v-model="lang.ability" label="程度" single-line bottom)
                                            v-flex(xs1)
                                            v-flex(xs2)
                                                v-btn(color='teal' @click='addLanguage' dark) 新增


                            div(v-if='tabIndex === 3')
                                p.recruit-edit-title 技能
                                    v-btn.white--text(flat color='primary' icon @click='edit.skill = true' v-if='!edit.skill')
                                        v-icon edit
                                    v-btn.ml-4(fab dark small color='red' @click='edit.skill = false' v-if='edit.skill')
                                        v-icon clear
                                    v-btn.ml-4(fab dark small color='green' @click='save("skill")' :loading="loading" :disabled="loading" v-if='edit.skill')
                                        v-icon done
                                    p.recruit-edit-content.ql-editor(v-if='!edit.skill' v-html='resume.skill')
                                quill-editor(:content="resume.skill" :options="editorOption" @change="onEditorChange($event, 'skill')" v-if='edit.skill')

                                p.recruit-edit-title 證照
                                    v-btn.white--text(flat color='primary' icon @click='edit.certificate = true' v-if='!edit.certificate')
                                        v-icon edit
                                    v-btn.ml-4(fab dark small color='red' @click='edit.certificate = false' v-if='edit.certificate')
                                        v-icon clear
                                    v-btn.ml-4(fab dark small color='green' @click='save("certificate")' :loading="loading" :disabled="loading" v-if='edit.certificate')
                                        v-icon done
                                    p.recruit-edit-content.ql-editor(v-if='!edit.certificate' v-html='resume.certificate')
                                quill-editor(:content="resume.certificate" :options="editorOption" @change="onEditorChange($event, 'certificate')" v-if='edit.certificate')

                            div(v-if='tabIndex === 4')
                                p.recruit-edit-title 自傳
                                    v-btn.white--text(flat dark color='primary' icon @click='edit.bio = true' v-if='!edit.bio')
                                        v-icon edit
                                    v-btn.ml-4(fab dark small color='red' @click='edit.bio = false' v-if='edit.bio')
                                        v-icon clear
                                    v-btn.ml-4(fab dark small color='green' @click='save("bio")' :loading="loading" :disabled="loading" v-if='edit.bio')
                                        v-icon done
                                    p.recruit-edit-content.ql-editor(v-if='!edit.bio' v-html='resume.bio')
                                quill-editor(:content="resume.bio" :options="editorOption" @change="onEditorChange($event, 'bio')" v-if='edit.bio')
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
            language: null,
            skill: '',
            certificate: '',
            bio: ''
        },
        dataList: {
            basic: ['firstName', 'lastName', 'gender', 'birth', 'nation', 'email', 'phone'],
            condition: ['expectedJobName', 'salaryFrom', 'salaryTo']
        },
        languageOpt: [
            {text: '英語', value: 'en'},
            {text: '中文', value: 'ch'},
            {text: '日文', value: 'jp'},
            {text: '法語', value: 'fr'}
        ],
        abilityOpt: [
            {text: '不會', value: 0},
            {text: '略懂', value: 1},
            {text: '中等', value: 2},
            {text: '熟練', value: 3},
            {text: '精通', value: 4}
        ],
        langColor: ['light-blue', 'light-green', 'yellow accent-4', 'deep-orange', 'pink'],
        langMap: {en: '英語', ch: '中文', jp: '日文', fr: '法語'},
        langLevel: ['D', 'C', 'B', 'A', 'S'],
        lang: {
            value: null,
            ability: null,
            list: []
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
                return true
            }
            return false
        },
        onEditorChange(event, fieldName) {
            this.resume[fieldName] = event.html
        },
        toggleTab(index) {
            this.tabIndex = index
        },
        getResumeInfo() {
            if (this.checkPermission()) return
            axios.get(`/api/resume`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                        this.resume.gender = this.resume.gender.toString()
                        for (const key in response.data.language) {
                            this.lang.list.push({
                                id: Math.floor(Math.random() * 10000),
                                language: key,
                                ability: response.data.language[key],
                                stat: true
                            })
                        }
                    }
                })
                .catch(e => this.errHandler(e))
        },
        addLanguage() {
            this.lang.list.push({
                id: Math.floor(Math.random() * 10000),
                language: this.lang.value,
                ability: this.lang.ability,
                stat: true
            })
            this.lang.value = null
            this.lang.ability = null
        },
        saveLanguage() {
            let langData = {}
            this.lang.list.forEach(element => {
                console.log(element)
                if (element.stat) langData[element.language] = element.ability
            })
            axios.post(`/resume/language`, {data: langData})
                .then(response => {
                    this.loading = false
                    this.edit.language = false
                    let msg = response.data.stat ? '保存成功!' : '保存失敗，請再試一次'
                    this.showDialog(msg)
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
        }
    }
}
</script>
