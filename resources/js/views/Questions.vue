<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5">
            <!-- Formulaire de recherche de question-->
            <form @submit.prevent="[refreshQuestions]">
                <div class="flex flex-wrap w-full mb-1 justify-center">
                    <input
                        name="question"
                        class="border-2 rounded-sm w-4/5 lg:w-5/6 px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                        type="text"
                        :placeholder="
                            searchMod == 0
                                ? 'Chercher un thème'
                                : 'Chercher une question'
                        "
                        v-model="searchInput"
                        @keyup="getSuggestions()"
                        v-focus
                    />
                    <button
                        class="bg-quizzlab-secondary w-12 h-13 border-2 ml-1"
                        @click="refreshQuestions"
                    >
                        <svg-icon
                            @click=""
                            class="text-white my-auto mx-auto"
                            :path="mdiMagnify"
                            type="mdi"
                        ></svg-icon>
                    </button>
                </div>
                <div
                    class="w-full flex flex-wrap mb-4 justify-center space-x-2"
                >
                    <button
                        @click="updateSearchMod(0)"
                        type="button"
                        class="p-2 pb-10 h-12 text-2xl"
                        :class="
                            searchMod == 0
                                ? 'bg-white text-quizzlab-quinary'
                                : 'text-white bg-quizzlab-quinary'
                        "
                    >
                        Thème
                    </button>
                    <button
                        @click="updateSearchMod(1)"
                        type="button"
                        class="p-2 pb-10 h-12 text-2xl"
                        :class="
                            searchMod == 1
                                ? 'bg-white text-quizzlab-quinary'
                                : 'text-white bg-quizzlab-quinary'
                        "
                    >
                        Question
                    </button>
                </div>
            </form>
            <!-- Suggestion de Thèmes / Questions -->
            <div v-if="computedSuggestedTag.length > 0" class="w-full z-50">
                <!-- Composant de proposition de question -->
                <SuggestedTags
                    @change-search="updateQuestionSearch($event)"
                    :suggestedTags="computedSuggestedTag"
                />
            </div>
            <div
                v-else-if="computedSuggestedQuestion.length > 0"
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
import { useTags } from "@/composables/tags.js";
// Import des composants
import Question from "../components/Question.vue";
import SuggestedQuestions from "../components/SuggestedQuestions.vue";
import SuggestedTags from "../components/SuggestedTags.vue";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiMagnify } from "@mdi/js";

const emit = defineEmits(["changeSearch"]);
// Import des composables
const {
    getSuggestedQuestions,
    resetSuggestedQuestions,
    computedSuggestedQuestion,
} = useQuestions();
const { getSuggestedTags, resetSuggestedTags, computedSuggestedTag } =
    useTags();
// Déclaration du store des questions
const questionStore = useQuestionStore();
//? Vérification si l'utilisateur est connecté
const userStore = useUserStore();

// Variable de recherche de question
const searchInput = ref(null);
const searchMod = ref(0);

// Liste des question suggérées calculée
const computedSearch = computed(() => {
    return searchInput.value;
});

// Mise à jour du mod de recherche
const updateSearchMod = (value) => {
    searchInput.value = null
    // réinitialisation des des données de suggestions
    resetSuggestedQuestions();
    resetSuggestedTags();
    searchMod.value = value;
};

const updateQuestionSearch = async (newQuestion) => {
    // On met à jour la question dans le champ
    searchInput.value = newQuestion;
    // Réinitialisation des suggestions de question
    resetSuggestedQuestions();
    resetSuggestedTags();
    // Soumission du formulaire dans le back pour récupérer les questions
    await questionStore.getQuestions(newQuestion, searchMod.value);
};

const refreshQuestions = async () => {
    // Réinitialisation des suggestions
    resetSuggestedQuestions();
    resetSuggestedTags();
    // Soumission du formulaire dans le back pour récupérer les questions
    await questionStore.getQuestions(searchInput.value, searchMod.value);
};

const getSuggestions = async () => {
    // Si recherche de thème
    if (searchMod.value == 0) {
        await getSuggestedTags(computedSearch);
    }
    // Si on recherche une question
    else if (searchMod.value == 1) {
        await getSuggestedQuestions(computedSearch);
    }
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
