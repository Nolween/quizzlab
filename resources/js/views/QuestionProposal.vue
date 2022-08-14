<template>
    <div
        class="pt-40 sm:pt-36 md:pt-28 justify-center bg-quizzlab-primary px-2 flex flex-wrap w-full"
    >
        <form class="space-y-6" @submit.prevent="sendProposition">
            <div class="text-center text-5xl text-white font-semibold">
                Proposer une question
            </div>
            <!-- CHAMPS -->
            <input
                name="question"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Question"
                @keyup="getSuggestions(1)"
                v-model="form.question"
                v-focus
            />
            <div
                v-if="computedSuggestedQuestion.length > 0"
                class="w-full z-50"
            >
                <!-- Composant de proposition de question -->
                <SuggestedQuestions
                    :suggestedQuestions="computedSuggestedQuestion"
                />
            </div>
            <input
                name="answer"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Réponse"
                v-model="form.answer"
            />
            <div class="flex justify-between">
                <input
                    name="theme"
                    class="border-2 focus:border-quizzlab-ternary rounded-sm w-5/6 px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                    type="text"
                    placeholder="Thème"
                    v-model="theme"
                    ref="themeInput"
                    @keyup="getSuggestions(0)"
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
                Thèmes Associés
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

            <!-- BOUTONS  -->
            <div class="flex flex-wrap justify-center mx-3 space-x-2">
                <button
                    type="submit"
                    class="hover:bg-quizzlab-quinary bg-quizzlab-secondary text-white text-2xl py-2 px-3 rounded-sm"
                >
                    Proposer
                </button>
            </div>
        </form>

        <!-- OVERLAY -->
        <div
            v-if="themeListOverlay"
            class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
        >
            <div class="w-4/5">
                <div class="flex justify-between bg-quizzlab-quinary p-3">
                    <span class="text-l md:text-2xl font-semibold text-white">
                        CHERCHER UN THEME DANS LA LISTE</span
                    >
                    <span class="w-5 text-right">
                        <svg-icon
                            @click="
                                (tagSearch = null), (themeListOverlay = false)
                            "
                            class="text-white my-auto cursor-pointer"
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
                            class="hover:bg-white bg-quizzlab-ternary hover:text-quizzlab-ternary text-white text-2xl py-2 px-3 rounded-sm"
                            @click="
                                (themeListOverlay = false), (handlingTags = [])
                            "
                        >
                            Annuler
                        </button>
                        <button
                            :disabled="handlingTags.length == 0"
                            type="submit"
                            class="bg-quizzlab-secondary text-white hover:bg-white hover:text-quizzlab-secondary text-2xl py-2 px-3 rounded-sm"
                            @click="addHandlingTags()"
                        >
                            Ajouter aux thèmes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, onBeforeMount } from "vue";
import router from "@/router";
// Import des composants
import SuggestedQuestions from "../components/SuggestedQuestions.vue";
import SuggestedTags from "../components/SuggestedTags.vue";
// Import des stores
import { useUserStore } from "@/stores/user";
// Imports de composables
import { useQuestions } from "@/composables/questions.js";

import { useTags } from "@/composables/tags.js";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiMagnify } from "@mdi/js";
import { mdiCloseBox } from "@mdi/js";
// Déclaration des stores
const userStore = useUserStore();
// Déclaration des ref pour le focus
const themeInput = ref(null);
const tagListRef = ref(null);

// Déclaration des composables
const {
    getSuggestedQuestions,
    resetSuggestedQuestions,
    computedSuggestedQuestion,
} = useQuestions();
const {
    getSuggestedTags,
    resetSuggestedTags,
    computedSuggestedTag,
    getAllTags,
    tagSearch,
    filteredTags,
    tags,
} = useTags();

// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

const theme = ref(null);
const themeListOverlay = ref(false);
const handlingTags = ref([]);

const form = reactive({
    question: null,
    answer: null,
    selectedThemes: [],
});

// Variable de recherche de question
const searchMod = ref(0);

const sendProposition = async () => {
    await userStore.doLogin({ ...form });
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

const getSuggestions = async (mod) => {
    // Si recherche de thème
    if (mod == 0) {
        await getSuggestedTags(theme);
    }
    // Si on recherche une question
    else if (mod == 1) {
        await getSuggestedQuestions(form.question);
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
    resetSuggestedQuestions();
    resetSuggestedTags();
    // Récupération de tous les tags
    getAllTags();
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    }
});
</script>
