<template>
    <div>
        <router-view></router-view>
        <posts-component v-if="this.$store.getters.loggedIn"></posts-component>
    </div>
</template>

<script>
    export default {
        name: "App"
    }
</script>

<style scoped>

</style>
