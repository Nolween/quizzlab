import { createRouter, createWebHistory } from 'vue-router'

import Questions from '../views/Questions.vue'
import QuestionCard from '../views/QuestionCard.vue'
import QuestionProposal from '../views/QuestionProposal.vue'
import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'
import Games from '../views/Games.vue'
import GameCreation from '../views/GameCreation.vue'
import GameJoining from '../views/GameJoining.vue'

const routes = [
    {
        path: '/:theme?',
        name: 'questions.index',
        component: Questions
    },
    {
        path: '/question/:id',
        name: 'question.show',
        component: QuestionCard
    },
    {
        path: '/connexion',
        name: 'connexion.create',
        component: Login
    },
    {
        path: '/proposition',
        name: 'proposition.create',
        component: QuestionProposal
    },
    {
        path: '/parties',
        name: 'games.index',
        component: Games
    },
    {
        path: '/partie/creation',
        name: 'games.create',
        component: GameCreation
    },
    {
        path: '/partie/preparation/:id',
        name: 'games.join',
        component: GameJoining
    },
    {
        path: '/inscription',
        name: 'inscription.create',
        component: Registration
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})