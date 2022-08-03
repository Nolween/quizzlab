import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";
import { useQuestionStore } from "@/stores/question";

export const useCommentStore = defineStore("comment", {
    state: () => ({
        commentId: null,
        commentReplyId: null,
        commentReplyContent: {},
        responseMod: 0,
        editMod: false,
        comments: [],
        comment: "",
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

        /**
         * Activation du mod réponse à un commentaire
         * @param {int} value 0 = pas de réponse, 1 = réponse commentaire, 2 = réponse de réponse de commentaire
         */
        updateResponseMod(value) {
            this.responseMod = value;
        },

        // Modification du mode d'édition
        updateEditMod(value) {
            this.editMod = value;
        },
        // Modification de l'ID du commentaire à modifier
        updateCommentId(value) {
            this.commentId = value;
        },

        // Mise à jour du contenu indiquant le commentaire auquel on répond
        updateCommentReplyContent(comment) {
            this.commentReplyContent = comment;
        },

        // Répondre à un commentaire
        replyComment(commentId, responseMod, parentId = null) {
            this.updateCommentId(null)
            this.updateEditMod(false);
            this.updateResponseMod(responseMod);
            this.commentReplyId = commentId;
            // Réinitialisation du champ
            this.updateComment("")
            const question = useQuestionStore();
            // Si on répond à un commentaire
            if (responseMod === 1) {
                // Quel est l'index du commentaire par rapport à son ID?
                const commentIndex = question.question.comments
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(commentId);
                this.commentReplyContent =
                    question.question.comments[commentIndex];
            }
            // Si on répond à un commentaire
            else if (responseMod === 2) {
                // Quel est l'index du commentaire par rapport à son ID?
                const commentIndex = question.question.comments
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(parentId);
                // Quel est l'index du commentaire par rapport à son ID?
                const responseIndex = question.question.comments[
                    commentIndex
                ].responses
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(commentId);
                this.commentReplyContent =
                    question.question.comments[commentIndex].responses[
                        responseIndex
                    ];
            }
            // Focus sur l'input
            document.getElementById("commentInput").focus();
            // Focus sur l'input
        },

        // Modifier un de ses commentaires
        editComment(commentId, responseMod, parentId = null) {
            this.updateCommentId(commentId)
            this.updateEditMod(true);
            this.updateResponseMod(responseMod);
            this.commentReplyId = commentId;
            // Réinitialisation du champ
            this.updateComment("")
            // Si on répond à un commentaire
            if (responseMod === 1) {
                const question = useQuestionStore();
                // Quel est l'index du commentaire par rapport à son ID?
                const commentIndex = question.question.comments
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(commentId);
                this.commentReplyContent =
                    question.question.comments[commentIndex];
                this.comment = question.question.comments[commentIndex].comment
            }
            // Si on répond à un commentaire
            else if (responseMod === 2) {
                const question = useQuestionStore();
                // Quel est l'index du commentaire par rapport à son ID?
                const commentIndex = question.question.comments
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(parentId);
                // Quel est l'index du commentaire par rapport à son ID?
                const responseIndex = question.question.comments[
                    commentIndex
                ].responses
                    .map(function (e) {
                        return e.id;
                    })
                    .indexOf(commentId);
                this.commentReplyContent =
                    question.question.comments[commentIndex].responses[
                        responseIndex
                    ];
                this.comment =
                    question.question.comments[commentIndex].responses[
                        responseIndex
                    ].comment;
            }
            // Focus sur l'input
            document.getElementById("commentInput").focus();
            // Focus sur l'input
        },

        // Annuler la réponse
        cancelReplyComment() {
            this.updateResponseMod(0);
            this.updateCommentId(null)
            this.commentReplyContent = {};
            this.commentReplyId = null;
            this.editMod = false;
            this.comment = "";
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
                // Nouveau commentaire
                if (!this.commentId) {
                    const data = {
                        comment: this.comment,
                        questionid: questionStore.question.id,
                        commentreplyid: this.commentReplyId,
                    };
                    let response = await axios.post("/api/comments", data);
                }
                // Modification de commentaire
                else if (this.commentId > 0) {
                    const data = {
                        comment: this.comment,
                        questionid: questionStore.question.id,
                        commentreplyid: this.commentReplyId,
                        commentid: this.commentId,
                    };
                    let response = await axios.patch(
                        `/api/comments/${this.commentid}`,
                        data
                    );
                }
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
