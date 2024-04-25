<template>
    <div class="pt-40 md:pt-32 justify-center bg-quizzlab-primary px-2">
        <div
            class="w-full text-center text-5xl text-white font-semibold mb-10 flex flex-wrap justify-around px-3 space-x-3">
            <div @click="resultMod = 0"
                 :class="resultMod === 1 ? 'bg-quizzlab-primary text-white' : 'bg-white text-quizzlab-primary'">
                <button type="button" class="text-4xl font-semibold py-3 px-8"> REPONSES</button>
            </div>
            <div @click="resultMod = 1"
                 :class="resultMod === 0 ? 'bg-quizzlab-primary text-white' : 'bg-white text-quizzlab-primary'">
                <button type="button" class="text-4xl font-semibold py-3 px-8"> CLASSEMENT</button>
            </div>
        </div>

        <!-- Résultats des questions-->
        <QuestionResult v-if="resultMod === 0"
                        v-for="(question, questionKey) in gameResults.questions" :key="questionKey"
                        :gameQuestionId="question.id"
                        :score="question.score"
                        :choices="question.allChoices"
                        :question="question.question"
                        :isCorrect="question.isCorrect">
        </QuestionResult>

        <!-- CLASSEMENT -->
        <div class="text-center" v-else>
            <div class="text-4xl font-semibold text-white">VOUS AVEZ POINTS !</div>
            <div class="text-4xl font-semibold text-white">VOUS ETES EME !</div>
        </div>

        <!--        <div v-for="(question, questionKey) in gameResults.questions" :key="questionKey">-->
        <!--            {{question.question}}-->
        <!--        </div>-->

    </div>
</template>
<script setup>
import {onBeforeMount, onMounted, ref} from "vue";

import QuestionResult from "../components/QuestionResult.vue";
// Import des stores
import {useGameStore} from "@/stores/game";
import {useUserStore} from "@/stores/user";
import {useGames} from "@/composables/games"

import {useRoute} from "vue-router";


// Déclaration de store
const gameStore = useGameStore();
const userStore = useUserStore();
const route = useRoute();
// Déclaration de composables
const {getGameResults, gameResults} = useGames();

const resultMod = ref(0);

onBeforeMount(() => {
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected === false) {
        // Redirection vers l'écran de connexion
        router.push({name: "connexion.create"});
    }
})

onMounted(() => {
    getGameResults(route.params.id)
})

</script>
