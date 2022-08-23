<template>
    <div
        class="fixed z-40 w-full text-4xl bg-white flex flex-wrap py-4 text-center justify-around drop-shadow-md"
    >
        <div class="text-quizzlab-ternary px-1">
            <span
                @click="$router.push({ name: 'questions.index' })"
                class="cursor-pointer"
                >Quizzlab</span
            >
        </div>
        <div class="text-quizzlab-secondary px-1">
            <span
                @click="$router.push({ name: 'games.index' })"
                class="cursor-pointer"
                >Jouer</span
            >
        </div>
        <div class="text-quizzlab-primary px-1">
            <span
                @click="$router.push({ name: 'proposition.create' })"
                class="cursor-pointer"
                >Proposer</span
            >
        </div>
        <div
            v-if="userStore.isConnected == true"
            class="text-quizzlab-quinary px-1"
        >
            <span @click="userStore.doLogout()" class="cursor-pointer"
                >Déconnexion</span
            >
        </div>
        <div v-else class="text-quizzlab-quinary px-1">
            <span
                @click="$router.push({ name: 'connexion.create' })"
                class="cursor-pointer"
                >Connexion</span
            >
        </div>
        <div v-if="userStore.isConnected == true" class="text-quizzlab-quinary px-1">
            <span class="cursor-pointer" onclick="">Compte</span>
        </div>
    </div>
</template>
<script setup>
import { onBeforeMount } from 'vue';
import { useUserStore } from "@/stores/user";

const userStore = useUserStore();

</script>
