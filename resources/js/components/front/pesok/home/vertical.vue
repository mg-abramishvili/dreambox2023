<template>
    <div class="home">
        <div class="banners">
            <swiper v-if="banners.length"
                :modules="modules"
                :slides-per-view="1"
                :space-between="0"
                :loop="swiperConfig('loop')"
                :autoplay="swiperConfig('autoplay')"
                :autoplayDelay="5000"
                :autoplayDisableOnInteraction="false"
                :allow-touch-move="swiperConfig('allowTouchMove')"
                @swiper="onSwiper"
                class="banner-home-slider">
                
                <swiper-slide v-for="(banner, index) in banners">
                    <div class="banner-home-slide" v-bind:style="{ 'background-image': 'url(' + banner.content + ')' }"></div>
                </swiper-slide>
            </swiper>
        </div>

        <div class="home-main">
            <div class="sidebar">
                <div class="logo">
                    <img :src="$parent.config.logo" alt="">
                    <p>{{ $parent.config.title }}</p>
                    <span>Общественное<br>пространство</span>
                </div>
                <div class="sidebar-bottom">
                    <img src="/img/pesok/sidebar-bottom1.png" alt="">
                    <img src="/img/pesok/sidebar-bottom2.png" alt="">
                </div>
            </div>
            <div class="menu">
                <ul>
                    <li @click="goToPage(page)" v-for="page in pages" class="menu-item">
                        <div v-if="page.icon" class="menu-item-image" v-bind:style="{ 'background-image': 'url(' + page.icon.image + ')' }"></div>
                        <span>{{ page.name }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { Autoplay } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/autoplay'

export default {
    props: ['pages', 'banners'],
    data() {
        return {
            views: {
                sliderArrows: true,
            }
        }
    },
    setup() {
        return {
            modules: [Autoplay],
        }
    },
    methods: {
        onSwiper(swiper) {
            this.swiper = swiper
        },
        swiperConfig(param) {
            if(this.banners.length > 1) {
                return true
            } else {
                return false
            }
        },
        goToPage(page) {
            this.$parent.goToPage(page)
        },
    },
    components: {
        Swiper,
        SwiperSlide,
    },
}
</script>