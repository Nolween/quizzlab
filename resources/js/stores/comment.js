import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";
import { useQuestionStore } from "@/stores/question";

export const useCommentStore = defineStore("comment", {
    state: () => ({ comments: [], comment: [] }),
    actions: {
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
        resetComment() {
            this.comment = [];
        },
    },
});
