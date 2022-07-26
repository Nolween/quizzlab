<template>
    <Navigation />
    <div class="min-h-screen bg-quizzlab-primary">
    <router-view />
    </div>
</template>
<script setup>
// Import des composants
import Navigation from "./components/Navigation.vue";
</script>
