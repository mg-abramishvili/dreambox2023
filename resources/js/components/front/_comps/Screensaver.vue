<template>
    <div class="screensaver-content">

        <swiper v-if="slides.length"
            :slides-per-view="1"
            :space-between="0"
            :loop="false"
            :autoplayDisableOnInteraction="false"
            :allow-touch-move="false"
            :speed="1"
            @swiper="onSwiper"
            class="ScreensaverHooper">
            
            <swiper-slide v-for="(slide, index) in slides" class="ScreensaverHooper-slide">
                <img v-if="slide.type == 'image' && slide.content" :src="slide.content"/>
                <video v-if="slide.type == 'video' && slide.content" :id="'video_' + slide.id" muted nocontrols>
                    <source :src="slide.content" type="video/mp4">
                </video>
            </swiper-slide>
        </swiper>
        
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'

export default {
    props: ['slides'],
    data() {
        return {
            timeout: ''
        }
    },
    created() {
        setTimeout(() => { this.slideTo(0) }, 500)
    },
    methods: {
        onSwiper(swiper) {
            this.swiper = swiper
        },
        slideTo(index) {
            this.swiper.slideTo(index)

            this.timeout = ''
            
            let currentSlide = this.slides[index]
            let nextSlide = 0
            let duration = 0

            if(this.slides[index + 1]) {
                nextSlide = index + 1
            }

            if(currentSlide.type == 'video') {
                let video = document.getElementById('video_' + this.slides[index].id)

                video.pause()
                video.currentTime = 0
                video.play()
                
                duration = Math.floor(video.duration * 10) / 10 * 1000
            } else {
                duration = 5000
            }

            this.timeout = setTimeout(() => {
                if(this.$parent.screensaver.isActive) {
                    this.slideTo(nextSlide)
                }
            }, duration)
        },
        stopSlide() {
            clearTimeout(this.timeout)
        },
    },
    components: {
        Swiper,
        SwiperSlide,
    }
}
</script>