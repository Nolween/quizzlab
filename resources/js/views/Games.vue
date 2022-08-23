<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5 space-y-2">
            <!-- Création de partie + Code de partie -->
            <button
                type="button"
                class="bg-quizzlab-secondary text-white text-3xl font-semibold p-3 w-full md:w-1/2"
            >
                Créer
            </button>
            <button
                type="button"
                class="bg-quizzlab-quinary text-white text-3xl font-semibold p-3 w-full md:w-1/2"
            >
                Code de partie
            </button>

            <!-- Formulaire de recherche de question-->
            <form @submit.prevent="[refreshGames]">
                <div class="flex flex-wrap w-full mb-1 justify-center">
                    <input
                        name="tag"
                        class="border-2 rounded-sm w-4/5 lg:w-5/6 px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                        type="text"
                        placeholder="Chercher un thème"
                        v-model="searchInput"
                        @keyup="getSuggestions()"
                        v-focus
                    />
                    <button
                        class="bg-quizzlab-secondary w-14 h-14 border-2 ml-1"
                        @click="refreshGames"
                    >
                        <svg-icon
                            @click=""
                            class="text-white my-auto mx-auto"
                            :path="mdiMagnify"
                            type="mdi"
                        ></svg-icon>
                    </button>
                </div>

                <!-- Suggestion de Thèmes / Questions -->
                <div v-if="computedSuggestedTag.length > 0" class="w-full z-50">
                    <!-- Composant de proposition de question -->
                    <SuggestedTags
                        @change-search="updateGameSearch($event)"
                        :suggestedTags="computedSuggestedTag"
                    />
                </div>
            </form>
            <Game
                v-for="game in gameStore.games"
                :id="game.id"
                :gameRule="game.gameRule"
                :questionCount="game.questionCount"
                :waitingPlayers="game.waitingPlayers"
                :maxPlayers="game.maxPlayers"
                :responseTime="game.responseTime"
                :gameCode="game.gameCode"
                :question="game.question"
                :avatar="game.avatar"
                :commentsCount="game.commentsCount"
                :userName="game.userName"
                :ago="game.ago"
                :tags="game.tags"
                @change-search="updateGameSearch($event)"
            ></Game>
        </div>
    </div>
</template>
<script setup>
import router from "@/router";
import { ref, computed, onBeforeMount, onMounted } from "vue";
// Import des composants
import SuggestedTags from "../components/SuggestedTags.vue";
import Game from "../components/Game.vue";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiMagnify } from "@mdi/js";

// Imports de stores;
import { useGameStore } from "@/stores/game";
import { useUserStore } from "@/stores/user";
// Import des composables
import { useTags } from "@/composables/tags.js";
// Déclaration des emits
const emit = defineEmits(["changeSearch"]);
// Déclararation de stores
const gameStore = useGameStore();
const userStore = useUserStore();
// Déclaration des composables
const { getSuggestedTags, resetSuggestedTags, computedSuggestedTag } =
    useTags();
// Variable de recherche de question
const searchInput = ref(null);

// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

//? Computed
// Champ de recherche en temps réel
const computedSearch = computed(() => {
    return searchInput.value;
});

const getSuggestions = async () => {
    await getSuggestedTags(computedSearch);
};

const updateGameSearch = async (newTag) => {
    // On met à jour le thème dans le champ
    searchInput.value = newTag;
    // Réinitialisation des suggestions de thème
    resetSuggestedTags();
    // On revient en haut de la page
    window.scrollTo(0, 0);
    // Soumission du formulaire dans le back pour récupérer les parties
    await gameStore.getGames(newTag);
};

const refreshGames = async () => {
    // Réinitialisation des suggestions
    resetSuggestedTags();
    // Soumission du formulaire dans le back pour récupérer les parties
    await gameStore.getGames(searchInput.value);
};

onBeforeMount(() => {
    // Reset des suggestion pour éviter la pollution d'anciennes pages
    resetSuggestedTags();
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected == false) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    }
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(() => {
    gameStore.getGames();
});
</script>
