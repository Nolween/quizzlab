<template>
    <div class="pt-24 flex justify-evenly">
        <form class="space-y-6" @submit.prevent="sendLogin">
            <div>
                <input
                    name="email"
                    class="mt-1 border-2 focus:border-quizzlab-quinary rounded-sm"
                    type="email"
                    placeholder="Email"
                    v-model="form.email"
                />
            </div>
            <div>
                <input
                    name="password"
                    class="mt-1 border-2 focus:border-quizzlab-quinary rounded-sm"
                    type="password"
                    placeholder="Mot de passe"
                    v-model="form.password"
                />
            </div>
            <button
                type="submit"
                class="bg-quizzlab-secondary text-white text-2xl py-2 px-3 rounded-sm"
            >
                Envoyer
            </button>
        </form>
    </div>
</template>
<script setup>
import { reactive } from "vue";
// Imports de composables
import useUsers from "../../js/composables/users";
// Déclaration des constantes à utiliser dans le composant
const { getLogin } = useUsers();

const form = reactive({
    email: "",
    password: "",
});

const sendLogin = async () => {
    await getLogin({ ...form });
};
</script>
