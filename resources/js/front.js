import './bootstrap'

import { createApp } from 'vue'
import router from './front-router'

import App from './components/front/App.vue'

import dayjs from 'dayjs'
import 'dayjs/locale/ru'

const app = createApp(App).use(router)

app.config.globalProperties.$dayjs = dayjs

app.mount('#front')