<template>
    <ul class="bg-white mb-4">
        <li class="py-1 px-4 text-xl font-semibold text-quizzlab-primary">
            Thèmes suggérés
        </li>
        <li
            v-for="(
                suggestedTag, suggestedTagKey
            ) in props.suggestedTags"
            :key="suggestedTagKey"
            class="text-xl text-quizzlab-quaternary hover:text-quizzlab-ternary cursor-pointer px-4 py-1 font-semibold"
            @click="selectSuggestedTag(suggestedTag.tag)"
        >
            {{ suggestedTag.tag }}
        </li>
    </ul>
</template>
<script setup>
// Imports d'éléments de vue
import { ref } from "vue";

// Définition des props du composant
const props = defineProps({
    suggestedTags: Array,
});

const emit = defineEmits(['changeSearch'])

// Lorque l'on clique sur une tag suggérée
const selectSuggestedTag = (params) =>  {
    // On active l'évènement changeSearch (change-search) présent dans le composant parent
    emit('changeSearch', params)
}
</script>
