<template>
    <div>
        <div class="panzoom-wrapper" :class="[`${views.windowWidth > 1920 ? 'map4K':'map1080'}`]">
            <div class="panzoom-controls">
                <button @click="zoomIn()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
                <br>
                <button @click="zoomOut()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                    </svg>
                </button>
                <br>
                <button @click="openSearchPanel()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>

            <div id="panzoom">
                <div id="map" class="map">
                    <template v-for="scheme in schemes">
                        <img v-show="scheme.id == selected.scheme.id" :src="scheme.image">

                        <svg v-if="scheme.id == selected.scheme.id" class="map-path svg1">
                            <template v-for="(point, index) in selected.route.route_code_floor1">
                                <template v-if="selected.route.route_code_floor1[index - 1]">
                                    <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', ' + selected.route.route_code_floor1[index - 1].x + ' ' + selected.route.route_code_floor1[index - 1].y"></path>
                                </template>
                                <template v-else>
                                    <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', '"></path>
                                </template>
                                <circle v-if="point.x" :cx="point.x" :cy="point.y" r="0" fill="#f33"></circle>
                            </template>

                            <template v-for="(point, index) in selected.route.route_code_floor2">
                                <template v-if="selected.route.route_code_floor2[index - 1]">
                                    <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', ' + selected.route.route_code_floor2[index - 1].x + ' ' + selected.route.route_code_floor2[index - 1].y"></path>
                                </template>
                                <template v-else>
                                    <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', '"></path>
                                </template>
                                <circle v-if="point.x" :cx="point.x" :cy="point.y" r="0" fill="#f33"></circle>
                            </template>

                            <template v-for="point in points.filter(p => p.scheme_id == selected.scheme.id)">
                                <polygon v-if="selected.route.point_id == point.id" @click="selectPoint(point)" :points="point.object.join()" style="fill:rgba(0,0,255,0.05);stroke:transparent;stroke-width:1"></polygon>
                                <polygon v-else @click="selectPoint(point)" :points="point.object.join()" style="fill:transparent;stroke:transparent;stroke-width:1"></polygon>
                                
                                <!-- <text v-if="selected.route.point_id == point.id" style="stroke: #ffffff; stroke-width: 0.2px;" :x="point.object[0].split(' ')[0]" :y="point.object[0].split(' ').pop()" font-family='Verdana' font-size='6' fill='blue' text-anchor="left">
                                    <tspan dx='5' dy='5' font-weight='bold'>{{ point.name }}</tspan>
                                </text> -->
                            </template>
                        </svg>
                    </template>

                    <!-- <svg v-if="selected.slide === 2" class="map-path svg2">
                        <template v-for="(point, index) in selected.route.route_code_floor2">
                            <template v-if="selected.route.route_code_floor2[index - 1]">
                                <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', ' + selected.route.route_code_floor2[index - 1].x + ' ' + selected.route.route_code_floor2[index - 1].y"></path>
                            </template>
                            <template v-else>
                                <path v-if="point.x" class="key-anim01" fill="none" stroke-width="5px" stroke="rgba(255,51,51,0.8)" :d="'M' + point.x + ' ' + point.y + ', '"></path>
                            </template>
                            <circle v-if="point.x" id="02" :cx="point.x" :cy="point.y" r="0" fill="#f33"></circle>
                        </template>
                    </svg> -->

                    <template v-if="kiosks.length">
                        <svg style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 100%; height: 100%;">
                            <!-- <circle
                                fill="red"
                                :cx="kiosks.find(k => k.id == kiosk).x"
                                :cy="kiosks.find(k => k.id == kiosk).y"
                                r="6" >
                            </circle> -->

                            <g :transform="'translate(' + (kiosks.find(k => k.id == kiosk).x - 12) + ',' + (kiosks.find(k => k.id == kiosk).y - 12) + ')'">
                                <svg width="24" height="24" fill="none" stroke="none">
                                    <path fill="red" d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle fill="white" cx="12" cy="10" r="3"></circle>
                                </svg>
                            </g>

                            <text style="stroke: #ffffff; stroke-width: 0.2px;" :x="kiosks.find(k => k.id == kiosk).x" :y="kiosks.find(k => k.id == kiosk).y" font-family='Verdana' font-size='6' fill='blue' text-anchor="left">
                                <tspan dx='5' dy='5' font-weight='bold'>Вы здесь</tspan>
                            </text>
                        </svg>
                    </template>
                </div>
            </div>
        </div>

        <div class="routes-list">
            <swiper v-if="routes.length"
                :slides-per-view="1"
                :space-between="0"
                :loop="false"
                :allow-touch-move="true"
                @swiper="onSwiper"
                class="routes-list-slider">
                
                <swiper-slide v-for="i in Math.ceil(routes.length / 6)">
                    <ul>
                        <li v-for="route in routes.slice((i - 1) * 6, i * 6)" :key="route.id" @click="SelectRoute(route)">
                            <a>{{ route.point.name }}</a>
                        </li>
                    </ul>
                </swiper-slide>

                <button v-if="routes.length > 6" @click="prev()" class="routes-list-navigation routes-list-navigation-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </button>
                <button v-if="routes.length > 6" @click="next()" class="routes-list-navigation routes-list-navigation-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
            </swiper>
        </div>

        <!-- <button @click="searchPanel_button()" class="route-open-search-panel-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button> -->

        <!-- <div v-if="selected.route.schemes1" class="route-about">
            <template v-if="selected.route.schemes1 && selected.route.schemes1.id.toString() == selected.floor">
                {{selected.route.schemes1.name }}
            </template>

            <template v-if="selected.route.schemes2 && selected.route.schemes2.id.toString() === selected.floor">
                {{selected.route.schemes2.name }}
            </template>

            <br>
            {{selected.route.name}}
        </div> -->

        

        <!-- <button v-show="views.searchPanel == false && selected.slide === 2" @click="PrevScheme(selected.route)" class="prevnextbutton prev_button">
            Начало маршрута
        </button>
        <button v-show="views.searchPanel == false && selected.route.schemes2 && selected.route.schemes2 && selected.slide === 1" @click="NextScheme(selected.route)" class="prevnextbutton next_button">
            Продолжить маршрут
        </button> -->

        <div v-show="views.searchPanel" class="search-panel">
            <button @click="closeSearchPanel()" class="search-panel-close-button">&times;</button>
            <ul>
                <li v-for="route in filteredRoutes" :key="route.id" @click="SelectRoute(route)">
                    <a>{{ route.point.name }}</a>
                </li>
            </ul>

            <div class="search-panel-input">
                <input
                :value="searchInput"
                @input="onInputChange"
                placeholder="Поиск..."
                >

                <div class="kb">
                    <SimpleKeyboard @onChange="onChange" @onKeyPress="onKeyPress" :input="searchInput"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Panzoom from '@panzoom/panzoom'
