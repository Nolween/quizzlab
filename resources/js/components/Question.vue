<template>
    <div class="mb-4 bg-white">
        <div class="flex">
            <div class="w-full">
                <div class="flex flex-wrap px-3 lg:px-8 justify-start">
                    <div
                        v-if="props.isIntegrated === false"
                        class="w-40 border-gray-300 border-2 px-3 m-2 pt-2 flex flex-wrap justify-between"
                    >
                        <template v-if="!props.hasToBeModerated">
                            <svg-icon
                                @click="
                                props.hasVoted == null ? prepareVote(false) : ''
                            "
                                :path="mdiMinusThick"
                                :class="negativeClass"
                                type="mdi"
                            ></svg-icon>
                            <div class="text-quizzlab-primary font-bold text-xl">
                                {{ vote }}°
                            </div>
                            <svg-icon
                                @click="
                                props.hasVoted == null ? prepareVote(true) : ''
                            "
                                :path="mdiPlusThick"
                                :class="positiveClass"
                                type="mdi"
                            ></svg-icon>
                        </template>
                        <template v-else>
                            <svg-icon
                                @click="prepareModerate(true)"
                                :path="mdiCheckBold"
                                class="bg-quizzlab-white hover:bg-quizzlab-secondary text-quizzlab-secondary hover:text-white p-1 h-8 w-8 cursor-pointer"
                                type="mdi"
                            ></svg-icon>
                            <svg-icon
                                @click="prepareModerate(false)"
                                :path="mdiCancel"
                                class="bg-quizzlab-white hover:bg-quizzlab-ternary text-quizzlab-ternary hover:text-white p-1 h-8 w-8 cursor-pointer"
                                type="mdi"
                            ></svg-icon>
                        </template>
                    </div>
                    <div
                        v-else
                        class="w-40 border-gray-300 border-2 px-3 m-2 pt-2 text-center"
                    >
                        <span class="font-bold text-quizzlab-secondary"
                        >DANS LE QUIZZ</span
                        >
                    </div>
                    <!-- Thèmes -->
                    <span
                        v-for="tag in tags"
                        :key="tag.id"
                        class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                        @click="goToTheme(tag.name)"
                    >{{ tag.name }}</span
                    >
                </div>
            </div>
        </div>

        <!-- Question -->
        <div class="flex justify-between px-3">
            <div
                title="Voir la fiche"
                class="text-quizzlab-primary font-medium text-4xl px-3 lg:px-8 py-2 text-center my-auto mx-auto"
                :class="props.isIntegrated === false ? 'cursor-pointer' : ''"
                @click="goToQuestion(props.questionId)"
            >
                {{ question }}
            </div>

            <!-- Image -->
            <div class="min-w-max mb-1" v-if="props.imagePath">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/questions/small/' +
                        props.imagePath
                    "
                    class="mt-2 w-20 h-20 object-cover rounded-md mr-3 cursor-pointer"
                    :alt="props.imagePath"
                    title="Agrandir l'image"
                    @click="displayBigImageQuestion(true)"
                />
            </div>
        </div>
        <!-- Réponse -->
        <div class="flex flex-wrap justify-center space-x-2 px-3">
            <div
                v-for="(choice, choiceKey) in choices"
                :key="choiceKey"
                :class="
                    choice.is_correct === 1
                        ? 'bg-quizzlab-secondary'
                        : 'bg-quizzlab-ternary'
                "
                class="text-white text-xl font-semibold text-right px-3 lg:px-8 py-3 mb-2"
            >
                {{ choice.title }}
            </div>
        </div>
        <!-- Infos -->
        <div class="flex flex-wrap justify-around py-2 px-3 lg:px-8">
            <div class="flex flex-wrap cursor-pointer">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' +
                        avatar
                    "
                    class="w-10 h-10 object-cover rounded-md mr-3"
                    alt=""
                />
                <span class="text-quizzlab-quinary text-xl font-medium pt-1">{{
                        userName
                    }}</span>
            </div>
            <div class="flex flex-wrap pt-1">
                <svg-icon
                    :path="mdiTimerOutline"
                    class="text-quizzlab-ternary w-7 h-7 mr-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-ternary text-xl font-medium">{{
                        ago
                    }}</span>
            </div>
            <div
                :class="props.isIntegrated === false ? 'cursor-pointer' : ''"
                class="flex flex-wrap pt-1"
                @click="goToQuestion(props.questionId)"
            >
                <svg-icon
                    :path="mdiCommentText"
                    class="text-quizzlab-quaternary w-7 h-7 mr-2"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-quaternary text-xl font-medium">{{
                        commentsCount
                    }}</span>
            </div>
        </div>
    </div>

    <!-- OVERLAY -->
    <div
        v-if="questionImageOverlay"
        class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
    >
        <div class="md:w-4/5 w-full">
            <div class="flex justify-between bg-white p-3">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/questions/big/' +
                        props.imagePath
                    "
                    class="mt-2 object-cover rounded-md mr-3 cursor-pointer"
                    :alt="props.imagePath"
                    title="Ferme l'image"
                    @click="displayBigImageQuestion(false)"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import {ref, computed} from "vue";
