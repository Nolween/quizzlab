<template>
    <div
        class="container mx-auto flex flex-col items-center justify-center pt-32"
    >
        <form class="space-y-6 items-center" @submit.prevent="sendInformationsUpdate">
            <div class="text-center text-5xl text-white font-semibold">
                Mon Compte
            </div>

            <!-- CHAMPS -->

            <!-- Upload d'image -->
            <div class="mx-auto">
                <label for="image-input">
                    <img
                        :src="imgSrc"
                        alt="Avatar"
                        class="cursor-pointer  rounded-full  w-40 h-40 object-cover mx-auto"
                    />
                </label>
                <input
                    id="image-input"
                    type="file"
                    accept=".jpg, .png, .jpeg, .avif, .webp"
                    name="imageInput"
                    @change="onFileChanged($event)"
                    class="hidden"
                    ref="imageInput"
                />
            </div>

            <!-- Email -->
            <input
                name="email"
                class="border-2 text-2xl focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="email"
                placeholder="Email"
                v-model="form.email"
                v-focus
            />
            <!-- Ancien mot de passe -->
            <input
                name="password"
                class="border-2 text-2xl focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="password"
                placeholder="Mot de passe Actuel"
                autocomplete="current-password"
                v-model="form.old_password"
                v-focus
            />
            <!-- Mot de passe -->
            <input
                name="password"
                class="border-2 text-2xl focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="password"
                autocomplete="off"
                placeholder="Nouveau Mot de passe"
                v-model="form.password"
                v-focus
            />
            <!-- Confirmation du mot de passe -->
            <input
                name="password_confirmation"
                class="border-2 text-2xl focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary pt-4"
                type="password"
                autocomplete="off"
                placeholder="Confirmation du mot de passe"
                v-model="form.password_confirmation"
                v-focus
            />

            <!-- BOUTONS  -->
            <div class="flex flex-wrap justify-center mx-3 space-x-2">
                <button
                    type="submit"
                    class="py-2 px-3 rounded-sm mb-12"
                    :class="
                        completedForm
                            ? 'hover:bg-quizzlab-secondary bg-white text-quizzlab-secondary hover:text-white'
                            : 'bg-slate-400 text-white'
                    "
                    :disabled="!completedForm"
                >
                    <span class="font-semibold text-3xl">METTRE A JOUR</span>
                </button>
            </div>
        </form>

    </div>
</template>
<script setup>
import {ref, toRef, reactive, computed, onBeforeMount} from "vue";
import router from "@/router";
// Import des composants
import SuggestedQuestions from "../components/SuggestedQuestions.vue";
import SuggestedTags from "../components/SuggestedTags.vue";
// Import des stores
import {useUserStore} from "@/stores/user";
// Imports de composables
import {useQuestions} from "@/composables/questions";

import {useTags} from "@/composables/tags";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {mdiMagnify} from "@mdi/js";
import {mdiCloseBox} from "@mdi/js";
// Déclaration des stores
const userStore = useUserStore();
// Déclaration des ref pour le focus
const themeInput = ref(null);
const imageInput = ref(null);
const tagListRef = ref(null);
const imgSrc = ref("http://127.0.0.1:5173/public/storage/img/questions/0.avif");

// Déclaration des composables


// Focus du premier champ au chargement de la vue
const vFocus = {
    mounted: (el) => el.focus(),
};

const theme = ref(null);
const themeListOverlay = ref(false);
const handlingTags = ref([]);

const form = reactive({
    email: null,
    old_password: null,
    password: null,
    password_confirmation: null,
    image: null,
});

// Activation du bouton de validation de formulaire
const completedForm = computed(() => {
    return (
        form.old_password && form.old_password.length >= 6 && (
            (form.email.length > 0) ||
            (
                form.password.length >= 6 &&
                form.password_confirmation === form.password
            )
        )

    );
});

// Changement d'image
const onFileChanged = (event) => {
    if (event.target.files[0]) {
        // Attribution de l'url à la source de l'image
        imgSrc.value = URL.createObjectURL(event.target.files[0]);
        form.image = event.target.files[0];
    } else {
        form.image = null;
    }
};


const sendInformationsUpdate = async () => {
    await userStore.doUpdateInformations({...form});
};


onBeforeMount(() => {
    userStore.checkAuth();
    // Si l'utilisateur n'est pas connecté
    if (!userStore.getIsConnected || userStore.getIsConnected === false) {
        // Redirection vers l'écran de connexion
        router.push({name: "connexion.create"});
    }
    // Appel dans l'API pour obtenir l'image de profile
    userStore.getInformations().then((response) => {
        // Attribution de l'url à la source de l'image
        imgSrc.value = "http://127.0.0.1:5173/public/storage/img/profile/" + userStore.computedInformations.avatar;
        // Attribution de l'email à la variable
        form.email = userStore.computedInformations.email;
    });

});
</script>
