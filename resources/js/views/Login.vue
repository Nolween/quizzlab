<template>
    <div class="pt-40 md:pt-80 flex justify-center bg-quizzlab-primary">
        <form class="space-y-6" @submit.prevent="sendLogin">
            <input
                name="email"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="email"
                placeholder="Email"
                v-model="form.email"
            />
            <input
                name="password"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="password"
                placeholder="Mot de passe"
                v-model="form.password"
            />
            <div class="text-3xl text-white cursor-pointer" @click="">Mot de passe Oublié?</div>
            <div class="flex flex-wrap justify-between mx-3">
                <button
                    @click="$router.push({ name: 'questions.index' })"
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
import { reactive } from "vue";
// Import des stores
import { useUserStore } from "@/stores/user";
// Imports de composables
const userStore = useUserStore();

const form = reactive({
    email: "",
    password: "",
});

const sendLogin = async () => {
    await userStore.doLogin({ ...form });
};
</script>
