<template>
    <div class="pt-24 flex justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5">
            <!-- Formulaire de recherche de question-->
            <form @submit.prevent="refreshQuestions">
                <input
                    name="question"
                    class="mb-4 border-2 rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                    type="text"
                    placeholder="Chercher une question"
                    v-model="questionSearch"
                    @keyup="getQuestionsSuggestions()"
                    v-focus
                />
            </form>

            <div
                v-if="computedSuggestedQuestion.length > 0"
                class="w-full z-50"
            >
                <!-- Composant de proposition de question -->
                <SuggestedQuestions
                    @change-search="updateQuestionSearch($event)"
                    :suggestedQuestions="computedSuggestedQuestion"
                />
            </div>
            <!-- Liste des question -->
            <Question
                :questionId="question.id"
                :answer="question.answer"
                :question="question.question"
                :avatar="question.avatar"
                :vote="question.vote"
                :userName="question.userName"
                :ago="question.ago"
                :tags="question.tags"
                :isIntegrated="question.isIntegrated"
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
import { onMounted, onBeforeMount, ref, computed } from "vue";
// Import des stores
import { useQuestionStore } from "@/stores/question";
import { useUserStore } from "@/stores/user";
import { useQuestions } from "@/composables/questions.js";
// Import des composants
import Question from "../components/Question.vue";
import SuggestedQuestions from "../components/SuggestedQuestions.vue";
const emit = defineEmits(["changeSearch"]);
// Import des composables
const {
    getSuggestedQuestions,
    resetSuggestedQuestions,
    computedSuggestedQuestion,
} = useQuestions();
// Déclaration du store des questions
const questionStore = useQuestionStore();
//? Vérification si l'utilisateur est connecté
const userStore = useUserStore();

// Variable de recherche de question
const questionSearch = ref(null);

// Liste des question suggérées calculée
const computedQuestionSearch = computed(() => {
    return questionSearch.value;
});

const updateQuestionSearch = async (newQuestion) => {
    // On met à jour la question dans le champ
    questionSearch.value = newQuestion;
    // Réinitialisation des suggestions de question
    resetSuggestedQuestions();
    // Soumission du formulaire dans le back pour récupérer les questions
    await questionStore.getQuestions(newQuestion)
    questionSearch.value = null
};

const refreshQuestions = async () => {
    // Réinitialisation des suggestions de question
    resetSuggestedQuestions();
    // Soumission du formulaire dans le back pour récupérer les questions
    await questionStore.getQuestions(questionSearch.value)
};



const getQuestionsSuggestions = async () => {
    await getSuggestedQuestions(computedQuestionSearch);
};

//? Cycle

// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

onBeforeMount(() => {
    userStore.checkAuth();
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(questionStore.getQuestions());
</script>
