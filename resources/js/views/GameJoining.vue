<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary px-5">
        <div class=" text-center w-full mb-4 flex flex-wrap justify-center md:justify-evenly ">
            <span class=" p-3 text-4xl text-white font-bold">PARTIE DE
            {{ gameStore.game.game ? gameStore.game.game.user.name : "" }}</span>
            <button type="button"
                    class="p-3 bg-quizzlab-quinary text-white text-3xl font-bold cursor-pointer"
                    @click="copyGameCode()"
                    >Code partie</button
                >
        </div>
        <!-- INFOS -->
        <div
            class="text-4xl text-white font-bold text-center w-full mb-4 bg-white flex flex-wrap justify-center py-2"
            v-if="gameStore.game.game"
        >
            <!-- Nombre de questions -->
            <div class="w-full sm:w-1/2 md:w-1/3 flex justify-center my-3">
                <svg-icon
                    :path="mdiMessageQuestion"
                    class="text-quizzlab-primary w-7 h-7 mr-1 pt-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-primary text-2xl font-medium pb-1"
                    >{{
                        gameStore.game.game.question_count || null
                    }}
                    Questions</span
                >
            </div>
            <!-- Temps de réponse -->
            <div class="w-full sm:w-1/2 md:w-1/3 flex justify-center my-3">
                <svg-icon
                    :path="mdiTimerOutline"
                    class="text-quizzlab-ternary w-7 h-7 mr-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-ternary text-xl font-medium"
                    >{{ gameStore.game.game.response_time }} secondes</span
                >
            </div>
            <!-- Places de joueurs -->
            <div class="w-full sm:w-1/2 md:w-1/3 flex justify-center my-3">
                <svg-icon
                    :path="mdiAccountGroup"
                    class="text-quizzlab-quaternary w-7 h-7 mr-2"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-quaternary text-xl font-medium"
                    >{{ gameStore.game.players.length }} /
                    {{ gameStore.game.game.max_players }}</span
                >
            </div>
        </div>
        <!-- JOUEURS -->
        <div class="w-full flex flex-wrap space-x-4 justify-evenly">
            <div
                class="p-2 text-white text-2xl font-semibold mb-3 flex"
                v-for="(player, playerKey) in gameStore.game.players"
                :key="playerKey"
                :class="
                    player.is_ready === 1
                        ? 'bg-quizzlab-secondary'
                        : 'bg-quizzlab-quaternary'
                "
            >
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' +
                        player.user.avatar
                    "
                    class="w-10 h-10 object-cover rounded-md"
                    alt=""
                />
                <span class="my-auto ml-2"> {{ player.user.name }}</span>
            </div>
        </div>
        <!-- Frame de discussion -->
        <div
            class="overflow-y-auto h-80 bg-white w-full p-4 flex flex-wrap border-2"
            id="chat-frame"
        >
            <div
                v-for="(chat, chatKey) in gameStore.game.chat"
                :key="chatKey"
                class="flex w-full"
            >
                <div
                    class="w-1/3"
                    v-if="chat.user.id === gameStore.game.userId"
                ></div>
                <div class="mb-5 p-2 bg-slate-100 w-full md:w-2/3 rounded-lg">
                    <div
                        class="font-semibold pl-2"
                        :class="
                            chat.user.id === gameStore.game.userId
                                ? 'text-quizzlab-secondary'
                                : 'text-quizzlab-primary'
                        "
                    >
                        {{ chat.user.name }}
                    </div>
                    <div class="rounded-md p-2 m-2">
                        {{ chat.text }}
                    </div>
                </div>
            </div>
        </div>
        <form class="space-y-6 w-full mb-6" @submit.prevent="sendMessage">
            <input type="hidden" v-model="form.gameId" />
            <div class="mb-4 w-full flex">
                <!-- Rédaction commentaire -->
                <textarea
                    v-model="form.message"
                    name="message"
                    class="w-full border-2"
                    rows="3"
                    id="messageInput"
                ></textarea>
                <button
                    type="button"
                    class="bg-quizzlab-secondary w-20 pl-6 border-2"
                >
                    <svg-icon
                        :path="mdiSend"
                        class="text-white w-7 h-7"
                        type="mdi"
                        @click="sendMessage()"
                    ></svg-icon>
                </button>
            </div>
        </form>
        <!-- Thèmes associés -->
        <div class="text-4xl text-white font-bold text-center w-full mb-4">
            THEMES
        </div>
        <div class="w-full flex flex-wrap space-x-4 justify-evenly mb-6">
            <span
                v-for="(tag, tagKey) in gameStore.game.tags"
                :key="tagKey"
                class="p-2 text-white text-2xl font-semibold mb-3 bg-quizzlab-quaternary"
            >
                {{ tag.tag.name }}</span
            >
        </div>
        <!-- BOUTON PRET -->
        <div
            class="text-4xl text-white font-bold text-center w-full mb-4 space-x-4"
        >
            <button
                type="button"
                class="bg-quizzlab-secondary text-white font-semibold text-4xl p-4"
                :disabled="busyReady === true"
                @click="modifyStatus()"
            >
                PRET
            </button>
            <!-- Si l'utilisateur est le créateur de la partie -->
            <button
                v-if="
                    gameStore.game.game &&
                    gameStore.game?.userId === gameStore.game?.game.user_id
                "
                type="button"
                class="bg-quizzlab-ternary text-white font-semibold text-4xl p-4"
                @click="beginGame()"
            >
                LANCER
            </button>
        </div>
        <!-- OVERLAY DE COMPTE A REBOURS -->

        <div
            v-if="countdownOverlay"
            class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
        >
            <div class="w-4/5">
                <div
                    class="text-quizzlab-primary text-3xl lg:text-5xl bg-white text-center pt-3 px-2"
                >
                    Préparez-vous pour le quizz!
                </div>
                <div
                    class="flex justify-center bg-white p-3 h-80 text-center space-x-3"
                >
                    <div class="shapes-4 my-auto"></div>
                    <span
                        class="text-7xl md:text-9xl font-semibold text-quizzlab-ternary my-auto"
                        >{{ countdown }}</span
                    >
                    <div class="shapes-4 my-auto"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {
    ref,
    reactive,
    watch,
    onBeforeMount,
    onMounted,
    onUnmounted,
} from "vue";
import router from "@/router";
import { useRoute } from "vue-router";
import { useToast } from "vue-toastification";
// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiMessageQuestion,
    mdiAccountGroup,
    mdiTimerOutline,
    mdiSend,
} from "@mdi/js";

