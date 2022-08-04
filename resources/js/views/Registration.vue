<template>
    <div class="pt-32 flex justify-center bg-quizzlab-primary px-2">
        <form class="space-y-6" @submit.prevent="sendRegistration">
            <div class="text-center text-5xl text-white font-semibold">Inscription</div>
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
                name="name"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="text"
                autocomplete="name"
                placeholder="Pseudo"
                v-model="form.name"
            />
            <input
                name="password"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="password"
                autocomplete="new-password"
                placeholder="Mot de passe"
                v-model="form.password"
            />
            <input
                name="password_confirmation"
                class="border-2 focus:border-quizzlab-ternary rounded-sm w-full px-3 placeholder:text-3xl placeholder:text-quizzlab-primary placeholder:text-center pt-4"
                type="password"
                autocomplete="new-password"
                placeholder="Confirmation MDP"
                v-model="form.password_confirmation"
            />

            <div class="w-full mb-2 px-3 md:px-0">
                <textarea
                    disabled
                    class="h-80 caret-gray-400 border-gray-100 text-gray-400 border-2 text-4xl w-full pl-4 rounded-sm focus:border-gray-400 focus:outline-none mb-3"
                >
Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo assumenda aperiam deserunt officia, veritatis architecto velit! Animi minima voluptas veritatis aut nam dolor unde quia totam perspiciatis. Ut, quae amet! Lorem, ipsum dolor sit amet consectetur adipisicing elit. Vero, aperiam. Similique dolorum dolores tenetur sapiente ab fugiat laborum, labore incidunt magni molestiae accusamus rem laboriosam excepturi aspernatur amet inventore esse?</textarea
                >
            </div>
            <div class="mt-1">
                <input
                    id="rules"
                    type="checkbox"
                    name="rules"
                    v-model="form.rules"
                    value="true"
                    class="h-5 w-5 mb-3 rounded-full accent-quizzlab-primary checked:bg-gray-300 cursor-pointer"
                />
                <label for="rules" id="rules"
                    ><span class="text-3xl text-white pl-2 cursor-pointer"
                        >J'ai lu et j'accepte les règles de la charte
                        ci-dessus.</span
                    ></label
                >
            </div>

            <div class="flex flex-wrap justify-center mx-3 mb-12">
                <button
                    type="submit"
                    class="text-white text-3xl py-2 px-3 rounded-sm"
                    :class="getEnabled == false ? 'bg-slate-500' : 'bg-quizzlab-secondary'"
                    :disabled="getEnabled == false"
                >
                    Inscription
                </button>
            </div>
            <div></div>
        </form>
    </div>
</template>
<script setup>
import router from "@/router";
import { reactive, computed } from "vue";
// Import des stores
import { useUserStore } from "@/stores/user";
// Déclaration des stores
const userStore = useUserStore();

const form = reactive({
    email: "",
    name: "",
    password: "",
    password_confirmation: "",
    rules: false,
});

// Focus du premier champ au chargement de la vue
const vFocus = {
  mounted: (el) => el.focus()
}

// Activation du bouton de validation de formulaire si acceptation des règles
const getEnabled = computed(() => {
    // Validation Mail
    const mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    const validMail = mailformat.test(form.email);

    const validName = form.name.length >= 2
    // Validation MDP
    const validPassword = form.password.replace(/\s/g, "").length >= 5;
    const validConfirmation =
        form.password === form.password_confirmation.replace(/\s/g, "");
    const validRules = form.rules === true
    return validRules && validName && validPassword && validConfirmation && validMail
});

const sendRegistration = async () => {
    await userStore.doRegistration({ ...form });
};
</script>
