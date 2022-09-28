import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

export function useGames() {
    const game = ref([]);
    const gameResults = ref([]);

    const errors = ref("");
    const router = useRouter();

    //? Fonctions Asynchrones
    // Proposition d'une nouvelle game
    const sendGameProposition = async (data, possibleQuestions) => {
        try {
            data.possibleQuestions = possibleQuestions
            data.allTags = data.allTags === true ? 1 : 0;
            // Envoi dans le back
            let response = await axios.post(`/api/games`, data);

            // Si retour ok
            if (response.data && response.data.data.id > 0) {
                // Si le joueur est seul
                if (data.maxPlayers === 1) {
                    // Redirection vers la partie
                    router.push({ name: "games.join", params: { id: response.data.data.id } });
                }
                // Si plusieurs joueurs, on passe par la file d'attente
                else {
                    router.push({ name: "games.join", params: { id: response.data.data.id } });
                }
            }
        } catch (error) {
            // Si on a un retour du back
            if (error.response.data.success === false) {
                // Notification
                const toast = useToast();
                toast.error(error.response.data.message);
            }
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };


    // Récupération des résultats
    const getGameResults = async (gameId) => {
        try {
            let response = await axios.get(
                `/api/games/results/${gameId}`
            );
            // Si on a bien un retour
            if (response.data.data) {
                gameResults.value = response.data.data;
                return gameResults.value;
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
            router.push({
                name: "questions.index"
            });
        }
    };


    return {
        errors,
        game,
        gameResults,
        sendGameProposition,
        getGameResults
    };
}
