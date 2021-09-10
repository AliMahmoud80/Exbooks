import { createApp } from 'vue'
import router from './router/router'
import store from './store/sotre'
import './plugins/axios'
import App from './App.vue'

createApp(App).use(router).use(store).mount('#app')
