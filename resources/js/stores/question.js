import {defineStore} from "pinia";
import axios from "axios";
import {useUserStore} from "@/stores/user";

export const useQuestionStore = defineStore("question", {
    state: () => ({questions: [], question: []}),

    actions: {
        resetQuestion() {
            this.question = [];
        },
        // Récupérer les questions dans le back
        async getQuestions(search = null, searchMod = 0, isForModeration = false) {
            try {
                let response = await axios.get(isForModeration ? "/api/admin/questions" : "/api/questions", {
                    params: {search, searchMod},
                });
                this.questions = response.data.data;
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Récupérer les questions dans le back
        async getQuestion(questionId) {
            try {
                let response = await axios.get(`/api/questions/${questionId}`);
                // Si on a pas le droit d'aller sur la question car intégrée au quizz
                if (response.data.data.forbidden) {
                    this.question = [];
                } else {
                    this.question = response.data.data;
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Modifier le vote d'une question
        async voteQuestion(data) {
            try {
                let response = await axios.patch(
                    `/api/question/${data.questionid}/vote`,
                    data
                );
                if (response.data.data) {
                    // Quelle est l'index de la question par rapport à son id?
                    const questionIndex = this.questions
                        .map(function (e) {
                            return e.id;
                        })
                        .indexOf(data.questionid);
                    this.questions[questionIndex].hasVoted = data.ispositive;
                    // Modification du score de vote
                    this.questions[questionIndex].vote = response.data.data.voteScore;
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Modérer la question
        async moderateQuestion(data) {
            try {
                let response = await axios.patch(
                    `/api/admin/question/${data.questionid}/moderate`,
                    data
                );
                if (response.data.data) {
                    // Quelle est l'index de la question par rapport à son id?
                    const questionIndex = this.questions
                        .map(function (e) {
                            return e.id;
                        })
                        .indexOf(data.questionid);
                    // Remove question
                    this.questions.splice(questionIndex, 1);
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        }
    },
});
