<template lang="pug">
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                v-flex(xs12 md4)
                    v-navigation-drawer.side-resume-menu.shadow(permanent light)
                        v-toolbar(flat)
                            v-list
                                v-list-tile
                                    v-list-tile-title.title {{ $t('resume.type') }}
                        v-divider
                        v-list.pt-0(dense)
                            v-list-tile(v-for='(item, key) in items', :key='item' @click='toggleTab(key)')
                                v-list-tile-action
                                    v-icon dashboard
                                v-list-tile-content
                                    v-list-tile-title {{ item }}

                v-flex(xs12 md6)
                    section
                        v-flex(md12 lg6)
                            p.page-title {{ $t('resume.editTitle') }}
                                v-btn.right(color='primary' @click='$router.push({name: "ResumeShow"})') {{ $t('common.back') }}
                        .recruit-edit-field
                        div(v-for='(item, key) in dataList', :key='item.title')
                        div(v-if='tabIndex === 0 && resume')
                            p.recruit-edit-title {{ $t('resume.basic') }}
                                v-btn.ml-4(color='green' @click='save("basic")' :loading="loading" :disabled="loading" dark) {{ $t('common.save') }}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    v-text-field(type='text' :label='$t("resume.lastName")' v-model.trim='resume.lastName')
                                    v-text-field(type='text' :label='$t("resume.firstName")' v-model.trim='resume.firstName')
                                    v-radio-group(v-model="resume.gender" row)
                                        v-radio(:label='$t("gender.male")' value=1)
                                        v-radio(:label='$t("gender.female")' value=2)
                                        v-radio(:label='$t("gender.other")' value=3)
                                    v-menu(lazy :close-on-content-click="false" v-model="menu" transition="scale-transition" offset-y full-width :nudge-right="40" max-width="290px" min-width="290px")
                                        v-text-field(slot="activator" :label='$t("resume.birth")' v-model="resume.birth" prepend-icon="event" readonly)
                                        v-date-picker(v-model="resume.birth" scrollable actions)
                                            template(slot-scope="{ save, cancel }")
                                                v-card-actions
                                                    v-spacer
                                                    v-btn(flat color="primary" @click="cancel") Cancel
                                                    v-btn(flat color="primary" @click="save") OK

                                    v-text-field(type='text' :label='$t("resume.nation")' v-model.trim='resume.nation')
                                    v-text-field(type='text' :label='$t("resume.email")' v-model.trim='resume.email')
                                    v-text-field(type='text' :label='$t("resume.phone")' v-model.trim='resume.phone')

                        div(v-if='tabIndex === 1')
                            p.recruit-edit-title {{ $t('resume.background') }}
                                v-btn.white--text(flat color='primary' icon @click='edit.background = true' v-if='!edit.background')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.background = false' v-if='edit.background')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("background")' :loading="loading" :disabled="loading" v-if='edit.background')
                                    v-icon done
                                p.recruit-edit-content.ql-editor(v-if='!edit.background' v-html='resume.background')
                            quill-editor(:content="resume.background" :options="editorOption" @change="onEditorChange($event, 'background')" v-if='edit.background')

                        div(v-if='tabIndex === 2')
                            p.recruit-edit-title {{ $t('resume.condition') }}
                                v-btn.ml-4(color='primary' @click='save("condition")' :loading="loading" :disabled="loading") {{ $t('common.save')}}
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    v-select(v-bind:items="jobList" v-model='resume.expectedJobName' :label='$t("resume.expectedJobName")' single-line bottom)
                                    p {{ $t('resume.salaryRequest') }}
                                    v-layout(wrap)
                                        v-flex(xs5)
                                            v-text-field(type='number' :label='$t("resume.salaryFrom")' v-model.trim='resume.salaryFrom')
                                        v-flex(xs0) 
                                            | ~
                                        v-flex(xs5)
                                            v-text-field(type='number' :label='$t("resume.salaryTo")' v-model.trim='resume.salaryTo')

                            p.recruit-edit-title {{ $t('resume.language') }}
                                v-btn.white--text(flat icon color='primary' @click='edit.language = true' v-if='!edit.language')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.language = false' v-if='edit.language')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='saveLanguage' :loading="loading" :disabled="loading" v-if='edit.language')
                                    v-icon done
                                
                                div.language-select(v-if='!edit.language')
                                    v-chip(v-for="item in lang.list" :key='item.id' v-if='item.ability')
                                        v-avatar(:class='langColor[item.ability]')
                                            span.white--text.headline {{langLevel[item.ability]}}
                                        | {{langMap[item.language]}}
                                div.language-select(v-if='edit.language')
                                    v-chip(close v-for="item in lang.list" :key='item.id' v-model='item.stat' v-if='item.ability')
                                        v-avatar(:class='langColor[item.ability]')
                                            span.white--text.headline {{langLevel[item.ability]}}
                                        | {{langMap[item.language]}}
                                    v-layout(wrap)
                                        v-flex(xs4)
                                            v-select(v-bind:items="languageOpt" v-model="lang.value" :label='$t("resume.lang")'  single-line bottom)
                                        v-flex(xs1)
                                        v-flex(xs4)
                                            v-select(v-bind:items="abilityOpt" v-model="lang.ability" :label='$t("resume.ability")' single-line bottom)
                                        v-flex(xs1)
                                        v-flex(xs2)
                                            v-btn(color='teal' @click='addLanguage' dark) {{ $t('common.add')}}


                        div(v-if='tabIndex === 3')
                            p.recruit-edit-title {{ $t('resume.skill') }}
                                v-btn.white--text(flat color='primary' icon @click='edit.skill = true' v-if='!edit.skill')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.skill = false' v-if='edit.skill')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("skill")' :loading="loading" :disabled="loading" v-if='edit.skill')
                                    v-icon done
                                p.recruit-edit-content.ql-editor(v-if='!edit.skill' v-html='resume.skill')
                            quill-editor(:content="resume.skill" :options="editorOption" @change="onEditorChange($event, 'skill')" v-if='edit.skill')

                            p.recruit-edit-title {{ $t('resume.certificate') }}
                                v-btn.white--text(flat color='primary' icon @click='edit.certificate = true' v-if='!edit.certificate')
                                    v-icon edit
                                v-btn.ml-4(fab dark small color='red' @click='edit.certificate = false' v-if='edit.certificate')
                                    v-icon clear
                                v-btn.ml-4(fab dark small color='green' @click='save("certificate")' :loading="loading" :disabled="loading" v-if='edit.certificate')
                                    v-icon done
                                p.recruit-edit-content.ql-editor(v-if='!edit.certificate' v-html='resume.certificate')
                            quill-editor(:content="resume.certificate" :options="editorOption" @change="onEditorChange($event, 'certificate')" v-if='edit.certificate')

                        div(v-if='tabIndex === 4')
                            p.recruit-edit-title {{ $t('resume.bio') }}
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
        items: [],
        menu: false,
        tabIndex: 0,
        resume: null,
        dataList: {
            basic: ['firstName', 'lastName', 'gender', 'birth', 'nation', 'email', 'phone'],
            condition: ['expectedJobName', 'salaryFrom', 'salaryTo']
        },
        jobList: [
            {text: '網頁工程師', value: '網頁工程師'},
            {text: '電機工程技術員', value: '電機工程技術員'},
            {text: '心理學研究人員', value: '心理學研究人員'},
            {text: '會計師', value: '會計師'},
            {text: '金融營業員', value: '金融營業員'}
        ],
        languageOpt: [
            {text: '英語', value: 'en'},
            {text: '中文', value: 'ch'},
            {text: '日文', value: 'jp'},
            {text: '法語', value: 'fr'}
        ],
        abilityOpt: [
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
            list: {}
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
    created() {
        this.items.push(this.$t('resume.basic'))
        this.items.push(this.$t('resume.background'))
        this.items.push(`${this.$t('resume.condition')} / ${this.$t('resume.language')}`)
        this.items.push(`${this.$t('resume.skill')} / ${this.$t('resume.certificate')}`)
        this.items.push(this.$t('resume.bio'))
    },
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
                title: this.$t('common.hint'),
                text: msg,
                buttons: [{ title: this.$t('common.close') }]
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
                    console.log('ResumeInfo', response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                        this.resume.gender = this.resume.gender.toString()
                        for (const key in response.data.language) {
                            this.lang.list[key] = {
                                id: Math.floor(Math.random() * 10000),
                                language: key,
                                ability: response.data.language[key],
                                stat: true
                            }
                        }
                    }
                })
                .catch(e => this.errHandler(e))
        },
        addLanguage() {
            let tmp = this.lang.list[this.lang.value]
            tmp.ability = this.lang.ability
            tmp.stat = true
            delete this.lang.list[this.lang.value]
            this.lang.list[this.lang.value] = tmp
            this.lang.value = null
            this.lang.ability = null
        },
        saveLanguage() {
            let langData = {}
            for (let key in this.lang.list) {
                langData[key] = this.lang.list[key].ability
            }
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
