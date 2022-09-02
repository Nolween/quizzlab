import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

export const useGameStore = defineStore("game", {
    state: () => ({ games: [], game: [] }),

    actions: {
        resetGame() {
            this.game = [];
        },
        // Ajout d'un message dans le chat
        addMessage(data) {
            if (this.game.chat) {
                this.game.chat.push(data);
            }
        },
        // Modifier le statut d'un joueur via websocket
        updatePlayerStatus(data) {
            // Quel est l'index du joueur par rapport à son ID joueur/partie?
            let playerIndex = this.game.players
                .map(function (e) {
                    return e.id;
                })
                .indexOf(data.id);
            // On remplace le contenu actuel du joueur avec le statut modifié
            this.game.players[playerIndex] = data;
        },
        // Suppression d'un joueur de la partie via websocket
        deleteGamePlayer(data) {
            // Quel est l'index du joueur par rapport à son ID joueur/partie?
            let playerIndex = this.game.players
                .map(function (e) {
                    return e.id;
                })
                .indexOf(data.id);
            // On vire le joueur du tableau de joueurs
            this.game.players.splice(playerIndex, 1);
        },
        // Ajout d'un joueur dans la partie via websocket
        insertGamePlayer(data) {
            //? On regarde si le joueur n'est pas déjà présent
            // Quel est l'index du joueur par rapport à son ID joueur/partie?
            let playerIndex = this.game.players
                .map(function (e) {
                    return e.id;
                })
                .indexOf(data.id);
            if (playerIndex == -1) {
                // On ajoute le joueur dans le tableau
                this.game.players.push(data);
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
                // Si on a la raison de l'erreur
                if (error.response.data.success == false) {
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                }
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Récupérer les informations de la salle d'attente de jeu
        async getJoiningGame(gameId) {
            try {
                let response = await axios.get(`/api/games/join/${gameId}`);
                // Si on a des données
                if (response.data.data) {
                    this.game = response.data.data;
                }
            } catch (error) {
                // Si on a la raison de l'erreur
                if (error.response.data.success == false) {
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                    // Redirection vers la page des parties
                    router.push({ name: "games.index" });
                }
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Modifier le statut PRET de la partie
        async updateStatus() {
            try {
                let response = await axios.patch(`/api/games/ready`, {
                    userId: this.game.userId,
                    gameId: this.game.game.id,
                });
                if (response.data.data) {
                }
            } catch (error) {
                // Si on a la raison de l'erreur
                if (error.response.data.success == false) {
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                }
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
        // Suppression de joueur dans la partie si départ
        async deleteGamePlayers() {
            try {
                // Quel est l'instance joueur/partie du joueur?
                let playerIndex = this.game.players
                    .map(function (e) {
                        return e.user_id;
                    })
                    .indexOf(this.game.userId);
                // Si on a bien une instance
                if (this.game.players[playerIndex].id) {
                    let response = await axios.delete(
                        `/api/gameplayers/${this.game.players[playerIndex].id}`,
                        {
                            data: {
                                gamePlayerId: this.game.players[playerIndex].id,
                            },
                        }
                    );
                    if (response.data.data) {
                    }
                }
            } catch (error) {
                // Si on a la raison de l'erreur
                if (error.response?.data.success == false) {
                    // Notification
                    const toast = useToast();
                    toast.error(error.response.data.message);
                }
                // Vérification de l'erreur
                const userStore = useUserStore();
                userStore.checkError(error);
            }
        },
    },
});
