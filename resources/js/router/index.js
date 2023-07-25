import { createRouter, createWebHistory } from 'vue-router'

import Questions from '../views/Questions.vue'
import QuestionCard from '../views/QuestionCard.vue'
import QuestionProposal from '../views/QuestionProposal.vue'
import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'
import Games from '../views/Games.vue'
import GameCreation from '../views/GameCreation.vue'
import GameQuestion from '../views/GameQuestion.vue'
import GameJoining from '../views/GameJoining.vue'
import GameResults from '../views/GameResults.vue'
import AccountInformations from "../views/AccountInformations.vue";

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
        path: '/partie/question/:id',
        name: 'games.question',
        component: GameQuestion
    },
    {
        path: '/partie/resultats/:id',
        name: 'games.results',
        component: GameResults
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
    },
    {
        path: '/account/informations',
        name: 'account.informations',
        component: AccountInformations
    },
];

export default createRouter({
    history: createWebHistory(),
    routes
})
