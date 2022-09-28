<template>
    <div class="px-3 mt-10">
        <!--   Question     -->
        <div class="flex py-4 px-12 sm:px-16 text-white text-4xl text-center font-semibold relative"
             :class="props.isCorrect ? 'bg-quizzlab-secondary' : 'bg-quizzlab-ternary'"
             title="Valeur en points de la question">
            {{ props.question }}
            <div class="w-16 h-16 bg-white absolute -top-4 -right-4 text-xl"
                 :class="props.isCorrect ? 'text-quizzlab-secondary' : 'text-quizzlab-ternary'">
                <span class="pt-4 absolute left-3 text-center"> {{ score }}</span>
            </div>

        </div>

        <!-- Réponse -->
        <div class="flex flex-wrap align-content-between w-full">
            <div
                v-for="(choice, choiceKey) in choices"
                :key="choiceKey"
                :class="
                            choice.is_correct === true
                                ? 'text-quizzlab-secondary'
                                : 'text-quizzlab-ternary'
                        "
                class="bg-white text-2xl font-semibold px-3 lg:px-8 py-3
                 mb-2 w-1/2 border-x-2 border-quizzlab-primary text-center"
            >
                {{ choice.title }}
                <!-- Avatars des réponses utilisateurs -->
                <div class="flex flex-wrap mt-2 justify-around">
                    <img v-for="(user, userKey) in choice.userChoices" :key="userKey"
                         :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' + user.avatar
                    "
                         class="w-10 h-10 object-cover rounded-md mr-3 flex mb-1"
                         :alt="user.user" :title="user.user"
                    />
                </div>
            </div>
        </div>

    </div>

</template>
<script setup>

// Icônes

// Import du store des questions

// Déclaration du store des questions


// Définition des props du composant
const props = defineProps({
    gameQuestionId: Number,
    score: Number,
    choices: Array,
    question: String,
    isCorrect: Boolean,
});

</script>
