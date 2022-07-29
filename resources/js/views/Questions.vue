<template>
    <div class="pt-24 flex justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5">
            <Question
                :questionId="question.id"
                :answer="question.answer"
                :question="question.question"
                :avatar="question.avatar"
                :vote="question.vote"
                :userName="question.userName"
                :ago="question.ago"
                :tags="question.tags"
                :commentsCount="question.commentsCount"
                :hasVoted="question.hasVoted"
                v-for="question in questionStore.questions"
                :key="question.id"
            />
        </div>
    </div>
</template>
<script setup>
// Imports de fonctionnalités essentielles de Vue (hook, ...)
import { onMounted, onBeforeMount, ref, reactive } from "vue";
// Import des stores
import { useQuestionStore } from "@/stores/question";
import { useUserStore } from "@/stores/user";
// Import des composants
import Question from "../components/Question.vue";
// Déclaration du store des questions
const questionStore = useQuestionStore();
//? Vérification si l'utilisateur est connecté
const userStore = useUserStore();
onBeforeMount(() => {
    userStore.checkAuth();
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(questionStore.getQuestions());
</script>
