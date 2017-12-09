<template lang="pug">
    div#app(style="display: initial;")
        transition(name="page",mode="out-in")
            keep-alive
                router-view
        v-dialog
</template>

<style lang="stylus">
    @import '../../../node_modules/vuetify/src/stylus/main.styl'
    @import '../stylus/main'
</style>
