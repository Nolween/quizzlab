import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";

export const useGameStore = defineStore("game", {
    state: () => ({ games: [], game: [] }),

    actions: {
        resetGame() {
            this.game = [];
        },
        // Ajout d'un message dans le chat
        addMessage(data) {
            if(this.game.chat) {
                this.game.chat.push(data)
            }
        },
        // Récupérer les parties dans le back
        async getGames(search = null) {
            try {
                let response = await axios.get("/api/games", {
                    params: { search },
                });
                this.games = response.data.data;
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Récupérer la partie dans le back
        async getGame(gameId) {
            try {
                let response = await axios.get(`/api/games/${gameId}`);
                // Si on a pas le droit d'aller sur la game car intégrée au quizz
                if (response.data.data.forbidden) {
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                } else {
                    this.game = response.data.data;
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Récupérer les informations de la salle d'attente de jeu
        async getJoiningGame(gameId) {
            try {
                let response = await axios.get(`/api/games/join/${gameId}`);
                // Si on a pas le droit d'aller sur la game car intégrée au quizz
                if (response.data.data.forbidden) {
                    this.game = [];
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                    // Retour à la liste de jeux
                    router.push({ name: "games.index" });
                }
                // Affichage des infos de la partie
                else {
                    this.game = response.data.data;
                }
            } catch (error) {
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
    },
    
});
