import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

export function useGameQuestions() {
    const gameQuestion = ref([]);
    const timeLeft = ref(null);

    const errors = ref("");
    const router = useRouter();

    //? Fonctions Asynchrones

    // Récupération de la question
    const getGameQuestion = async (gameId) => {
        try {
            let response = await axios.get(
                `/api/gamequestions/question/${gameId}`
            );
            // Si on a bien un retour
            if (response.data.data) {
                gameQuestion.value = response.data.data;
                timeLeft.value = response.data.data.responseTime;
                return gameQuestion.value;
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
            router.push({
                name: "games.question",
                params: { gameId },
            });
        }
    };

    // Récupération de la question d'une partie
    const sendAnswerProposition = async (choiceId) => {
        try {
            // Construction du payload
            const data = {
                choice_id: choiceId,
                game_question_id: gameQuestion.value.gameQuestionId,
                question_id: gameQuestion.value.questionId,
                game_id: gameQuestion.value.gameId,
            };
            // Envoi dans le back des résultats
            let response = await axios.post(`/api/gameresults`, data);
            // Si retour ok
            if (response.data) {
            }
        } catch (error) {
            // Si on a un retour du back
            if (error.response.data.success == false) {
                // Notification
                const toast = useToast();
                toast.error(error.response.data.message);
            }
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
            // On réactualise la page
            router.push({
                name: "games.question",
                params: { id: data.gameId },
            });
        }
    };

    return {
        errors,
        gameQuestion,
        sendAnswerProposition,
        getGameQuestion,
        timeLeft,
    };
}
