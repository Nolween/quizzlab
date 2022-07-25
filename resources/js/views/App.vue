<template>
    <Navigation />
    <div class="h-screen bg-quizzlab-primary pt-24 flex justify-center">
        <div class="w-5/6 lg:w-4/5"><Question/></div>
    </div>
</template>
<script setup>
import Navigation from "../components/Navigation.vue";
import Question from "../components/Question.vue";
</script>
