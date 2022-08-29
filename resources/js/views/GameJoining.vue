<template>
    <div class="pt-24 flex flex-wrap justify-center bg-quizzlab-primary px-5">
        <div class="text-4xl text-white font-bold text-center w-full mb-4">
            PARTIE DE
            {{ gameStore.game.game ? gameStore.game.game.user.name : "" }}
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
                class="bg-quizzlab-secondary p-2 text-white text-2xl font-semibold mb-3 flex"
                v-for="(player, playerKey) in gameStore.game.players"
                :key="playerKey"
            >
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' +
                        player.user.avatar
                    "
                    class="w-10 h-10 object-cover rounded-md"
                    alt=""
                />
                <span
                    class="my-auto ml-2"
                    :class="
                        player.is_ready == true
                            ? 'bg-quizzlab-secondary'
                            : 'bg-quizzlab-quaternary'
                    "
                >
                    {{ player.user.name }}</span
                >
            </div>
        </div>
        <!-- Frame de discussion -->
        <div
            class="overflow-y-auto h-80 bg-white w-full p-4 flex flex-wrap border-2"
        >
            <div
                v-for="(chat, chatKey) in gameStore.game.chat"
                :key="chatKey"
                class="flex w-full"
            >
                <div
                    class="w-1/3"
                    v-if="chat.user.id == gameStore.game.userId"
                ></div>
                <div class="mb-5 p-2 bg-slate-100 w-full md:w-2/3 rounded-lg">
                    <div
                        class="font-semibold pl-2"
                        :class="
                            chat.user.id == gameStore.game.userId
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
        <form class="space-y-6 w-full" @submit.prevent="sendMessage">
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
                <button type="button" class="bg-quizzlab-secondary w-20 pl-6 border-2">
                    
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
        <div class="w-full flex flex-wrap space-x-4 justify-evenly">
            <span
                v-for="(tag, tagKey) in gameStore.game.tags"
                :key="tagKey"
                class="p-2 text-white text-2xl font-semibold mb-3 bg-quizzlab-quaternary"
            >
                {{ tag.tag.name }}</span
            >
        </div>
        <!-- BOUTON PRET -->
        <div class="text-4xl text-white font-bold text-center w-full mb-4">
            <button
                type="button"
                class="bg-quizzlab-secondary text-white font-semibold text-4xl p-4"
            >
                PRET
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed, onBeforeMount, onMounted } from "vue";
import router from "@/router";
import { useRoute } from "vue-router";
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
import { useGameChats } from "@/composables/gamechats.js";

const route = useRoute();
// Déclararation de stores
const gameStore = useGameStore();
const userStore = useUserStore();

// Déclaration de composables
const { sendGameChat } = useGameChats();

const form = reactive({
    message: null,
    gameId: null,
});
// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

// Fonction d'envoi de message
const sendMessage = async () => {
    // Si le message n'est pas vide
    if (form.message !== null && form.message.trim().length > 0) {
        await sendGameChat({ ...form });
    }
};

onBeforeMount(() => {
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected == false) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    } else {
        gameStore.getJoiningGame(route.params.id);
        form.gameId = route.params.id;
    }
});

// Lorsque le composant est monté, on va chercher via l'API les ressources
onMounted(() => {});
</script>
