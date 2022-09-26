<template>
    <div
        class="pt-40 sm:pt-36 md:pt-28 bg-quizzlab-primary px-2 flex justify-center"
    >
        <form class="space-y-6 w-4/5" @submit.prevent="sendAnswer">
            <div
                class="text-8xl text-center font-bold"
                :class="timeLeft < 4 ? 'text-quizzlab-ternary' : 'text-white'"
            >
                {{ timeLeft }}
            </div>
            <div class="bg-white w-full h-3">
                <div
                    :class="
                        timeLeft < 4
                            ? 'bg-quizzlab-ternary'
                            : 'bg-quizzlab-secondary'
                    "
                    class="h-3"
                    :style="width"
                ></div>
            </div>
            <div class="w-full flex flex-wrap justify-center">
                <!-- THEMES -->
                <span
                    v-for="(tag, tagKey) in gameQuestion.tags"
                    :key="tagKey"
                    class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                    >{{ tag }}</span
                >
            </div>
            <!-- QUESTION -->
            <div
                class="w-full text-center bg-white text-quizzlab-primary text-4xl font-bold p-5"
            >
                {{ gameQuestion.question }}
            </div>
            <!-- CHOIX -->
            <div class="flex flex-wrap justify-around">
                <div
                    v-for="(choice, choiceKey) in gameQuestion.choices"
                    :key="choiceKey"
                    class="cursor-pointer hover:text-white hover:bg-quizzlab-quaternary text-xl font-semibold text-left px-3 lg:px-8 py-3 mb-2 w-1/2 border-x-2 border-quizzlab-primary"
                    :class="
                        choiceId == choice.id
                            ? 'bg-quizzlab-quaternary text-white'
                            : 'bg-white text-quizzlab-primary'
                    "
                    @click="chooseAnswer(choice.id)"
                >
                    {{ choice.title }}
                </div>
            </div>
            <!-- SI IMAGE -->
            <div class="flex justify-center" v-if="gameQuestion.image">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/questions/big/' +
                        gameQuestion.image
                    "
                    class="w-full 2xl:w-2/3 object-cover rounded-md cursor-pointer m-5"
                    :alt="gameQuestion.image"
                    title="Agrandir l'image"
                    @click="displayBigImageQuestion(true)"
                />
            </div>
        </form>
    </div>

    <!-- OVERLAY -->
    <div
        v-if="questionImageOverlay"
        class="h-screen bg-black bg-opacity-50 rounded-sm fixed inset-0 z-50 flex justify-center items-center"
    >
        <div class="w-full">
            <div class="flex justify-between bg-white p-3">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/questions/big/' +
                        gameQuestion.image
                    "
                    class="mt-2 w-full rounded-md mr-3 cursor-pointer"
                    :alt="gameQuestion.image"
                    title="Fermer l'image"
                    @click="displayBigImageQuestion(false)"
                />
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed, onMounted, onBeforeMount } from "vue";
import router from "@/router";
import { useRoute } from "vue-router";
// Import des composants

// Import des stores
import { useUserStore } from "@/stores/user";
// Imports de composables
import { useGameQuestions } from "@/composables/gamequestions";

// Icônes
import SvgIcon from "@jamescoyle/vue-icon";
import { mdiSend } from "@mdi/js";
// Déclaration des stores
const userStore = useUserStore();
const route = useRoute();

const questionImageOverlay = ref(false);

// Déclaration des composables
const { getGameQuestion, sendAnswerProposition, gameQuestion, timeLeft } =
    useGameQuestions();

// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

const choiceId = ref(null);

// Calcul du pourcentage de progression de la barre de temps restant
const width = computed(() => {
    return {
        width:
            100 -
            100 / (gameQuestion.value.responseTime / timeLeft.value) +
            "%",
        "transition-duration": "1s",
    };
});

function chooseAnswer(choice) {
    choiceId.value = choice;
}

const sendAnswer = async () => {
    const sent = await sendAnswerProposition(choiceId.value);
    if(sent && sent.endGame === false) {
        // Récupération de la nouvelle question
        getGameQuestion(route.params.id);
        // Lancement du compte à rebours
        launchCountdown();
    }
    // Si fin de partie
    else if (sent && sent.endGame) {
    // Redirection vers la page des résultats
        router.push({ name: "games.results", params: { id: route.params.id } });
    }
};

//? Fonctions du composant
function displayBigImageQuestion(value) {
    questionImageOverlay.value = value;
}

// Lancement du compte à rebours
const launchCountdown = async () => {
    // Création d'un timer qui change chaque seconde
    let timer = setInterval(function () {
        // Si on arrive à zéro
        if (timeLeft.value <= 0) {
            // On arrête le timer
            clearInterval(timer);
            // On envoie la réponse
            sendAnswer();
        } else {
            // Réduction du timer
            timeLeft.value -= 1;
        }
    }, 1000);
};

onBeforeMount(() => {
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected == false) {
        // Redirection vers l'écran de connexion
        router.push({ name: "connexion.create" });
    } else {
        // On va chercher la question dans le back en fonction de la partie
        getGameQuestion(route.params.id);
    }
});
// Une fois la page montée
onMounted(() => {
    // Lancement du compte à rebours
    launchCountdown();
});
</script>
