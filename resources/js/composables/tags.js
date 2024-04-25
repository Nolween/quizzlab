import { ref, computed, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";

export function useTags() {
    const tag = ref([]);
    const suggestedTags = ref([]);
    const tags = ref([]);
    const tagSearch = ref('');
    // const forbiddenTag = ref(false);
    const possibleQuestions = ref(null);

    const errors = ref("");
    const router = useRouter();

    // Liste des tag suggérées calculée
    const computedSuggestedTag = computed(() => {
        return suggestedTags.value;
    });

    // Liste des tags selon ce que l'on recherche
    const filteredTags = computed(() => {
        // Si on a une recherche dans le champ
        if (tagSearch.value.trim() !== "" && tagSearch.value !== null) {
            let escapedSearch = tagSearch.value.replace(
                /[-[\]{}()*+?.,\\^$|#\s]/g,
                "\\$&"
            );
            // Initialisation de la chaine qui définira le regex
            var regexRapidValue = new RegExp(escapedSearch, "i");
            let filtered = tags.value.filter((element) => {
               return regexRapidValue.test(element.name.toString());
            });
            return filtered;
        }
        // Si le champ de recherche est vide
        else {
            return tags.value;
        }
    });

    //? Fonctions Asynchrones
    // Récupérer les tags suggérées selon la recherche dans le back
    const getSuggestedTags = async (tagSearch) => {
        if (tagSearch.value && tagSearch.value.trim() !== "" && tagSearch.value !== null) {
            try {
                let response = await axios.get(`/api/tags/search`, {
                    params: { tag: tagSearch.value },
                });
                // Si on a bien un retour d'Elastic
                if (response.data.data) {
                    suggestedTags.value = response.data.data;
                } else {
                    suggestedTags.value = [];
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        } else {
            suggestedTags.value = [];
        }
    };

    // Récupérer tous les tags
    const getAllTags = async () => {
        try {
            let response = await axios.get(`/api/tags`);
            // Si on a bien un retour d'Elastic
            if (response.data.data) {
                tags.value = response.data.data;
            } else {
                tags.value = [];
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };
    // Récupérer le nombre de questions selon les tags sélectionnés
    const getQuestionsTagsCount = async (allTags, selectedThemes ) => {
        try {
            let response = await axios.get(`/api/tags/questions/count`, {
                params: { tags: selectedThemes, allTags },
            });
            // Si on a bien un retour
            if (response.data) {
                possibleQuestions.value = response.data.possibleQuestions;
            } else {
                possibleQuestions.value = 0;
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
            possibleQuestions.value = 0;
        }
    };

    // Réinitialisation des tags suggérées
    const resetSuggestedTags = () => {
        suggestedTags.value = [];
    };

    return {
        errors,
        tag,
        tags,
        suggestedTags,
        tagSearch,
        computedSuggestedTag,
        filteredTags,
        getSuggestedTags,
        getAllTags,
        resetSuggestedTags,
        getQuestionsTagsCount,
        possibleQuestions,
    };
}
