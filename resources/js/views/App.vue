<template>
    <Navigation />
    <div class="min-h-screen bg-quizzlab-primary pt-24 flex justify-center">
        <div class="w-5/6 lg:w-4/5">
            <Question
                :answer="question.answer"
                :question="question.question"
                :avatar="question.avatar"
                :vote="question.vote"
                :userName="question.userName"
                :ago="question.ago"
                :tags="question.tags"
                :commentsCount="question.commentsCount"
                v-for="question in questions"
                :key="question.id"
            />
        </div>
        <!-- <template v-for="question in questions" :key="question.id">
                {{ question.answer }}
        </template> -->
    </div>
</template>
<script setup>
// Imports de fonctionnalités essentielles de Vue (hook, ...)
import { onMounted, ref } from "vue";
// Import des composants
import Navigation from "../components/Navigation.vue";
import Question from "../components/Question.vue";
// Imports de composables
import useQuestions from "../../js/composables/questions";
// Déclaration des constantes à utiliser dans le composant
const { questions, getQuestions } = useQuestions();

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(getQuestions);
// export default {
//     setup() {
//         const { questions, getQuestions } = useQuestions();

//         onMounted(getQuestions);

//         return {
//             questions,
//         };
//     },
// };
</script>
