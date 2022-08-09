import { ref, computed, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";

export function useTags() {
    const tag = ref([]);
    const suggestedTags = ref([]);
    // const forbiddenTag = ref(false);

    const errors = ref("");
    const router = useRouter();

    // Liste des tag suggérées calculée
    const computedSuggestedTag = computed(() => {
        return suggestedTags.value;
    });

    //? Fonctions Asynchrones
    // Récupérer les tags suggérées selon la recherche dans le back
    const getSuggestedTags = async (tagSearch) => {
        if (tagSearch.value.trim() !== "" && tagSearch.value !== null) {
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

    // Réinitialisation des tags suggérées
    const resetSuggestedTags = () => {
        suggestedTags.value = [];
    }

    return {
        errors,
        tag,
        suggestedTags,
        computedSuggestedTag,
        getSuggestedTags,
        resetSuggestedTags,
    };
}
