<template lang="pug">
    div    
        v-content
            v-layout(row justify-space-between)
                    v-flex(xs0)
                    v-flex(xs10 lg6)
                        section
                            p.page-title {{ recruit.title }}
                                v-btn.right(color='primary' @click='$router.push({name: "Recruit"})' :loading="loading" :disabled="loading") 返回列表
                            .recruit-edit-field

                            p.recruit-show-title 徵才資訊
                            .recruit-edit-field
                                v-flex(xs12 md6)
                                    p.recruit-show-info 職位名稱: {{ recruit.jobname || '尚未填寫'}}
                                    p.recruit-show-info 工作類型: {{ recruit.jobtype === 0 ? '兼職' : '全職' }}
                                    p.recruit-show-info 語言需求: {{ recruit.lang || '尚未填寫'}}
                                    p.recruit-show-info 薪資條件: {{ recruit.dpay }} ~ {{recruit.upay}}
                            p.recruit-show-title 工作內容
                                p.recruit-edit-content.ql-editor(v-html='recruit.jobinfo')

                            p.recruit-show-title 條件要求
                                p.recruit-edit-content.ql-editor(v-html='recruit.jobrequire || "尚未填寫"')
                            

                            p.recruit-show-title 公司福利
                                p.recruit-edit-content.ql-editor(v-html='recruit.benefits || "尚未填寫"')

                            p.recruit-show-title 聯絡方式
                                p.recruit-edit-content.ql-editor(v-html='recruit.contact || "尚未填寫"')
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
