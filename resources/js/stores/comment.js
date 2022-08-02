import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";
import { useQuestionStore } from "@/stores/question";

export const useCommentStore = defineStore("comment", {
    state: () => ({
        commentReplyId: null,
        commentReplyContent: {},
        responseMod: false,
        comments: [],
        comment: [],
    }),
    actions: {
        //? MODIFICATION DE STORE
        // Modification du commentaire
        updateComment(data) {
            this.comment = data;
        },
        resetComment() {
            this.comment = [];
        },

        // Activation du mod réponse à un commentaire
        updateResponseMod(value) {
            this.responseMod = value;
        },

        // Mise à jour du contenu indiquant le commentaire auquel on répond
        updateCommentReplyContent(comment) {
            this.commentReplyContent = comment;
        },

        // Répondre à un commentaire
        replyComment(commentId) {
            this.updateResponseMod(true);
            this.commentReplyId = commentId;
            // Quel est l'index du commentaire par rapport à son ID?
            const question = useQuestionStore();
            const commentIndex = question.question.comments
                .map(function (e) {
                    return e.id;
                })
                .indexOf(commentId);
            this.commentReplyContent = question.question.comments[commentIndex];
            // On descen en bas de l'écran
            window.scrollTo(0, document.body.scrollHeight);
            // Focus sur l'input
            
        },

        // Répondre à un commentaire
        cancelReplyComment(commentId) {
            this.updateResponseMod(false);
            this.commentReplyId = null;
        },

        //? API
        // Récupérer les comments dans le back
        async getComments() {
            try {
                let response = await axios.get("/api/comments");
                this.comments = response.data.data;
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Récupérer un commentaire dans le back
        async getComment(commentId) {
            try {
                let response = await axios.get(`/api/comments/${commentId}`);
                this.comment = response.data.data;
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Modifier la réaction à un commentaire
        async reactQuestionComment(data) {
            try {
                let response = await axios.post(`/api/approvals`, data);

                const question = useQuestionStore();
                // Quelle est l'index de la comment par rapport à son id?
                const commentIndex = question.question.comments
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(data.commentid);
                // Mise à jour dans le store partie question
                question.question.comments[commentIndex].hasReacted =
                    response.data.data.ispositive;
                question.question.comments[commentIndex].approvals_count =
                    response.data.data.approvals_count;
                question.question.comments[commentIndex].disapprovals_count =
                    response.data.data.disapprovals_count;
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        async sendComment() {
            try {
                const questionStore = useQuestionStore();
                const data = {
                    comment: this.comment,
                    questionid: questionStore.question.id,
                    commentresponseid: this.commentReplyId,
                };
                // let response = await axios.post("/api/comments", data);
                // await router.push({ name: 'question.show' })
                // Rechargement de la page pour voir si d'autres commentaires sont également apparus
                window.location.reload();
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
    },
});
