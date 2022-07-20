import { createRouter, createWebHistory } from 'vue-router'

import WelcomeButton from '../components/WelcomeButton.vue'

const routes = [
    {
        path: '/dashboard',
        name: 'companies.index',
        component: WelcomeButton
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})