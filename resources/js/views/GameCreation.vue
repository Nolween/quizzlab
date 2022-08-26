<template>
    <div
        class="pt-40 sm:pt-36 md:pt-28 justify-center bg-quizzlab-primary px-2 flex flex-wrap w-full md:px-10"
    >
        <form class="space-y-6" @submit.prevent="sendGame">
            <div class="text-center text-5xl text-white font-semibold">
                Créer une partie
            </div>
            <!-- Nombre de joueurs -->
            <div class="flex justify-center space-x-3 mb-4">
                <span
                    class="w-full text-center bg-white text-3xl text-quizzlab-primary px-3 py-3 border-2 border-slate-500"
                >
                    Nombre de joueurs</span
                >
                <input
                    name="maxPlayers"
                    class="border-2 focus:border-quizzlab-ternary rounded-sm w-32 px-3 text-2xl text-quizzlab-primary text-center"
                    type="number"
                    min="1"
                    max="30"
                    v-model="form.maxPlayers"
                    v-focus
                />
            </div>
            <!-- Nombre de questions -->
            <div class="flex justify-center space-x-3 mb-4">
                <span
                    class="w-full text-center bg-white text-3xl text-quizzlab-primary px-3 py-3 border-2 border-slate-500"
                >
                    Nombre de questions</span
                >
                <input
                    name="questionCount"
                    class="border-2 focus:border-quizzlab-ternary rounded-sm w-32 px-3 text-2xl text-quizzlab-primary text-center"
                    type="number"
                    min="1"
                    max="50"
                    v-model="form.questionCount"
                />
            </div>
            <!-- Délai de réponse -->
            <div class="flex justify-center space-x-3 mb-4">
                <span
                    class="w-full text-center bg-white text-3xl text-quizzlab-primary px-3 py-3 border-2 border-slate-500"
                >
                    Délai de réponse (en secondes)</span
                >
                <input
                    name="responseTime"
                    class="border-2 focus:border-quizzlab-ternary rounded-sm w-32 px-3 text-2xl text-quizzlab-primary text-center"
                    type="number"
                    min="10"
                    max="180"
                    v-model="form.responseTime"
                />
            </div>
            <!-- THEME -->
            <div class="flex justify-between">
                <input
                    name="theme"
                    class="border-2 focus:border-quizzlab-ternary rounded-sm w-5/6 px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                    type="text"
                    placeholder="Thème"
                    v-model="theme"
                    ref="themeInput"
                    @keyup="getSuggestions()"
                />
                <div>
                    <button
                        type="button"
                        class="bg-quizzlab-secondary text-white w-12 h-12 border-2 ml-1"
                        title="Chercher dans la liste"
                        @click="themeListOverlay = true"
                    >
                        <svg-icon
                            @click=""
                            class="text-white my-auto mx-auto"
                            :path="mdiMagnify"
                            type="mdi"
                        ></svg-icon>
                    </button>
                </div>
            </div>
            <!-- Suggestion de Thèmes / Questions -->
            <div v-if="computedSuggestedTag.length > 0" class="w-9/12 z-50">
                <!-- Composant de proposition de question -->
                <SuggestedTags
                    @change-search="addTag($event)"
                    :suggestedTags="computedSuggestedTag"
                />
            </div>
            <!-- THEMES ASSOCIES-->
            <div
                v-if="form.selectedThemes.length > 0"
                class="text-center text-4xl text-white font-semibold"
            >
                {{
                    form.selectedThemes.length > 1
                        ? form.selectedThemes.length + " Thèmes Associés"
                        : form.selectedThemes.length + " Thème Associé"
                }}
            </div>
            <div class="flex flex-wrap justify-center">
                <span
                    v-for="(
                        selectedTheme, selectedThemeKey
                    ) in form.selectedThemes"
                    :key="selectedThemeKey"
                    class="bg-quizzlab-quaternary hover:bg-quizzlab-ternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                    @click="removeSelectedTheme(selectedThemeKey)"
                    >{{ selectedTheme }}</span
                >
            </div>
            <!-- Chackbox comportements tags question -->
            <div class="mt-1" v-if="form.selectedThemes.length > 1">
                <input
                    id="allTags"
                    type="checkbox"
                    name="allTags"
                    v-model="form.allTags"
                    value="true"
                    class="h-5 w-5 mb-3 rounded-full accent-quizzlab-primary checked:bg-gray-300 cursor-pointer"
                />
                <label for="allTags" id="allTags"
                    ><span
                        class="text-3xl text-white pl-2 cursor-pointer text-justify"
                        >Chaque question comporte tous les thèmes associés</span
                    ></label
                >
            </div>

            <!-- BOUTONS  -->
            <div class="flex flex-wrap justify-center mx-3 space-x-2">
                <button
                    type="submit"
                    class="py-5 px-8 rounded-sm mb-12"
                    :class="
                        completedForm
                            ? 'hover:bg-quizzlab-secondary bg-white text-quizzlab-secondary hover:text-white'
                            : 'bg-slate-400 text-white'
                    "
                    :disabled="!completedForm"
                >
                    <span class="font-semibold text-3xl">LANCER</span>
                </button>
            </div>
        </form>

        <!-- OVERLAY -->
        <div
            v-if="themeListOverlay"
            class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
        >
            <div class="w-4/5">
                <div class="flex justify-between bg-white p-3">
                    <span
                        class="text-l md:text-2xl font-semibold text-quizzlab-ternary my-auto"
                    >
                        CHERCHER UN THEME DANS LA LISTE</span
                    >
                    <span class="w-10 text-right">
                        <svg-icon
                            @click="
                                (tagSearch = null), (themeListOverlay = false)
                            "
                            class="text-quizzlab-ternary h-10 w-10 my-auto cursor-pointer"
                            :path="mdiCloseBox"
                            type="mdi"
                        ></svg-icon
                    ></span>
                </div>
                <div class="bg-quizzlab-primary">
                    <div class="p-3 flex justify-center">
                        <input
                            name="tagSearch"
                            class="border-2 focus:border-quizzlab-ternary rounded-sm w-5/6 px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                            type="text"
                            placeholder="Thème"
                            v-model="tagSearch"
                            ref="tagListRef"
                        />
                    </div>

                    <div class="w-full text-center">
                        <span class="text-white text-3xl"
                            >{{ handlingTags.length }} thème{{
                                handlingTags.length > 1 ? "s" : ""
                            }}
                            à ajouter</span
                        >
                    </div>
                    <div
                        class="w-full inset-0 p-3 flex flex-wrap justify-around"
                    >
                        <span
                            v-for="filteredTag in filteredTags"
                            :key="filteredTag.id"
                            class="text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                            :class="
                                handlingTags.includes(filteredTag.name)
                                    ? 'bg-quizzlab-quaternary'
                                    : 'bg-quizzlab-secondary hover:bg-quizzlab-quaternary'
                            "
                            @click="
                                handlingTags.includes(filteredTag.name)
                                    ? handlingTags.splice(
                                          handlingTags.indexOf(
                                              filteredTag.name
                                          ),
                                          1
                                      )
                                    : handlingTags.push(filteredTag.name)
                            "
                            >{{ filteredTag.name }}</span
                        >
                    </div>
                    <div
                        class="flex flex-wrap justify-between p-3 space-x-3 space-y-2 bg-white"
                    >
                        <button
                            type="submit"
                            class="bg-white hover:bg-quizzlab-ternary text-quizzlab-ternary hover:text-white text-2xl py-2 px-3 rounded-sm"
                            @click="
                                (themeListOverlay = false), (handlingTags = [])
                            "
                        >
                            <span class="font-semibold"> Annuler</span>
                        </button>
                        <button
                            :disabled="handlingTags.length == 0"
                            type="submit"
                            class="hover:bg-quizzlab-secondary hover:text-white bg-white text-quizzlab-secondary text-2xl py-2 px-3 rounded-sm"
                            @click="addHandlingTags()"
                        >
                            <span class="font-semibold"
                                >Ajouter aux thèmes</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, toRef, reactive, computed, onBeforeMount } from "vue";
