<template>
    <Navigation/>
    <div class="min-h-screen h-auto" id="appPart" :class="getBackgroundClass">
        <router-view/>
    </div>
</template>
<script setup>
import {ref, computed} from "vue";
import router from "@/router";
// Import des composants
import Navigation from "./components/Navigation.vue";

// La couleur de fond de l'application est définie en fonction de la route
const getBackgroundClass = computed(() => (
    {
        "bg-quizzlab-primary": !router.currentRoute.value.name || !router.currentRoute.value.name.includes("admin"),
        "bg-quizzlab-ternary": router.currentRoute.value.name && router.currentRoute.value.name.includes("admin"),
    }));

</script>
