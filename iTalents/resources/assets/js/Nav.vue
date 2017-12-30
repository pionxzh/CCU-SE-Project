<template lang='pug'>
    v-toolbar.primary(color='blue darken-3' dark app clipped-left fixed)
        //v-toolbar-side-icon.hiden-md-and-up(@click.stop="toggleDrawer")
        router-link.no-decoration(:to="{name: 'Main'}" v-if='!searchToggle')
            v-toolbar-title.f27 iTalent
        v-spacer
        v-text-field.hidden-sm-and-down(light solo prepend-icon='search' placeholder='Search' style='max-width: 500px; min-width: 300px')
        v-text-field.hidden-md-and-up(light solo prepend-icon='search' placeholder='Search' style='max-width: 300px; min-width: 300px' v-if='searchToggle' @blur='searchToggle = false')
        v-spacer
        v-btn.hidden-md-and-up(flat icon dark @click='searchToggle = !searchToggle' v-if='!searchToggle')
            v-icon search
        .d-flex(align-center style='margin-left: auto' v-if='!$root.user.username.length')
            router-link.no-decoration(:to="{name: 'Login'}")
                v-btn {{ $t('common.login') }}/{{ $t('common.register') }}
        v-menu(offset-y)
            v-btn.hidden-sm-and-down(flat icon dark slot='activator')
                v-icon public
            v-list
                v-list-tile.list__tile--link(@click='setLanguage("en")')
                    v-list-tile-title EN
                v-list-tile.list__tile--link(@click='setLanguage("zh")')
                    v-list-tile-title 中文

        v-menu(v-if='$root.user.username.length && !searchToggle' offset-y)
            v-btn.mr-5(slot='activator') {{ username }}
            v-list
                router-link.no-decoration(:to="{name: 'User'}")
                    v-list-tile.list__tile--link
                        v-list-tile-title {{ $t('user.title') }}
                router-link.no-decoration(:to="{name: 'ResumeShow'}" v-if='$root.user.userType === 1')
                    v-list-tile.list__tile--link
                        v-list-tile-title {{ $t('resume.title') }}
                router-link.no-decoration(:to="{name: 'ResumeMatch'}" v-if='$root.user.userType === 1')
                    v-list-tile.list__tile--link
                        v-list-tile-title {{ $t('resume.match') }}

                router-link.no-decoration(:to="{name: 'Recruit'}" v-if='$root.user.userType === 2')
                    v-list-tile.list__tile--link
                        v-list-tile-title {{ $t('recruit.title') }}

                v-divider
                v-list-tile.list__tile--link(@click='logout')
                    v-list-tile-title {{ $t('common.logout') }}

</template>

<script>
import axios from 'axios'

export default {
    data: () => ({
        searchToggle: false
    }),
    computed: {
        username() {
            return this.$root.user.username.length ? this.$root.user.username.split('@', 1)[0] : ''
        }
    },
    methods: {
        errHandler (e) {
            console.log(e.message)
        },
        logout: function() {
            axios.get('/logout')
                .then(response => {
                    if (response.data.stat) {
                        window.location.href = '/main'
                    }
                })
                .catch(e => this.errHandler(e))
        },
        setLanguage(language) {
            this.$i18n.locale = language
        },
        toggleDrawer() {
        }
    }
}
</script>
