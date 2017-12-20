<template lang="pug">
    v-content
        v-container(fluid fill-height)
            v-layout(row justify-space-between)
                v-flex(xs0)
                v-flex(xs10 lg6)
                    section
                        p.page-title {{ $t('user.editPersonal')}}
                        
                        v-flex(xs12 md6)
                            p.recruit-edit-title {{ $t('user.personalInformation')}}
                                v-btn.right(dark color='green' @click='save' :loading="loading" :disabled="loading") {{ $t('common.save')}}
                        .recruit-edit-field(v-if='$root.user.userType === 1')
                            v-flex(xs12 md6)
                                v-text-field(type='text' :label='$t("resume.lastName")' v-model.trim='user.lastname')
                                v-text-field(type='text' :label='$t("resume.firstName")' v-model.trim='user.firstname')
                                v-text-field(type='text' :label='$t("user.studentID")' v-model.trim='user.studentID')
                                v-radio-group(v-model="user.gender" row)
                                    v-radio(:label='$t("gender.male")' value=1)
                                    v-radio(:label='$t("gender.female")' value=2)
                                    v-radio(:label='$t("gender.other")' value=3)
                                v-text-field(type='text' :label='$t("resume.phone")' v-model.trim='user.phone')

                        .recruit-edit-field(v-if='$root.user.userType === 2')
                            v-flex(xs12 md6)
                                v-text-field(type='text' :label='$t("user.companyName")' v-model.trim='user.name')
                                v-text-field(type='text' :label='$t("user.companyID")' v-model.trim='user.EIN')
                                v-text-field(type='text' :label='$t("user.hrEmail")' v-model.trim='user.email')
                                v-text-field(type='text' :label='$t("user.hrPhone")' v-model.trim='user.phone')
                                p.red--text {{ $t('user.hrPhoneHint')}}
                            
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
                    let msg = response.data.stat ? this.$t('alert.saveSuccess') : this.$t('alert.saveFail')
                    this.showDialog(msg)
                })
                .catch(e => this.errHandler(e))
        }
    }
}
</script>
