import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";

export const useQuestionStore = defineStore("question", {
    state: () => ({ questions: [] }),
    // could also be defined as
    // state: () => {
    //   return { count: 0 }
    // },
    actions: {
        // Récupérer les questions dans le back
        async getQuestions() {
            try {
                let response = await axios.get("/api/questions");
                this.questions = response.data.data;
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
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
            // Quelle est l'index de la question par rapport à son id?
            const questionIndex = this.questions
                .map(function (e) {
                    return e.id;
                })
                .indexOf(data.questionid);
            this.questions[questionIndex].hasVoted = data.ispositive;
        },
    },
});
