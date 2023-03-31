<template>
    <template v-if="config.theme == 'pesok'">
        <PesokVertical
            v-if="config.orientation == 'vertical'"
            ref="pesok"
            :page="page"
            :kiosk="$route.params.kiosk" />
    </template>
</template>

<script>
import PesokVertical from './pesok/pages/vertical.vue'

export default {
    data() {
        return {
            page: {},
        }
    },
    computed: {
        config() {
            return this.$root.config
        },
    },
    created() {
        this.loadPage()
    },
    methods: {
        loadPage() {
            axios.get(`/api/page/${this.$route.params.id}`)
            .then(response => {
                this.page = response.data
            })
        },
        goTo(fromPage, toPage) {
            if(fromPage) {
                fromPage.blocks.forEach(block => {
                    if(block.type == 'video') {
                        let video = document.getElementById('video_' + block.id)
    
                        if(video) {
                            video.load()
                        }
                    }
    
                    if(block.type == 'routes') {
                        this.$refs.pesok.$refs.routes[0].closeSearchPanel()
                        this.$refs.pesok.$refs.routes[0].resetRoutes()
                        this.$refs.pesok.$refs.routes[0].zoomReset()
                        this.$refs.pesok.$refs.routes[0].slideToZero()
                    }

                    if(block.type == 'events') {
                        this.$refs.pesok.$refs.events[0].slideToZero()
                        this.$refs.pesok.$refs.events[0].descriptionModalContent = ''
                    }

                    if(block.type == 'image_slider') {
                        this.$refs.pesok.slideToZero()
                    }
                })
            }

            if(toPage) {
                if(toPage == 'home') {
                    this.$router.push({ name: 'Home', params: { kiosk: this.$route.params.kiosk } })
                } else {
                    toPage.blocks.forEach(block => {
                        if(block.type == 'video') {
                            setTimeout(() => {
                                let video = document.getElementById('video_' + block.id)

                                if(video) {
                                    video.play()
                                }
                            }, 100)
                        }
                    })

                    this.$router.push({ name: 'Page', params: { id: toPage.id, kiosk: this.$route.params.kiosk } })
                }
            }
        },
    },
    components: {
        PesokVertical
    }
}
</script>