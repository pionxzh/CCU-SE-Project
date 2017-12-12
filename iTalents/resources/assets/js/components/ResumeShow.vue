<template lang="pug">
    div    
        v-content
            v-layout(row justify-space-between)
                    v-flex(xs)
                    v-flex(xs10 lg6)
                        section
                            p.page-title 履歷
                                router-link.no-decoration(:to="{name: 'ResumeEdit'}")
                                    v-btn.ml-4(color='primary')
                                        v-icon edit
                                        | 編輯

                            p.recruit-show-title 基本資料
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info 姓名: {{ resume.lastName }} {{ resume.firstName }}
                                    p.recruit-show-info 性別: {{ resume.gender }}
                                    p.recruit-show-info 生日: {{ resume.birth }}
                                    p.recruit-show-info 國籍: {{ resume.nation }}
                                    p.recruit-show-info Email: {{ resume.email }}
                                    p.recruit-show-info 手機: {{ resume.phone }}
                            p.recruit-show-title 職業資訊
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info 希望職務名稱: {{ resume.expectedJobName }}
                                    p.recruit-show-info 薪資條件: {{ resume.salaryFrom }}&nbsp;~&nbsp;{{ resume.salaryTo }}

                            p.recruit-show-title 語言能力
                                p.recruit-edit-content.ql-editor(v-html='resume.language || "還沒有填寫喔~"')

                            p.recruit-show-title 學歷經驗
                                p.recruit-edit-content.ql-editor(v-html='resume.background')

                            p.recruit-show-title 具備技能
                                p.recruit-edit-content.ql-editor(v-html='resume.skill')

                            p.recruit-show-title 證照能力
                                p.recruit-edit-content.ql-editor(v-html='resume.certificate')

                            p.recruit-show-title 自傳
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
            language: '',
            skill: '',
            certificate: '',
            bio: ''
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
        getResumeInfo() {
            axios.get(`/api/resume`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.resume = response.data.data
                    }
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
