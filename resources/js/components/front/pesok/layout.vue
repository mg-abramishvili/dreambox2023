<template>
    <div class="pesok-layout">
        <router-view v-slot="{ Component }">
            <keep-alive>
                <component :is="Component" :key="$route.path" />
            </keep-alive>
        </router-view>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                //
            }
        },
        computed: {
            theme() {
                return this.$root.theme
            }
        },
        created() {
            //
        },
    }
</script>

<style>
@import "../../../../css/pesok/vertical.css"
</style>