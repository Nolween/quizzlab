import { ref, computed, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

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
        if (
            questionSearch && questionSearch.value.trim() !== "" &&
            questionSearch.value !== null
        ) {
            try {
                let response = await axios.get(`/api/questions/search`, {
                    params: { question: questionSearch.value },
                });
                // Si on a bien un retour d'Elastic
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

    // Proposition d'une nouvelle question
    const sendQuestionProposition = async (data) => {
        try {
            // Création d'un formulaire
            let formData = new FormData();
            formData.append('_method', 'post');
            formData.append('question', data.question);
            formData.append('rules', data.rules === true ? 1 : 0);
            formData.append('imageNeeded', data.imageNeeded === true ? 1 : 0);
            if (data.image && data.imageNeeded === true) {
                formData.append('image', data.image);
            }
            // Parcours des thèmes
            data.selectedThemes.forEach((tag) => {
                formData.append("selectedThemes[]", tag);
            });
            // Parcours des choix
            data.choices.forEach((choice) => {
                formData.append("choices[]", choice);
            });
            // Ajout d'une entête pour les fichiers
            let config = {
                header: {
                    "Content-Type": "multipart/form-data",
                },
            };
            // Envoi dans le back
            let response = await axios.post(`/api/questions`, formData, config);
            // Si on a bien un retour
            if (response.data && response.data.success === true) {
                // Notification
                const toast = useToast();
                toast.success(response.data.message);
                // Redirection vers l'accueil
                router.push({ name: "questions.index" });
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };


    // Modération d'une question avec modération
    const sendModerationQuestionWithEdition = async (data) => {
        try {
            // Création d'un formulaire
            let formData = new FormData();
            formData.append('_method', 'post');
            formData.append('question', data.question);
            formData.append('id', data.id);
            formData.append('imageNeeded', data.imageNeeded === true ? 1 : 0);
            if (data.image && data.imageNeeded === true) {
                formData.append('image', data.image);
            }
            // Parcours des choix
            let choices = Array.from(data.choices);
            choices.forEach((choice) => {
                formData.append("choices[]", choice);
            });
            // Parcours des Thèmes
            let selectedThemes = Array.from(data.selectedThemes);
            selectedThemes.forEach((tag) => {
                formData.append("selectedThemes[]", tag);
            });
            // Ajout d'une entête pour les fichiers
            let config = {
                header: {
                    "Content-Type": "multipart/form-data",
                },
            };
            // Envoi dans le back
            let response = await axios.post(`/api/admin/questions/${data.id}/moderateandedit`, formData, config);
            // Si on a bien un retour
            if (response.data && response.data.success === true) {
                // Notification
                const toast = useToast();
                toast.success(response.data.message);
                // Redirection vers l'accueil
                router.push({ name: "admin.questions" });
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };

    // Réinitialisation des questions suggérées
    const resetSuggestedQuestions = () => {
        suggestedQuestions.value = [];
    };

    return {
        errors,
        question,
        suggestedQuestions,
        computedSuggestedQuestion,
        getSuggestedQuestions,
        sendModerationQuestionWithEdition,
        resetSuggestedQuestions,
        sendQuestionProposition,
        // forbiddenQuestion,
        // updateForbiddenQuestion,
    };
}
