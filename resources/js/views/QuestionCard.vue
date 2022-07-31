<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary">
        <div class="w-5/6 lg:w-4/5 mb-5">
            <Question
                :questionId="questionStore.question.id"
                :answer="questionStore.question.answer"
                :question="questionStore.question.question"
                :avatar="questionStore.question.avatar"
                :vote="questionStore.question.vote"
                :userName="questionStore.question.userName"
                :ago="questionStore.question.ago"
                :tags="questionStore.question.tags"
                :commentsCount="questionStore.question.commentsCount"
                :hasVoted="questionStore.question.hasVoted"
            />
        </div>

        <div class="w-5/6 lg:w-4/5">
            <div
                class="bg-white mb-3"
                v-for="comment in questionStore.question.comments"
                :key="comment.id"
            >
                <!-- Nom + Temps -->
                <div
                    class="flex flex-wrap justify-center sm:justify-between px-4 lg:px-12 mb-4 pt-4"
                >
                    <div class="flex flex-wrap cursor-pointer">
                        <img
                            :src="
                                'http://127.0.0.1:5173/public/img/profile/' +
                                comment.avatar
                            "
                            class="w-10 h-10 object-cover rounded-md mr-3"
                            alt=""
                        />
                        
                        <span
                            class="text-quizzlab-quinary text-xl font-medium pt-1"
                            >{{ comment.userName }}</span
                        >
                    </div>
                    <div class="flex flex-wrap pt-1">
                        <svg-icon
                            :path="mdiTimerOutline"
                            class="text-quizzlab-ternary w-7 h-7 mr-1"
                            type="mdi"
                        ></svg-icon>
                        <span
                            class="text-quizzlab-ternary text-xl font-medium"
                            >{{ comment.ago }}</span
                        >
                    </div>
                </div>
                <!-- Commentaire -->
                <div
                    class="text-2xl text-quizzlab-primary font-medium px-4 lg:px-12 mb-3"
                >
                    {{ comment.comment }}
                </div>
                <!-- Actions -->
                <div class="flex flex-wrap justify-end pr-3 space-x-3 pb-3">
                    <span class="text-xl font-medium text-quizzlab-secondary">{{
                        comment.approvals_count
                    }}</span>
                    <span
                        :title="
                            comment.hasReacted === 1
                                ? `Annuler l'approbation du commentaire`
                                : 'Approuver ce commentaire'
                        "
                    >
                        <svg-icon
                            :path="
                                comment.hasReacted == 1
                                    ? mdiThumbUp
                                    : mdiThumbUpOutline
                            "
                            class="text-quizzlab-secondary w-7 h-7"
                            :class="
                                comment.ownComment == 0 ? 'cursor-pointer' : ''
                            "
                            type="mdi"
                            @click="
                                comment.ownComment == 0
                                    ? prepareReact(comment.id, comment.hasReacted == 1 ? null : true)
                                    : ''
                            "
                        ></svg-icon>
                    </span>
                    <span class="text-xl font-medium text-quizzlab-ternary">
                        {{ comment.disapprovals_count }}</span
                    >
                    <span
                        :title="
                            comment.hasReacted == 0
                                ? 'Annuler la désapprobation du commetaire'
                                : 'Désapprouver ce commentaire'
                        "
                    >
                        <svg-icon
                            :path="
                                comment.hasReacted == 0
                                    ? mdiThumbDown
                                    : mdiThumbDownOutline
                            "
                            class="text-quizzlab-ternary w-7 h-7"
                            :class="
                                comment.ownComment == 0 ? 'cursor-pointer' : ''
                            "
                            type="mdi"
                            @click="
                                comment.ownComment == 0
                                    ? prepareReact(comment.id,  comment.hasReacted == 0 ? null : false)
                                    : ''
                            "
                        ></svg-icon>
                    </span>
                    <span title="Répondre à ce commentaire">
                        <svg-icon
                            :path="mdiCommentArrowRight"
                            class="text-quizzlab-quaternary w-7 h-7 cursor-pointer"
                            type="mdi"
                        ></svg-icon>
                    </span>
                </div>
                <!-- Rédaction commentaire utilisateur -->
                <div></div>
            </div>
        </div>
    </div>
</template>
<script setup>
// Imports de fonctionnalités essentielles de Vue (hook, ...)
import { onMounted, onBeforeMount, onUnmounted } from "vue";
import router from "@/router";
import { useRoute } from "vue-router";
// Import des stores
import { useQuestionStore } from "@/stores/question";
import { useCommentStore } from "@/stores/comment";
import { useUserStore } from "@/stores/user";
// Import des composants
import Question from "../components/Question.vue";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiAccount,
    mdiMinusThick,
    mdiPlusThick,
    mdiTimerOutline,
    mdiThumbUp,
    mdiThumbUpOutline,
    mdiThumbDown,
    mdiThumbDownOutline,
    mdiCommentArrowRight,
    mdiCommentText,
} from "@mdi/js";
// Déclaration du store des questions
const questionStore = useQuestionStore();
const commentStore = useCommentStore();
//? Vérification si l'utilisateur est connecté
const userStore = useUserStore();
const route = useRoute();
onBeforeMount(() => {
    userStore.checkAuth();
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(() => {
    questionStore.getQuestion(route.params.id);
});
onUnmounted(() => {
    questionStore.resetQuestion();
});

//? Reaction sur un commentaire
function prepareReact(commentid, ispositive) {
    const data = { commentid, ispositive };
    commentStore.reactQuestionComment(data);
}
// const test = ref($route.params.id)
</script>
