<template lang="pug">
    v-content
            v-container(fluid fill-height)
                v-layout(row justify-space-between)
                        v-flex(xs0)
                        v-flex(xs10 lg6)
                            section
                                p.page-title 個人資料修改
                                
                                v-flex(xs12 md6)
                                    p.recruit-edit-title 基本資訊
                                        v-btn.right(dark color='green' @click='save' :loading="loading" :disabled="loading") 保存
                                .recruit-edit-field(v-if='$root.user.userType === 1')
                                    v-flex(xs12 md6)
                                        v-text-field(type='text' label='姓氏' v-model.trim='user.lastname')
                                        v-text-field(type='text' label='名稱' v-model.trim='user.firstname')
                                        v-text-field(type='text' label='學號' v-model.trim='user.studentID')
                                        v-radio-group(v-model="user.gender" row)
                                            v-radio(label="男" value=1)
                                            v-radio(label="女" value=2)
                                            v-radio(label="第三性" value=3)
                                        v-text-field(type='text' label='手機' v-model.trim='user.phone')

                                .recruit-edit-field(v-if='$root.user.userType === 2')
                                    v-flex(xs12 md6)
                                        v-text-field(type='text' label='公司名稱' v-model.trim='user.name')
                                        v-text-field(type='text' label='公司統編' v-model.trim='user.EIN')
                                        v-text-field(type='text' label='人資部Email' v-model.trim='user.email')
                                        v-text-field(type='text' label='人資部電話' v-model.trim='user.phone')
                                    
                        v-flex(xs0)

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        user: {},
        loading: false
    }),
    activated() {
        this.getUserInfo()
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
        getUserInfo() {
            axios.get(`/api/user/personal`)
                .then(response => {
                    console.log(response.data)
                    if (response.data.stat) {
                        this.user = response.data
                        if (this.$root.user.userType === 1) this.user.gender = '' + this.user.gender
                    }
                })
                .catch(e => this.errHandler(e))
        },
        save() {
            this.loading = true
            let tmp = {}
            if (this.$root.user.userType === 1) {
                tmp = {
                    firstname: this.user.firstname,
                    lastname: this.user.lastname,
                    studentID: this.user.studentID,
                    gender: parseInt(this.user.gender),
                    phone: this.user.phone
                }
            } else {
                tmp = {
                    name: this.user.name,
                    EIN: this.user.EIN,
                    email: this.user.email,
                    phone: this.user.phone
                }
            }
            axios.post(`/api/user/personal`, tmp)
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
