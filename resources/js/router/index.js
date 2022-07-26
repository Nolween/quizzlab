import { createRouter, createWebHistory } from 'vue-router'

import Questions from '../views/Questions.vue'
import Login from '../views/Login.vue'

const routes = [
    {
        path: '/',
        name: 'questions.index',
        component: Questions
    },
    {
        path: '/login',
        name: 'login.create',
        component: Login
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})