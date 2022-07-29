<template>
    <div class="pt-40 md:pt-80 flex justify-center bg-quizzlab-primary px-2">
        <form class="space-y-6" @submit.prevent="sendLogin">
        <div class="text-center text-5xl text-white font-semibold">Connexion</div>
            <input
                name="email"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="email"
                autocomplete="email"
                placeholder="Email"
                v-model="form.email"
                v-focus
            />
            <input
                name="password"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="password"
                autocomplete="current-password"
                placeholder="Mot de passe"
                v-model="form.password"
            />
            <div class="text-3xl text-white cursor-pointer" @click="">Mot de passe Oublié?</div>
            <div class="flex flex-wrap justify-center sm:justify-between mx-3 space-x-2">
                <button
                type="button"
                    @click="$router.push({ name: 'registration.create' })"
                    class="bg-quizzlab-quaternary text-white text-2xl py-2 px-3 rounded-sm"
                >
                    Inscription
                </button>
                <button
                    type="submit"
                    class="bg-quizzlab-secondary text-white text-2xl py-2 px-3 rounded-sm"
                >
                    Connexion
                </button>
            </div>
        </form>
    </div>
</template>
<script setup>
import router from "@/router";
import { reactive, onMounted } from "vue";
// Import des stores
import { useUserStore } from "@/stores/user";
// Imports de composables
const userStore = useUserStore();

// Focus du premier champ au chargement de la vue
const vFocus = {
  mounted: (el) => el.focus()
}

const form = reactive({
    email: "",
    password: "",
});

const sendLogin = async () => {
    await userStore.doLogin({ ...form });
};

</script>