import router from "@/router";
// Import des composants
import SuggestedTags from "../components/SuggestedTags.vue";
// Import des stores
import { useUserStore } from "@/stores/user";
// Imports de composables
import { useGames } from "@/composables/games.js";

import { useTags } from "@/composables/tags.js";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiMagnify, mdiContentCopy, mdiCloseBox } from "@mdi/js";
// Déclaration des stores
const userStore = useUserStore();
// Déclaration des ref pour le focus
const themeInput = ref(null);
const imageInput = ref(null);
const tagListRef = ref(null);
const imgSrc = ref("http://127.0.0.1:5173/public/storage/img/questions/0.avif");

// Déclaration des composables
const {
    getSuggestedTags,
    resetSuggestedTags,
    computedSuggestedTag,
    getAllTags,
    tagSearch,
    filteredTags,
    tags,
} = useTags();
const { sendGameProposition } = useGames();

// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

const theme = ref(null);
const themeListOverlay = ref(false);
const handlingTags = ref([]);

const form = reactive({
    maxPlayers: 1,
    questionCount: 10,
    responseTime: 15,
    allTags: false,
    selectedThemes: [],
});

// Activation du bouton de validation de formulaire
const completedForm = computed(() => {
    return (
        form.maxPlayers &&
        form.maxPlayers > 0 &&
        form.questionCount &&
        form.questionCount > 0 &&
        form.responseTime &&
        form.responseTime > 0
    );
});

const sendGame = async () => {
    await sendGameProposition({ ...form });
};

// Retirer le thème sur lequel on clique
const removeSelectedTheme = (selectedThemeKey) => {
    form.selectedThemes.splice(selectedThemeKey, 1);
};

// Ajouter les thèmes de la liste sélectionnés
const addHandlingTags = () => {
    // Parcours des thèmes en attente d'ajout
    handlingTags.value.forEach((handlingTag) => {
        // Si le Thème n'est ps encore présent
        if (!form.selectedThemes.includes(handlingTag)) {
            // Ajout dans le tableau
            form.selectedThemes.push(handlingTag);
        }
    });
    // Réinitialisation du tableau des thèmes en attente d'ajout
    handlingTags.value = [];
    // Fermeture de l'overlay
    themeListOverlay.value = false;
};

const getSuggestions = async () => {
    // Si recherche de thème
    if (theme) {
        await getSuggestedTags(theme);
    }
};

const addTag = async (newQuestion) => {
    // Ajout du thème dans les thèmes associés
    form.selectedThemes.push(newQuestion);
    // Réinitialisation des suggestions de thèmes
    resetSuggestedTags();
    theme.value = null;
    // Re focus sur le champ de thème
    themeInput.value.focus();
};

onBeforeMount(() => {
    // Reset des suggestion pour éviter la pollution d'anciennes pages
    resetSuggestedTags();
    // Récupération de tous les tags
    getAllTags();
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected == false) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    }
});
</script>
