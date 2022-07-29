import { createRouter, createWebHistory } from 'vue-router'

import Questions from '../views/Questions.vue'
import QuestionCard from '../views/QuestionCard.vue'
import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'

const routes = [
    {
        path: '/',
        name: 'questions.index',
        component: Questions
    },
    {
        path: '/question/:id',
        name: 'question.show',
        component: QuestionCard
    },
    {
        path: '/login',
        name: 'login.create',
        component: Login
    },
    {
        path: '/registration',
        name: 'registration.create',
        component: Registration
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})