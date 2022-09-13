<template>
    <div
        class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary"
        v-if="questionStore.question && questionStore.question.length === 0"
    >
        <span v-if="loaded == true" class="text-center text-4xl text-white font-semibold mb-2">
            QUESTION INTEGREE AU QUIZZ</span
        >

    </div>
    <div v-else class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary">
        <!-- QUESTION -->
        <div class="w-5/6 lg:w-4/5 mb-5">
            <div class="text-center text-4xl text-white font-semibold mb-2">
                QUESTION
            </div>
            <Question
                :questionId="questionStore.question.id"
                :choices="questionStore.question.choices"
                :question="questionStore.question.question"
                :avatar="questionStore.question.avatar"
                :imagePath="questionStore.question.image"
                :vote="questionStore.question.vote"
                :userName="questionStore.question.userName"
                :ago="questionStore.question.ago"
                :tags="questionStore.question.tags"
                :commentsCount="questionStore.question.commentsCount"
                :hasVoted="questionStore.question.hasVoted"
                :isIntegrated="questionStore.question.isIntegrated"
                :responseDisplay="false"
            />
        </div>

        <!-- COMMENTAIRES -->
        <div class="w-5/6 lg:w-4/5 mb-5 flex flex-wrap justify-end">
            <div
                v-if="
                    questionStore.question.comments &&
                    questionStore.question.comments.length > 0
                "
                class="w-full text-center text-4xl text-white font-semibold mb-2"
            >
                COMMENTAIRES
            </div>
            <div
                class="mb-3 w-full flex flex-wrap justify-end"
                v-for="comment in questionStore.question.comments"
                :key="comment.id"
            >
                <QuestionComment
                    :id="comment.id"
                    :questionId="comment.questionId"
                    :comment="comment.comment"
                    :avatar="comment.avatar"
                    :userName="comment.userName"
                    :ago="comment.ago"
                    :ownComment="comment.ownComment"
                    :approvalsCount="comment.approvals_count"
                    :disapprovalsCount="comment.disapprovals_count"
                    :hasReacted="comment.hasReacted"
                    :responses="comment.responses"
                    :responseDisplay="false"
                    :modified="comment.modified"
                />
            </div>
        </div>
        <!-- AJOUT / REPONSE DE COMMENTAIRE -->
        <div
            id="commentPart"
            v-if="userStore.isConnected == true"
            class="h-40 w-5/6 lg:w-4/5"
            :class="commentStore.responseMod > 0 ? 'mb-80' : 'mb-40'"
        >
            <div class="text-center text-4xl text-white font-semibold mb-2">
                {{
                    commentStore.responseMod > 0
                        ? commentStore.editMod == true
                            ? "MODIFIER LE COMMENTAIRE"
                            : "REPONDRE AU COMMENTAIRE"
                        : "AJOUTER UN COMMENTAIRE"
                }}
            </div>
            <QuestionComment
                v-if="
                    commentStore.responseMod == 1 &&
                    commentStore.commentReplyId !== null
                "
                :id="commentStore.commentReplyContent.id"
                :questionId="commentStore.commentReplyContent.questionId"
                :comment="commentStore.commentReplyContent.comment"
                :avatar="commentStore.commentReplyContent.avatar"
                :userName="commentStore.commentReplyContent.userName"
                :ago="commentStore.commentReplyContent.ago"
                :ownComment="commentStore.commentReplyContent.ownComment"
                :approvalsCount="
                    commentStore.commentReplyContent.approvals_count
                "
                :disapprovalsCount="
                    commentStore.commentReplyContent.disapprovals_count
                "
                :hasReacted="commentStore.commentReplyContent.hasReacted"
                :responseDisplay="true"
                :modified="commentStore.commentReplyContent.modified"
            />
            <QuestionCommentResponse
                v-if="
                    commentStore.responseMod == 2 &&
                    commentStore.commentReplyId !== null
                "
                :id="commentStore.commentReplyContent.id"
                :questionId="commentStore.commentReplyContent.questionId"
                :comment="commentStore.commentReplyContent.comment"
                :avatar="commentStore.commentReplyContent.avatar"
                :userName="commentStore.commentReplyContent.userName"
                :ago="commentStore.commentReplyContent.ago"
                :ownComment="commentStore.commentReplyContent.ownComment"
                :approvalsCount="
                    commentStore.commentReplyContent.approvals_count
                "
                :disapprovalsCount="
                    commentStore.commentReplyContent.disapprovals_count
                "
                :hasReacted="commentStore.commentReplyContent.hasReacted"
                :responseDisplay="true"
                :modified="commentStore.commentReplyContent.modified"
            />
            <div class="mt-5">
                <QuestionCommentEditor />
            </div>
        </div>
    </div>
</template>
<script setup>
// Imports de fonctionnalités essentielles de Vue (hook, ...)
import { onMounted, onBeforeMount, onUnmounted, ref } from "vue";
import router from "@/router";
import { useRoute } from "vue-router";

// Import de composables
// import useQuestions from "@/composables/questions.js";

// Import des stores
import { useQuestionStore } from "@/stores/question";
import { useUserStore } from "@/stores/user";
import { useCommentStore } from "@/stores/comment";
// Import des composants
import Question from "../components/Question.vue";
import QuestionCommentEditor from "../components/QuestionCommentEditor.vue";
import QuestionComment from "../components/QuestionComment.vue";
import QuestionCommentResponse from "../components/QuestionCommentResponse.vue";
// Déclaration du store des questions
const questionStore = useQuestionStore();
const userStore = useUserStore();
const commentStore = useCommentStore();
//? Vérification si l'utilisateur est connecté
const route = useRoute();

// const { forbiddenQuestion, updateForbiddenQuestion } = useQuestions();

const loaded = ref(false);

//? CYCLE
// Avant le montage du composant
onBeforeMount(() => {
    userStore.checkAuth();
    questionStore.getQuestion(route.params.id);
    loaded.value = true
});
// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(() => {});
onUnmounted(() => {
    questionStore.resetQuestion();
    commentStore.cancelReplyComment();
});

</script>
