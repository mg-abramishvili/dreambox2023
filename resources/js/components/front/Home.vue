<template>
    <template v-if="config.theme == 'pesok'">
        <PesokVertical
            v-if="config.orientation == 'vertical'"
            ref="pesok"
            :pages="pages"
            :banners="banners" />
    </template>
</template>

<script>
import PesokVertical from './pesok/home/vertical.vue'

export default {
    data() {
        return {
            pages: [],
            banners: [],
        }
    },
    computed: {
        config() {
            return this.$root.config
        },
    },
    created() {
        this.loadPages()
        this.loadBanners()
    },
    methods: {
        loadPages() {
            axios.get('/api/pages')
            .then(response => {
                this.pages = response.data
            })
        },
        loadBanners() {
            axios.get('/api/banners')
            .then(response => {
                this.banners = response.data
            })
        },
        goToPage(page) {
            page.blocks.forEach(block => {
                if(block.type == 'video') {
                    setTimeout(() => {
                        let video = document.getElementById('video_' + block.id)

                        if(video) {
                            video.play()
                        }
                    }, 100)
                }
            })

            this.$router.push({name: 'Page', params: {id: page.id}})
        },
    },
    components: {
        PesokVertical
    }
}
</script>