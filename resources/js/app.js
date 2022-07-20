import './bootstrap';

import Alpine from 'alpinejs';

import { createApp } from 'vue';
import router from './router'

import WelcomeButton from './components/WelcomeButton.vue';

window.Alpine = Alpine;

Alpine.start();


createApp({
    components: {
      WelcomeButton
    }
}).use(router).mount('#app')