import { defineStore } from "pinia";
import axios from "axios";

export const useQuestionStore = defineStore("question", {
    state: () => ({ questions: [] }),
    // could also be defined as
    // state: () => {
    //   return { count: 0 }
    // },
    actions: {
        // Récupérer les questions dans le back
        async getQuestions() {
            let response = await axios.get("/api/questions");
            this.questions = response.data.data;
        },
        // Modifier le vote d'une question
        async voteQuestion(data) {
            let response = await axios.patch(
                `/api/question/${data.questionid}/vote`,
                data
            );
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
