<template>
    <div class="page">
        <div v-if="page.icon" class="page-icon" v-bind:style="{ 'background-image': 'url(' + page.icon.image + ')' }"></div>

        <div class="page-header">
            <h1>{{ page.name }}</h1>
        </div>

        <div class="page-content">
            <template v-for="block in page.blocks">
                <div v-if="block.type == 'text'" class="page-block page-block-text">
                    <div v-html="block.content"></div>
                </div>

                <div v-else-if="block.type == 'image'" class="page-block page-block-image" :class="[`${page.blocks.length > 1 ? 'page-block-image-mini':'page-block-image-max'}`]">
                    <img :src="block.content" alt="">
                </div>

                <div v-else-if="block.type == 'image_slider'" class="page-block page-block-image-slider" :class="[`${page.blocks.length > 1 ? 'page-block-image-slider-mini':'page-block-image-slider-max'}`]">
                    <swiper v-if="block.content"
                        :slides-per-view="1"
                        :space-between="0"
                        :loop="false"
                        :allow-touch-move="true"
                        @swiper="onSwiper"
                        class="image_slider-slider" >
                        
                        <swiper-slide v-for="(s, index) in block.content.split(',')">
                            <div class="image_slider-slide" v-bind:style="{ 'background-image': 'url(' + s + ')' }"></div>
                        </swiper-slide>

                        <button v-if="block.content.split(',').length > 1" @click="prev()" class="page-block-navigation page-block-navigation-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                            </svg>
                        </button>
                        <button v-if="block.content.split(',').length > 1" @click="next()" class="page-block-navigation page-block-navigation-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    </swiper>
                </div>

                <div v-else-if="block.type == 'video'" class="page-block page-block-video">
                    <video v-if="block.content" :id="'video_' + block.id" autoplay controls disablePictureInPicture controlsList="noplaybackrate nodownload">
                        <source :src="block.content" type="video/mp4" />
                    </video>
                </div>

                <div v-else-if="block.type == 'pdf'" class="page-block page-block-pdf">
                    <iframe :src="'/pdfjs/web/viewer.html?file=' + block.content" height="100%" width="100%" style="border: none;"></iframe>
                </div>

                <div v-else-if="block.type == 'excel'" class="page-block page-block-excel">
                    <div :id="XlsxTable(block)" style="text-align: center;"></div>
                </div>

                <div v-else-if="block.type == 'iframe'">
                    <iframe :src="block.content" frameborder="0" style="width: 100%; height: 85vh; background: white"></iframe>
                </div>

                <div v-else-if="block.type == 'routes'">
                    <Routes ref="routes" :kiosk="kiosk" />
                </div>
            </template>

            <template v-if="page.is_folder">
                <ul class="folder">
                    <li @click="goTo(page, pg)" v-for="pg in page.children">
                        <div v-if="pg.icon" class="icon" v-bind:style="{ 'background-image': 'url(' + pg.icon.image + ')' }"></div>
                        <span>{{ pg.name }}</span>
                    </li>
                </ul>
            </template>
        </div>

        <div class="footer page-footer">
            <button v-if="page.parent" @click="goTo(page, page.parent)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </button>

            <button @click="goTo(page, 'home')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
import { read, utils } from 'xlsx'

import Routes from '../../_comps/Routes.vue'

import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper.min.css'

export default {
    props: ['page', 'kiosk'],
    data() {
        return {
            //
        }
    },
    methods: {
        XlsxTable(block) {
            axios.get(block.content, { responseType: 'arraybuffer' })
            .then(response => {
                let wb = read(response.data)
                
                let div = document.getElementById(block.id)

                if(!div.innerHTML) {
                    document.getElementById(block.id).innerHTML = utils.sheet_to_html(wb.Sheets[wb.SheetNames[0]], { header: '', footer: '' })
                }
            })

            return block.id
        },
        goTo(fromPage, toPage) {
            this.$parent.goTo(fromPage, toPage)
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
        }
    },
    components: {
        Routes,
        Swiper,
        SwiperSlide,
    }
}
</script>