import './bootstrap';

import Alpine from 'alpinejs';

import { createApp } from 'vue';
import router from './router'

import App from './App.vue';
import Login from './views/Login.vue';

window.Alpine = Alpine;

Alpine.start();


createApp(App).use(router).mount('#app')