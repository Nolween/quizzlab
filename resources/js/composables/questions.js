import { ref, computed, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";

export function useQuestions() {
    const question = ref([]);
    const suggestedQuestions = ref([]);
    // const forbiddenQuestion = ref(false);

    const errors = ref("");
    const router = useRouter();

    // Liste des question suggérées calculée
    const computedSuggestedQuestion = computed(() => {
        return suggestedQuestions.value;
    });

    // const updateForbiddenQuestion = (value) => {
    //     forbiddenQuestion.value = value
    // }

    //? Fonctions Asynchrones
    // Récupérer les questions suggérées selon la recherche dans le back
    const getSuggestedQuestions = async (questionSearch) => {
        if (questionSearch.value.trim() !== "" && questionSearch.value !== null) {
            try {
                let response = await axios.get(`/api/questions/search`, {
                    params: { question: questionSearch.value },
                });
                // Si on a pas le droit d'aller sur la question car intégrée au quizz
                if (response.data.data) {
                    suggestedQuestions.value = response.data.data;
                } else {
                    suggestedQuestions.value = [];
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        } else {
            suggestedQuestions.value = [];
        }
    };

    // Réinitialisation des questions suggérées
    const resetSuggestedQuestions = () => {
        suggestedQuestions.value = [];
    }

    return {
        errors,
        question,
        suggestedQuestions,
        computedSuggestedQuestion,
        getSuggestedQuestions,
        resetSuggestedQuestions,
        // forbiddenQuestion,
        // updateForbiddenQuestion,
    };
}
