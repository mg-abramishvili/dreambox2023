<template>
    <div class="home">
        <div class="menu">
            <swiper v-if="pages.length"
                :slides-per-view="3"
                :slides-per-column="2"
                :slides-per-column-fill="'column'"
                :space-between="50"
                :allow-touch-move="true"
                @swiper="onSwiper"
                class="menu-slider">
                
                <swiper-slide v-for="(page, index) in pages" @click="goToPage(page)" class="menu-item">
                    <div class="menu-item-image" v-bind:style="{ 'background-image': 'url(' + page.icon.image + ')' }"></div>
                    <span>{{ page.name }}</span>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper.min.css'

export default {
    props: ['pages', 'banners'],
    data() {
        return {
            views: {
                sliderArrows: true,
            }
        }
    },
    methods: {
        onSwiper(swiper) {
            this.swiper = swiper
        },
        goToPage(page) {
            this.$parent.goToPage(page)
        },
        slideToZero() {
            this.swiper.slideTo(0)
        }
    },
    components: {
        Swiper,
        SwiperSlide,
    },
}
</script>