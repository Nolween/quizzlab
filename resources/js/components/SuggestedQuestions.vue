<template>
    <ul class="bg-white mb-4">
        <li class="py-1 px-4 text-xl font-semibold text-quizzlab-primary">
            {{ props.stitle }}
        </li>
        <li
            v-for="(
                suggestedQuestion, suggestedQuestionKey
            ) in props.suggestedQuestions"
            :key="suggestedQuestionKey"
            class="text-xl text-quizzlab-quaternary hover:text-quizzlab-ternary cursor-pointer px-4 py-1 font-semibold"
            @click="selectSuggestedQuestion(suggestedQuestion.question)"
        >
            {{ suggestedQuestion.question }}
        </li>
    </ul>
</template>
<script setup>
// Imports d'éléments de vue


// Définition des props du composant
const props = defineProps({
    stitle: String,
    suggestedQuestions: Array,
});

const emit = defineEmits(["changeSearch"]);

// Lorsque l'on clique sur une question suggérée
const selectSuggestedQuestion = (params) => {
    // On active l'évènement changeSearch (change-search) présent dans le composant parent
    emit("changeSearch", params);
};
</script>
