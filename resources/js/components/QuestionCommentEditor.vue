<template>
    <div>
        <textarea v-model="commentStore.comment" name="comment" class="w-full border-0" rows="6"></textarea>
    </div>
    <div
        class="bg-quizzlab-secondary text-center text-4xl text-white font-semibold mb-2 cursor-pointer"
        @click="commentStore.sendComment"
    >
        ENVOYER
    </div>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import router from "@/router";
import useComments from "@/composables/comments.js"
import {useCommentStore} from "@/stores/comment"

const commentStore = useCommentStore();

</script>