import SimpleKeyboard from "./kbRoutes.vue"

import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'

export default {
    props: ['kiosk'],
    data() {
        return {
            schemes: [],
            routes: [],
            points: [],
            kiosks: [],

            selected: {
                scheme: '',
                route: ''
            },

            views: {
                searchPanel: false,
                windowWidth: '',
            },

            searchInput: '',

            panzoom: ''
        }
    },
    mounted() {
        this.panzoom = Panzoom(document.getElementById('panzoom'), {
            minScale: 1,
            maxScale: 3,
            contain: 'outside',
        })
    },
    created() {
        this.views.windowWidth = window.screen.availWidth

        this.loadSchemes()
        this.loadRoutes()
        this.loadPoints()
        this.loadKiosks()
    },
    computed: {
        filteredRoutes() {
            if (this.searchInput.trim() === '') {
                return this.routes
            } else {
                return this.routes.filter(route => {
                    return route.point.name.toLowerCase().indexOf(this.searchInput.toLowerCase()) >= 0
                })
            }
        },
    },
    methods: {
        loadSchemes() {
            axios.get('/api/schemes')
            .then(response => {
                this.schemes = response.data

                this.selected.scheme = response.data[0]
            })
        },
        loadRoutes() {
            axios.get(`/api/routes/${this.kiosk}`)
            .then(response => {
                this.routes = response.data
            })
        },
        loadPoints() {
            axios.get(`/api/points`)
            .then(response => {
                this.points = response.data
            })
        },
        loadKiosks() {
            axios.get(`/api/kiosks`)
            .then(response => {
                this.kiosks = response.data
            })
        },
        SelectRoute(route) {
            this.views.searchPanel = false
            this.searchInput = ''
            this.selected.floor = route.scheme1_id

            axios.get(`/api/route/${route.id}`)
            .then(response => {
                this.selected.route = response.data
                this.selected.slide = 1
            })
        },
        selectPoint(point) {
            this.selected.scheme = this.schemes.find(s => s.id == point.scheme_id)
            this.selected.route = point.routes.filter(r => r.scheme1_id == this.selected.scheme.id)[0]
        },
        PrevScheme(selectedRoute) {
            this.selected.floor = selected.route.scheme1_id
            this.selected.slide = 1
        },
        NextScheme(selectedRoute) {
            this.selected.floor = selected.route.scheme2_id
            this.selected.slide = 2
        },
        onChange(input) {
            this.searchInput = input
        },
        onKeyPress(button) {
            //console.log("button", button)
        },
        onInputChange(input) {
            this.searchInput = input.target.value
        },
        openSearchPanel() {
            this.views.searchPanel = true
            this.selected.route = {}
        },
        closeSearchPanel() {
            this.resetRoutes()
            this.views.searchPanel = false
        },
        resetRoutes() {
            this.selected.slide = 1
            this.selected.floor = 1
            this.selected.route = ''
            this.searchInput = ''
        },
        zoomIn() {
            this.panzoom.zoomIn()
        },
        zoomOut() {
            this.panzoom.zoomOut()
        },
        zoomReset() {
            this.panzoom.reset()
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
        Panzoom,
        SimpleKeyboard,
        Swiper,
        SwiperSlide,
    }
}
</script>