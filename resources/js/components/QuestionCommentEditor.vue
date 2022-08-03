<template>
    <div>
        <textarea
            v-model="commentStore.comment"
            name="comment"
            class="w-full border-0"
            rows="6"
            id="commentInput"
        ></textarea>
    </div>
    <div class="flex flex-wrap justify-between">
        <div
        v-if="commentStore.responseMod > 0"
            class="bg-quizzlab-quaternary text-center text-4xl text-white font-semibold mb-2 cursor-pointer w-full md:w-1/2"
            @click="commentStore.cancelReplyComment"
        >
            ANNULER
        </div>
        <div
            :class="commentStore.responseMod > 0 ? 'w-full md:w-1/2' : 'w-full'"
            class="bg-quizzlab-secondary text-center text-4xl text-white font-semibold mb-2 cursor-pointer"
            @click="commentStore.sendComment"
        >
            ENVOYER
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import router from "@/router";
import useComments from "@/composables/comments.js";
import { useCommentStore } from "@/stores/comment";

const commentStore = useCommentStore();
</script>
