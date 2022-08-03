<template>
    <Navigation />
    <div class="min-h-screen h-auto bg-quizzlab-primary" id="appPart">
    <router-view />
    </div>
</template>
<script setup>
// Import des composants
import Navigation from "./components/Navigation.vue";
</script>
