<template lang="pug">
    v-app#app(light)
        v-navigation-drawer.hidden-md-and-up(fixed v-model='drawer')
            v-list(dense)
                router-link.no-decoration(:to="{name: 'User'}")
                    v-list-tile
                        v-list-tile-action
                            v-icon home
                        v-list-tile-content
                            v-list-tile-title {{ $t('user.title') }}
                router-link.no-decoration(:to="{name: 'ResumeShow'}" v-if='$root.user.userType === 1')
                    v-list-tile
                        v-list-tile-action
                            v-icon recent_actors
                        v-list-tile-content
                            v-list-tile-title {{ $t('resume.title') }}
                router-link.no-decoration(:to="{name: 'ResumeMatch'}" v-if='$root.user.userType === 1')
                    v-list-tile
                        v-list-tile-action
                            v-icon touch_app
                        v-list-tile-content
                            v-list-tile-title {{ $t('resume.match') }}
                router-link.no-decoration(:to="{name: 'Recruit'}" v-if='$root.user.userType === 2')
                    v-list-tile
                        v-list-tile-action
                            v-icon terrain
                        v-list-tile-content
                            v-list-tile-title {{ $t('recruit.title') }}
                v-divider
                v-list-tile(@click='logout')
                    v-list-tile-action
                        v-icon power_settings_new
                    v-list-tile-content
                        v-list-tile-title {{ $t('common.logout') }}

        v-toolbar.primary(color='blue darken-3' dark app clipped-left fixed)
            v-toolbar-side-icon.hidden-md-and-up(@click.stop="drawer = !drawer")
            router-link.no-decoration(:to="{name: 'Main'}")
                v-toolbar-title.ml-5.f27 iTalent
            v-spacer
            v-text-field.hidden-sm-and-down(light solo prepend-icon='search' placeholder='Search' style='max-width: 500px; min-width: 300px')
            v-text-field.hidden-md-and-up.cool-search(light solo prepend-icon='search' placeholder='Search' style='max-width: 350px; min-width: 350px' :class='{ blind: !searchToggle}' @blur='searchToggle = false')
            v-spacer
            v-btn.hidden-md-and-up(flat icon datk @click='searchToggle = !searchToggle')
                v-icon search
            .d-flex(align-center style='margin-left: auto' v-if='!$root.user.username.length')
                router-link.no-decoration(:to="{name: 'Login'}")
                    v-btn {{ $t('common.login') }}/{{ $t('common.register') }}
            v-menu(offset-y)
                v-btn(flat icon dark slot='activator')
                    v-icon public
                v-list
                    v-list-tile.list__tile--link(@click='setLanguage("en")')
                        v-list-tile-title EN
                    v-list-tile.list__tile--link(@click='setLanguage("zh")')
                        v-list-tile-title 中文

            v-menu.hidden-sm-and-down(v-if='$root.user.username.length' offset-y)
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
        transition(name="page", mode="out-in")
            keep-alive
                router-view
        p-footer(v-if="['Login', 'Register'].indexOf($route.name) === -1")
        v-dialog
</template>

<style lang="stylus">
    @import '../../../node_modules/vuetify/src/stylus/main.styl'
    @import '../stylus/main'
</style>

<script>
import axios from 'axios'

export default {
    data: () => ({
        drawer: false,
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
        }
    }
}
</script>
