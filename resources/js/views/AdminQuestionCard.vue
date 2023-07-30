<template>
    <div
        class="pt-40 sm:pt-36 md:pt-28 justify-center bg-quizzlab-ternary px-2 flex flex-wrap w-full md:px-10"
    >


        <Question
            :questionId="question.id"
            :choices="question.choices"
            :question="question.question"
            :avatar="question.avatar"
            :vote="question.vote"
            :userName="question.userName"
            :ago="question.ago"
            :tags="question.tags"
            :isIntegrated="question.isIntegrated"
            :commentsCount="question.commentsCount"
            :hasVoted="question.hasVoted"
            :imagePath="question.image"
            :has-to-be-moderated="true"
            :key="question.id"
        />

        <form class="space-y-6" @submit.prevent="prepareModerateWithEdition">
            <div class="text-center text-5xl text-white font-semibold">
                Modérer la question
            </div>
            <!-- CHAMPS -->
            <input
                name="question"
                class="border-2  text-2xl focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
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
                    stitle="Questions relatives"
                />
            </div>
            <!-- Checkbox besoin d'une image -->
            <div class="mt-1">
                <input
                    id="imageNeeded"
                    type="checkbox"
                    name="imageNeeded"
                    v-model="form.imageNeeded"
                    value="true"
                    class="h-5 w-5 mb-3 rounded-full accent-quizzlab-primary checked:bg-gray-300 cursor-pointer"
                />
                <label for="imageNeeded" id="imageNeeded"
                ><span
                    class="text-3xl text-white pl-2 cursor-pointer text-justify"
                >Cette question a besoin de s’appuyer d’une image</span
                ></label
                >
            </div>
            <!-- Upload d'image -->
            <div class="mx-auto lg:w-1/2" v-if="form.imageNeeded === true">
                <label for="image-input">
                    <img
                        :src="imgSrc"
                        alt="Image de la question"
                        class="cursor-pointer"
                    />
                </label>
                <input
                    id="image-input"
                    type="file"
                    accept=".jpg, .png, .jpeg, .avif, .webp"
                    name="imageInput"
                    @change="onFileChanged($event)"
                    class="hidden"
                    ref="imageInput"
                />
            </div>
            <!-- Réponses -->
            <input
                name="choice1"
                class="border-2 focus:border-quizzlab-ternary text-2xl text-quizzlab-secondary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Réponse 1 (Correcte)"
                v-model="form.choices[0]"
            />
            <input
                name="choice2"
                class="border-2 focus:border-quizzlab-ternary text-2xl text-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Réponse 2 (Fausse)"
                v-model="form.choices[1]"
            />
            <input
                name="choice3"
                class="border-2 focus:border-quizzlab-ternary text-2xl text-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Réponse 3 (Fausse)"
                v-model="form.choices[2]"
            />
            <input
                name="choice4"
                class="border-2 focus:border-quizzlab-ternary text-2xl text-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="text"
                placeholder="Réponse 4 (Fausse)"
                v-model="form.choices[3]"
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
                    @click="prepareModerate(false)"
                    class="py-2 px-3 rounded-sm mb-12"
                    :class="
                        completedForm
                            ? 'hover:bg-quizzlab-quinary bg-white text-quizzlab-quinary hover:text-white'
                            : 'bg-slate-400 text-white'
                    "
                >
                    <span class="font-semibold text-3xl">REFUSER</span>
                </button>
                <button
                    type="submit"
                    class="py-2 px-3 rounded-sm mb-12"
                    :class="
                        completedForm
                            ? 'hover:bg-quizzlab-secondary bg-white text-quizzlab-secondary hover:text-white'
                            : 'bg-slate-400 text-white'
                    "
                    :disabled="!completedForm"
                >
                    <span class="font-semibold text-3xl">ACCEPTER</span>
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
                                [tagSearch = null, themeListOverlay = false]
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
                                [themeListOverlay = false, handlingTags = []]
                            "
                        >
                            <span class="font-semibold"> Annuler</span>
                        </button>
                        <button
                            :disabled="handlingTags.length === 0"
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
import {ref, toRef, reactive, computed, onBeforeMount} from "vue";
import router from "@/router";
// Import des composants
import SuggestedQuestions from "../components/SuggestedQuestions.vue";
import SuggestedTags from "../components/SuggestedTags.vue";
// Import des stores
import {useUserStore} from "@/stores/user";
import {useQuestionStore} from "@/stores/question";
// Imports de composables
import {useQuestions} from "@/composables/questions";

import {useTags} from "@/composables/tags";

