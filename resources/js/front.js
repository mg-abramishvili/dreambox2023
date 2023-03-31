import './bootstrap'

import { createApp } from 'vue'
import router from './front-router'

import App from './components/front/App.vue'

createApp(App).use(router).mount('#app')