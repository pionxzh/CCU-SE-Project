<template lang="pug">
    div    
        v-content
            v-layout(row='' justify-space-between='')
                    v-flex(xs0='')
                    v-flex(xs10='' lg6='')
                        section
                            p.page-title 徵才訊息詳情 完成度{{ recruit.is_complete ? 'O' : 'X' }}
                                v-btn.right(color='primary' @click='$router.push({name: "Recruit"})' :loading="loading" :disabled="loading") 返回列表
                            .recruit-edit-field

                            p.recruit-edit-title 徵才資訊
                                v-btn.right(color='primary' @click='saveField' :loading="loading" :disabled="loading") 保存
                            .recruit-edit-field
                                v-flex(xs12='' md6='')
                                    v-text-field(type='text' label='標題' v-model.trim='recruit.title')
                                    v-text-field(type='text' label='職位名稱' v-model.trim='recruit.jobname')
                                    v-text-field(type='number' label='職位類別' v-model.trim='recruit.jobtype')
                                    v-text-field(type='text' label='語言條件' v-model.trim='recruit.lang')
                                    v-layout(wrap='')
                                        v-flex(xs5='')
                                            v-text-field(type='number' label='最低薪資' v-model.trim='recruit.dpay')
                                        v-flex(xs0='') 
                                            | ~
                                        v-flex(xs5='')
                                            v-text-field(type='number' label='最高薪資' v-model.trim='recruit.upay')

                            p.recruit-edit-title 工作內容
                                p.recruit-edit-content.ql-editor(v-if='!edit.jobinfo' v-html='recruit.jobinfo')

                            p.recruit-edit-title 條件要求
                                p.recruit-edit-content.ql-editor(v-if='!edit.jobrequire' v-html='recruit.jobrequire')
                            

                            p.recruit-edit-title 公司福利
                                p.recruit-edit-content.ql-editor(v-if='!edit.benefits' v-html='recruit.benefits')

                            p.recruit-edit-title 聯絡方式
                                p.recruit-edit-content.ql-editor(v-if='!edit.contact' v-html='recruit.contact')
                    v-flex(xs0='')
        p-footer

</template>

<script>
import axios from 'axios'

export default {
    components: {
        quillEditor
    },
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
                title: '提示',
                text: msg,
                buttons: [{title: '關閉'}]
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