// Import de helper
import {convertImgSrcToFile} from "../helpers/image";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {mdiMagnify} from "@mdi/js";
import {mdiCloseBox} from "@mdi/js";
import Question from "@/components/Question.vue";
// Déclaration des stores
const userStore = useUserStore();
const questionStore = useQuestionStore();
// Déclaration des ref pour le focus
const themeInput = ref(null);
const imageInput = ref(null);
const tagListRef = ref(null);
const imgSrc = ref("http://127.0.0.1:5173/public/storage/img/questions/0.avif");

// Déclaration des composables
const {
    getSuggestedQuestions,
    resetSuggestedQuestions,
    computedSuggestedQuestion,
    sendQuestionProposition,
    sendModerationQuestionWithEdition,
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
    id: null,
    question: null,
    choices: [null, null, null, null],
    imageNeeded: false,
    image: null,
    selectedThemes: [],
});

const question = reactive({
    id: null,
    choices: null,
    question: null,
    avatar: null,
    vote: null,
    userName: null,
    ago: null,
    tags: null,
    isIntegrated: null,
    commentsCount: null,
    hasVoted: null,
    image: null,
})

// Activation du bouton de validation de formulaire
const completedForm = computed(() => {
    return (
        form.question &&
        form.question.length > 0 &&
        form.choices[0] &&
        form.choices[0].length > 0 &&
        form.choices[1] &&
        form.choices[1].length > 0 &&
        form.choices[2] &&
        form.choices[2].length > 0 &&
        form.choices[3] &&
        form.choices[3].length > 0 &&
        form.selectedThemes.length > 0 &&
        (!form.imageNeeded || (form.imageNeeded && form.image))
    );
});

// Changement d'image
const onFileChanged = (event) => {
    if (event.target.files[0]) {
        // Attribution de l'url à la source de l'image
        imgSrc.value = URL.createObjectURL(event.target.files[0]);
        form.image = event.target.files[0];
    } else {
        form.image = null;
    }
};

const sendQuestion = async () => {
    await sendQuestionProposition({...form});
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
    if (theme && mod === 0) {
        await getSuggestedTags(theme);
    }
    // Si on recherche une question
    else if (form.question && mod === 1) {
        await getSuggestedQuestions(toRef(form, "question"));
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


//? Modération de la question
function prepareModerate(isModerated) {
    const data = {questionid: questionStore.question.id, isModerated};
    questionStore.moderateQuestion(data).then((response) => {
        router.push({name: "admin.questions"});
    });
}

//? Modération de la question avec des modifications
const prepareModerateWithEdition = async () => {
    await sendModerationQuestionWithEdition({...form});
}


onBeforeMount(async () => {
    userStore.checkAuth();
    userStore.checkAdminStatus();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected === false) {
        // Redirection vers l'écran de connexion
        router.push({name: "connexion.create"});
    }
    // Si l'utilisateur n'est pas admin
    if (!userStore.getIsAdmin || userStore.getIsAdmin === false) {
        // Redirection vers l'écran de connexion
        router.push({name: "questions.index"});
        window.location.reload();
    }
    // Récupération des infos de la question
    questionStore.getQuestion(router.currentRoute.value.params.id).then(async (response) => {
        form.choices = [questionStore.question.choices[0].title, questionStore.question.choices[1].title, questionStore.question.choices[2].title, questionStore.question.choices[3].title];
        form.question = questionStore.question.question;
        form.id = questionStore.question.id;
        form.imageNeeded = questionStore.question.image !== null;
        imgSrc.value = questionStore.question.image !== null ? "http://127.0.0.1:5173/public/storage/img/questions/big/" + questionStore.question.image : "http://127.0.0.1:5173/public/storage/img/questions/0.avif";
        let themes = [];
        questionStore.question.tags.forEach((tag) => {
            themes.push(tag.name);
        });
        form.selectedThemes = themes;
        form.image = await convertImgSrcToFile(imgSrc.value)

        // Hydrate question from store
        question.id = questionStore.question.id
        question.choices = questionStore.question.choices
        question.question = questionStore.question.question
        question.avatar = questionStore.question.avatar
        question.vote = questionStore.question.vote
        question.userName = questionStore.question.userName
        question.ago = questionStore.question.ago
        question.tags = questionStore.question.tags
        question.isIntegrated = questionStore.question.isIntegrated
        question.commentsCount = questionStore.question.commentsCount
        question.hasVoted = questionStore.question.hasVoted


        question.image = questionStore.question.image

    });

    // Reset des suggestion pour éviter la pollution d'anciennes pages
    resetSuggestedQuestions();
    resetSuggestedTags();
    // Récupération de tous les tags
    getAllTags();
});
</script>
