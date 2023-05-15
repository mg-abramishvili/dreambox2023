<template>
    <div class="muzei-layout">
        <Sidebar v-if="$config.orientation == 'horizontal'" />

        <router-view v-slot="{ Component }">
            <keep-alive>
                <component :is="Component" :key="$route.path" />
            </keep-alive>
        </router-view>
    </div>
</template>

<script>
import Sidebar from './sidebar.vue'

export default {
    data() {
        return {
            time: '',
            date: '',
        }
    },
    mounted() {
        setInterval(this.currentTime, 1000)
        setInterval(this.currentDate, 1000)
    },
    methods: {
        currentTime() {
            this.time = this.$dayjs().locale('ru').format('H:mm')
        },
        currentDate() {
            this.date = this.$dayjs().locale('ru').format('D MMMM, dddd')
        },
    },
    components: {
        Sidebar
    }
}
</script>

<style>
    @import "../../../../css/muzei/vertical.css"
</style>