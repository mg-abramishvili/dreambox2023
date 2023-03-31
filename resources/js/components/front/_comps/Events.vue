<template>
    <div class="events">
        <swiper v-if="events.length"
            :slides-per-view="1"
            :space-between="0"
            :loop="false"
            :allow-touch-move="true"
            @swiper="onSwiper"
            class="events-slider">
            
            <swiper-slide v-for="(event, index) in events">
                <div class="event-slide">
                    <div class="event-slide-image" v-bind:style="{ 'background-image': 'url(' + event.image + ')' }"></div>
                    <div class="event-slide-datetime">{{ event.date }} {{ event.time }}</div>
                    <div @click="descriptionModal(event.description)" v-html="event.description" class="event-slide-desc"></div>
                    <div v-if="descriptionModalContent.length" class="description-modal">
                        <button @click="descriptionModalContent = ''" class="description-modal-close">&times;</button>
                        <div v-html="descriptionModalContent" class="description-modal-content"></div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>

        <button v-if="events.length > 1" @click="prev()" class="events-navigation events-navigation-prev">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
        </button>
        <button v-if="events.length > 1" @click="next()" class="events-navigation events-navigation-next">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'

export default {
    data() {
        return {
            events: [],

            descriptionModalContent: '',
        }
    },
    created() {
        this.loadEvents()
    },
    methods: {
        loadEvents() {
            axios.get('/api/events')
            .then(response => {
                this.events = response.data
            })
        },
        onSwiper(swiper) {
            this.swiper = swiper
        },
        prev() {
            let currentSlide = this.swiper.realIndex

            this.swiper.slideTo(currentSlide - 1)
        },
        next() {
            let currentSlide = this.swiper.realIndex

            this.swiper.slideTo(currentSlide + 1)
        },
        slideToZero() {
            this.swiper.slideTo(0)
        },
        descriptionModal(desc) {
            this.descriptionModalContent = desc
        },
    },
    components: {
        Swiper,
        SwiperSlide,
    },
}
</script>