// Imports de stores;
import { useGameStore } from "@/stores/game";
import { useUserStore } from "@/stores/user";

// Imports de composables
import { useGameChats } from "@/composables/gamechats";

const route = useRoute();
// Déclaration de stores
const gameStore = useGameStore();
const userStore = useUserStore();

// Déclaration de composables
const { sendGameChat } = useGameChats();

const form = reactive({
    message: null,
    gameId: null,
});
const busyReady = ref(false);
// Overlay du compte à rebours
const countdownOverlay = ref(false);
const countdown = ref(10);
// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

// On surveille le compteur
watch(
    () => countdown.value,
    (count) => {
        if (count <= 0) {
            // On dirige vers la partie
        }
    }
);

watch(gameStore, (newVal) => {
    // On surveille si la partie a commencé
    if (newVal.game.game?.has_begun && newVal.game.game.has_begun === 1) {
        // On lance le compteur
        launchCountdown();
    }

    // On surveille si tous les joueurs sont prêts
    if (newVal.game?.players) {
        // Quel est le nombre de joueurs max de la partie
        let maxReady = newVal.game.game.max_players;
        let readyCount = 0;
        // Parcours des joueurs de la partie
        for (let playerIndex in newVal.game.players) {
            // Si le joueur est prêt
            if (newVal.game.players[playerIndex].is_ready === 1) {
                // Un joueur de plus dans le compte
                readyCount = readyCount + 1;
            }
        }
        // Si tous les joueurs sont prêts
        if (readyCount >= maxReady) {
            // On va demander au premier joueur de la liste de déclencher le lancement de la partie
            if (newVal.game.userId === newVal.game.players[0].user_id) {
                beginGame();
            }
        }
    }
});

