<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5">
            <div class="w-full flex space-x-3 mb-6">
                <!-- Création de partie + Code de partie -->
                <button
                    type="button"
                    class="bg-quizzlab-secondary text-white text-3xl font-semibold p-3 w-full md:w-1/2"
                    @click="router.push({ name: 'games.create' })"
                >
                    Créer
                </button>
                <button
                    type="button"
                    class="bg-quizzlab-quinary text-white text-3xl font-semibold p-3 w-full md:w-1/2"
                    @click="gameCodeOverlay = true"
                >
                    Code de partie
                </button>
            </div>
            <!-- Formulaire de recherche de question-->
            <form @submit.prevent="[refreshGames]">
                <div class="flex flex-wrap w-full justify-center mb-10">
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
                <div
                    v-if="computedSuggestedTag.length > 0"
                    class="w-full z-50 mb-4"
                >
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
                :hasBegun="game.hasBegun"
                :questionStep="game.questionStep"
                :ago="game.ago"
                :tags="game.tags"
                @change-search="updateGameSearch($event)"
            ></Game>
        </div>
        <!-- Bouton de rafraichissement des partiies -->
        <button
            class="bg-quizzlab-ternary w-14 h-14"
            id="refresh-button"
            title="Rafraichir les parties"
            @click="gameStore.getGames()"
        >
            <svg-icon
                @click=""
                class="text-white my-auto mx-auto"
                :path="mdiRefresh"
                type="mdi"
            ></svg-icon>
        </button>

        <!-- OVERLAY DE CODE PARTIE -->

        <div
            v-if="gameCodeOverlay"
            class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
        >
            <div class="w-4/5">
                <div
                    class="text-quizzlab-primary bg-white py-3 px-2 font-bold flex justify-between"
                >
                    <span class="px-3 text-3xl lg:text-5xl"
                        >Entrez votre code de partie</span
                    >
                    <svg-icon
                        @click="gameCodeOverlay = false"
                        class="text-quizzlab-ternary h-10 w-10 my-auto cursor-pointer"
                        :path="mdiCloseBox"
                        type="mdi"
                    ></svg-icon>
                </div>
                <form @submit.prevent="joinByGameCode">
                    <div
                        class="w-full flex flex-wrap justify-center bg-white p-3 text-center space-x-3 space-y-4"
                    >
                        <div class="w-full">
                            <input
                                ref="gameCodeInput"
                                name="gameCode"
                                class="w-4/5 border-2 rounded-sm px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-5"
                                type="text"
                                placeholder="Votre code"
                                v-model="gameCode"
                            />
                        </div>

                        <div class="w-full">
                            <button
                                type="button"
                                class="bg-quizzlab-secondary text-white font-semibold text-2xl md:text-4xl p-4"
                                @click="joinByGameCode"
                            >
                                REJOINDRE
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
import { mdiMagnify, mdiRefresh, mdiCloseBox } from "@mdi/js";

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
// Code de partie
const gameCode = ref(null);
const gameCodeInput = ref(null);
// Overlay du code de partie
const gameCodeOverlay = ref(false);

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

const joinByGameCode = async () => {
    // Réinitialisation des suggestions
    await gameStore.verifyGameCode(gameCode.value);
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
<style scoped>
#refresh-button {
    position: fixed;
    right: 40px;
    bottom: 40px;
}
</style>
