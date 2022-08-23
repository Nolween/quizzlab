import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";

export function useGames() {
    const game = ref([]);

    const errors = ref("");
    const router = useRouter();


    //? Fonctions Asynchrones
    // Proposition d'une nouvelle game
    const sendGameProposition = async (data) => {
        try {
            // Création d'un formulaire
            let formData = new FormData();
            formData.append('maxPlayers', data.maxPlayers)
            formData.append('questionCount', data.questionCount)
            formData.append('responseTime', data.responseTime)
            formData.append('gameCode', data.gameCode)
            formData.append('allTags', data.allTags == true ? 1 : 0)
            formData.append('selectedThemes', data.selectedThemes)
            // Envoi dans le back
            let response = await axios.post(`/api/games`, formData);
            // Si retour ok
            if (response.data && response.data.success == true) {
                // Si le joueur est seul
                if(data.maxPlayers === 1) {
                    // Redirection vers la partie
                    router.push({ name: "games.index" });
                }
                // Si plusieurs joueurs, on passe par la file d'attente
                else {
                    router.push({ name: "games.index" });
                }
            }
        } catch (error) {
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };


    return {
        errors,
        game,
        sendGameProposition,
    };
}