// Copie dans le presse papier du code de la partie pour le partage
const copyGameCode = () => {
    // Copie du code dans le presse papier
    navigator.clipboard.writeText(gameStore.game.game.game_code);
    // Notification
    const toast = useToast();
    toast.success('Code partie copié!' );
};

// Activation dans le back du début de partie
const beginGame = async () => {
    await gameStore.updateGameBgin();
};

// Lancement du compte à rebours
const launchCountdown = () => {
    // Affichage de l'overlay
    countdownOverlay.value = true;
    // Création d'un timer qui change chaque seconde
    let timer = setInterval(function () {
        // Si on arrive à 0
        if (countdown.value <= 0) {
            // On arrête le timer
            clearInterval(timer);
            // On se dirige vers la partie
            router.push({ name: "games.question", params: { id: route.params.id } });
        } else {
            // Réduction du timer
            countdown.value -= 1;
        }
    }, 1000);
};

// Envoi de message
const sendMessage = async () => {
    // Si le message n'est pas vide
    if (form.message !== null && form.message.trim().length > 0) {
        await sendGameChat({ ...form });
        // On vide l'input après l'envoi
        form.message = null;
    }
};

// Mise à jour du statut pour la partie
const modifyStatus = async () => {
    // Désactivation du bouton le temps du process
    busyReady.value = true;
    await gameStore.updateStatus();
    busyReady.value = false;
};

onBeforeMount(() => {
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected === false) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    } else {
        gameStore.getJoiningGame(route.params.id);
        form.gameId = route.params.id;
    }
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(() => {
    //? Partie Discussion
    // On écoute le channel chat + l'id de la partie, et dés qu'un évènement nommé message.sent (défini avec la fonction broadcastAs() dans l'event)
    window.Echo.private("game." + route.params.id)
        .listen(".message.sent", (e) => {
            // On ajoute la discussion dans le chat le message
            gameStore.addMessage(e);
        })
        // Statut de partie
        .listen(".game.ready", (e) => {
            // On modifie le statut du joueur concerné
            gameStore.updatePlayerStatus(e);
        })
        // Ajout de joueur
        .listen(".game.join", (e) => {
            // On ajoute le joueur concerné
            gameStore.insertGamePlayer(e);
        })
        // Suppression de joueur
        .listen(".game.leave", (e) => {
            // On supprime le joueur concerné
            gameStore.deleteGamePlayer(e);
        })
        // Lancement d'une partie
        .listen(".game.begin", (e) => {
            // On modifie le statut de commencement de la partie
            gameStore.game.game.has_begun = e.has_begun;
        });
});

// Lorsque l'utilisateur quitte la page
onUnmounted(() => {
    gameStore.deleteGamePlayers();
});
</script>

<style scoped>
.shapes-4 {
    width: 40px;
    height: 40px;
    color: #d92b2b;
    background: conic-gradient(
            from -45deg at top 20px left 50%,
            #0000,
            currentColor 1deg 90deg,
            #0000 91deg
        ),
        conic-gradient(
            from 45deg at right 20px top 50%,
            #0000,
            currentColor 1deg 90deg,
            #0000 91deg
        ),
        conic-gradient(
            from 135deg at bottom 20px left 50%,
            #0000,
            currentColor 1deg 90deg,
            #0000 91deg
        ),
        conic-gradient(
            from -135deg at left 20px top 50%,
            #0000,
            currentColor 1deg 90deg,
            #0000 91deg
        );
    animation: sh4 1s infinite cubic-bezier(0.3, 1, 0, 1);
}
@keyframes sh4 {
    50% {
        width: 60px;
        height: 60px;
        transform: rotate(180deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
