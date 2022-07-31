<template>
    <div class="mb-4 bg-white">
        <div class="flex flex-wrap justify-start px-3 lg:px-8">
            <div
                class="w-40 border-gray-300 border-2 px-3 m-2 pt-2 flex flex-wrap justify-between"
            >
                <svg-icon
                    @click="props.hasVoted == null ? prepareVote(false) : ''"
                    :path="mdiMinusThick"
                    :class="negativeClass"
                    type="mdi"
                ></svg-icon>
                <div class="text-quizzlab-primary font-bold text-xl">
                    {{ vote }}°
                </div>
                <svg-icon
                    @click="props.hasVoted == null ? prepareVote(true) : ''"
                    :path="mdiPlusThick"
                    :class="positiveClass"
                    type="mdi"
                ></svg-icon>
            </div>
            <!-- Thèmes -->
            <span
                v-for="tag in tags"
                :key="tag.id"
                class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                >{{ tag.name }}</span
            >
        </div>
        <!-- Question -->
        <div
            class="text-quizzlab-primary font-medium text-3xl px-3 lg:px-8 py-2 cursor-pointer"
             @click="$router.push({name: 'question.show', params: {id: props.questionId}})"
        >
            {{ question }}
        </div>
        <!-- Réponse -->
        <div
            class="bg-quizzlab-secondary text-white text-3xl font-semibold text-right px-3 lg:px-8 py-3"
        >
            {{ answer }}
        </div>
        <!-- Infos -->
        <div class="flex flex-wrap justify-around py-2 px-3 lg:px-8">
            <div class="flex flex-wrap cursor-pointer">
                <img
                    :src="'http://127.0.0.1:5173/public/img/profile/' + avatar"
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
            <div class="flex flex-wrap cursor-pointer pt-1" @click="$router.push({name: 'question.show', params: {id: props.questionId}})">
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
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import router from "@/router";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiAccount,
    mdiMinusThick,
    mdiPlusThick,
    mdiTimerOutline,
    mdiCommentText,
} from "@mdi/js";
// Import du store des questions
import { useQuestionStore } from "@/stores/question";
// Déclaration du store des questions
const questionStore = useQuestionStore();

// Définition des props du composant
const props = defineProps({
    questionId: Number,
    answer: String,
    question: String,
    avatar: String,
    vote: Number,
    commentsCount: Number,
    userName: String,
    ago: String,
    tags: Array,
    hasVoted: {
        type: Number,
        required: false,
    },
});

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

</script>
