import './bootstrap';

import Alpine from 'alpinejs';

import { createApp } from 'vue';
import router from './router'

import App from './views/App.vue';

window.Alpine = Alpine;

Alpine.start();


createApp(App).use(router).mount('#app')