import router from "@/router";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiMinusThick,
    mdiPlusThick,
    mdiTimerOutline,
    mdiCheckBold,
    mdiCancel,
    mdiCommentText,
} from "@mdi/js";
// Import du store des questions
import {useQuestionStore} from "@/stores/question";
// Déclaration du store des questions
const questionStore = useQuestionStore();

const emit = defineEmits(['changeSearch', 'goToQuestion']);
// Définition des props du composant
const props = defineProps({
    questionId: Number,
    choices: Array,
    question: String,
    avatar: String,
    vote: Number,
    commentsCount: Number,
    userName: String,
    ago: String,
    imagePath: String,
    tags: Array,
    isIntegrated: Boolean,
    hasToBeModerated: {type: Boolean, default: false},
    hasVoted: {
        type: Number,
        required: false,
    },
});

const questionImageOverlay = ref(false);
//? Computed
// Vote de la question
const statusVote = computed(() => {
    return props.hasVoted;
});

// Couleur des pastilles positives
const positiveClass = computed(() => ({
    "cursor-pointer": props.hasVoted == null,
    "text-quizzlab-secondary ": props.hasVoted == null || props.hasVoted === 0,
    "hover:bg-quizzlab-secondary hover:text-white": props.hasVoted == null,
    "bg-quizzlab-secondary text-white": props.hasVoted === 1,
}));

// Couleur des pastilles négatives
const negativeClass = computed(() => ({
    "cursor-pointer": props.hasVoted == null,
    "text-quizzlab-ternary": props.hasVoted == null || props.hasVoted === 1,
    "hover:bg-quizzlab-ternary hover:text-white": props.hasVoted == null,
    "bg-quizzlab-ternary text-white": props.hasVoted === 0,
}));

//? Vote de la question
function prepareVote(ispositive) {
    const data = {questionid: props.questionId, ispositive};
    questionStore.voteQuestion(data);
}

//? Modération de la question
function prepareModerate(isModerated) {
    const data = {questionid: props.questionId, isModerated};
    questionStore.moderateQuestion(data);
}

//? Fonctions du composant
function displayBigImageQuestion(value) {
    questionImageOverlay.value = value;
}

// Lorsque l'on clique sur un thème
const goToTheme = (theme) => {
    // Si on est sur la page d'accueil, on active le rafraichissement des questions
    if (router.currentRoute.value.name === "questions.index" || router.currentRoute.value.name === "admin.questions") {
        emit("changeSearch", theme);
    }
    // Si pas la page d'accueil, on redirige avec le paramètre des thèmes
    else {
        // On active l'évènement changeSearch (change-search) présent dans le composant parent
        router.push({
            name: props.hasToBeModerated ? "admin.questions" : "questions.index",
            params: {theme},
        });
    }
};

// Lorsque l'on clique sur la question
const goToQuestion = (questionId) => {
    emit('goToQuestion', questionId)
};
</script>
