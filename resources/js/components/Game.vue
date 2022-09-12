<template>
    <div class="mb-10 bg-white">
        <div class="w-full">
            <div
                class="w-full text-3xl text-center bg-quizzlab-quinary text-white font-semibold p-1"
                v-if="hasBegun == true"
            >
                Partie en cours ({{
                    (questionStep || 0) + " / " + questionCount
                }})
            </div>
            <div class="w-full flex flex-wrap px-10 py-3">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' +
                        avatar
                    "
                    class="w-10 h-10 object-cover rounded-md"
                    alt=""
                />
                <span
                    class="text-quizzlab-primary font-medium text-3xl px-3 lg:px-8"
                >
                    Partie de {{ userName }}</span
                >
            </div>

            <div class="w-full flex flex-wrap px-3 lg:px-8 justify-start">
                <!-- Thèmes -->
                <span
                    v-if="tags.length > 0"
                    v-for="tag in tags"
                    :key="tag.id"
                    class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                    @click="goToTheme(tag.name)"
                    >{{ tag.name }}</span
                >
                <span
                    v-else
                    class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl"
                >
                    Tous les thèmes</span
                >
            </div>
        </div>
        <!-- Rejoindre -->
        <div
            class="bg-quizzlab-secondary font-semibold hover:bg-quizzlab-ternary text-white text-center p-2 text-3xl cursor-pointer"
            @click="joinGame(hasBegun, id)"
        >
            REJOINDRE
        </div>
        <!-- Infos -->
        <div class="flex flex-wrap justify-around py-2 px-3 lg:px-8">
            <div class="flex flex-wrap cursor-pointer">
                <svg-icon
                    :path="mdiMessageQuestion"
                    class="text-quizzlab-primary w-7 h-7 mr-1 pt-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-primary text-2xl font-medium pb-1"
                    >{{ questionCount }} Questions</span
                >
            </div>
            <div class="flex flex-wrap pt-1">
                <svg-icon
                    :path="mdiTimerOutline"
                    class="text-quizzlab-ternary w-7 h-7 mr-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-ternary text-xl font-medium"
                    >{{ responseTime }} secondes</span
                >
            </div>
            <div class="flex flex-wrap pt-1">
                <svg-icon
                    :path="mdiAccountGroup"
                    class="text-quizzlab-quaternary w-7 h-7 mr-2"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-quaternary text-xl font-medium"
                    >{{ waitingPlayers }} / {{ maxPlayers }}</span
                >
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import router from "@/router";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiMessageQuestion,
    mdiAccountGroup,
    mdiPlusThick,
    mdiTimerOutline,
    mdiCommentText,
} from "@mdi/js";
// Import du store des questions
import { useQuestionStore } from "@/stores/question";
// Déclaration du store des questions
const questionStore = useQuestionStore();

const emit = defineEmits(["changeSearch"]);
// Définition des props du composant
const props = defineProps({
    id: Number,
    gameRule: Number,
    questionCount: Number,
    waitingPlayers: Number,
    maxPlayers: Number,
    responseTime: Number,
    gameCode: String,
    question: String,
    avatar: String,
    commentsCount: Number,
    userName: String,
    ago: String,
    hasBegun: Boolean,
    questionStep: {
        type: Number,
        required: false,
    },
    tags: Array,
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
    "text-quizzlab-secondary ": props.hasVoted == null || props.hasVoted == 0,
    "hover:bg-quizzlab-secondary hover:text-white": props.hasVoted == null,
    "bg-quizzlab-secondary text-white": props.hasVoted == 1,
}));

// Couleur des pastilles négatives
const negativeClass = computed(() => ({
    "cursor-pointer": props.hasVoted == null,
    "text-quizzlab-ternary": props.hasVoted == null || props.hasVoted == 1,
    "hover:bg-quizzlab-ternary hover:text-white": props.hasVoted == null,
    "bg-quizzlab-ternary text-white": props.hasVoted == 0,
}));

//? Fonctions du composant
function prepareVote(ispositive) {
    const data = { questionid: props.questionId, ispositive };
    questionStore.voteQuestion(data);
}

function joinGame(hasBegun, gameId) {
    // Si la partie n'a pas encore commencée
    if (hasBegun == false) {
        router.push({
            name: "games.join",
            params: { id: gameId },
        });
    }
    // Si la partie a déjà commencé, on le dirige dans la vue des questions
    else {
        router.push({
            name: "games.question",
            params: { id: gameId },
        });
    }
}

//? Fonctions du composant
function displayBigImageQuestion(value) {
    questionImageOverlay.value = value;
}

// Lorsque l'on clique sur un thème
const goToTheme = (theme) => {
    emit("changeSearch", theme);
};
</script>
