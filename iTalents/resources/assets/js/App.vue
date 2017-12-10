<template lang="pug">
    v-app#app(light='' :class='{fullscreen: isFullScreen || full}')
        navbar
        transition(name="page",mode="out-in")
            keep-alive
                router-view
        v-dialog
</template>

<style lang="stylus">
    @import '../../../node_modules/vuetify/src/stylus/main.styl'
    @import '../stylus/main'
</style>

<script>
export default {
    data: () => ({
        full: false
    }),
    watch: {
        '$route': function(value) {
            if (value.name === 'Login' || value.name === 'Register') this.full = true
            else this.full = false
        }
    },
    computed: {
        isFullScreen() {
            return window.location.pathname === '/login' || window.location.pathname === '/register'
        }
    }
}
</script>
