<template>
    <div class="bg-white">
        <!-- Nom + Temps -->
        <div
            class="flex flex-wrap justify-center sm:justify-between px-4 lg:px-12 mb-4 pt-4"
        >
            <div class="flex flex-wrap cursor-pointer">
                <img
                    :src="
                        'http://127.0.0.1:5173/public/storage/img/profile/' +
                        props.avatar
                    "
                    class="w-10 h-10 object-cover rounded-md mr-3"
                    alt=""
                />

                <span class="text-quizzlab-quinary text-xl font-medium pt-1">{{
                    props.userName
                }}</span>
            </div>
            <div class="flex flex-wrap pt-1">
                <svg-icon
                    :path="mdiTimerOutline"
                    class="text-quizzlab-ternary w-7 h-7 mr-1"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-ternary text-xl font-medium">{{
                    props.ago
                }}</span>
            </div>
        </div>
        <!-- Commentaire -->
        <div
            class="text-2xl text-quizzlab-primary font-medium px-4 lg:px-12 mb-3"
        >
           <span v-if="props.modified == true" class="text-slate-400">(Modifié)</span> {{ props.comment }}
        </div>
        <!-- Actions -->
        <div
            v-if="props.responseDisplay == false"
            class="flex flex-wrap justify-end pr-3 space-x-3 pb-3"
        >
            <span class="text-xl font-medium text-quizzlab-secondary">{{
                props.approvalsCount
            }}</span>
            <span
                :title="
                    props.hasReacted === 1
                        ? `Annuler l'approbation du commentaire`
                        : 'Approuver ce commentaire'
                "
            >
                <svg-icon
                    :path="
                        props.hasReacted == 1 ? mdiThumbUp : mdiThumbUpOutline
                    "
                    class="text-quizzlab-secondary w-7 h-7"
                    :class="props.ownComment == 0 ? 'cursor-pointer' : ''"
                    type="mdi"
                    @click="
                        props.ownComment == 0
                            ? prepareReact(
                                  props.id,
                                  props.hasReacted == 1 ? null : true
                              )
                            : ''
                    "
                ></svg-icon>
            </span>
            <span class="text-xl font-medium text-quizzlab-ternary">
                {{ props.disapprovalsCount }}</span
            >
            <span
                :title="
                    props.hasReacted == 0
                        ? 'Annuler la désapprobation du commetaire'
                        : 'Désapprouver ce commentaire'
                "
            >
                <svg-icon
                    :path="
                        props.hasReacted == 0
                            ? mdiThumbDown
                            : mdiThumbDownOutline
                    "
                    class="text-quizzlab-ternary w-7 h-7"
                    :class="props.ownComment == 0 ? 'cursor-pointer' : ''"
                    type="mdi"
                    @click="
                        props.ownComment == 0
                            ? prepareReact(
                                  props.id,
                                  props.hasReacted == 0 ? null : false
                              )
                            : ''
                    "
                ></svg-icon>
            </span>
            <span
                v-if="userStore.isConnected == true && props.ownComment == 0"
                title="Répondre à ce commentaire"
            >
                <svg-icon
                    :path="mdiCommentArrowRight"
                    class="text-quizzlab-quaternary w-7 h-7 cursor-pointer"
                    type="mdi"
                    @click="
                        commentStore.replyComment(props.id, 2, props.parentId)
                    "
                ></svg-icon>
            </span>
            <span
                v-if="userStore.isConnected == true && props.ownComment == 1"
                title="Modifier ce commentaire"
            >
                <svg-icon
                    :path="mdiPencil"
                    class="text-quizzlab-quaternary w-7 h-7 cursor-pointer"
                    type="mdi"
                    @click="commentStore.editComment(props.id, 2, props.parentId)"
                ></svg-icon>
            </span>
        </div>
        <!-- Rédaction commentaire utilisateur -->
        <div></div>
    </div>
</template>
<script setup>
import router from "@/router";
import { useRoute } from "vue-router";

// Import de composables
// import useComments from "@/composables/comments.js";

// Import des stores
import { useCommentStore } from "@/stores/comment";
import { useUserStore } from "@/stores/user";

// Icones
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiTimerOutline,
    mdiThumbUp,
    mdiThumbUpOutline,
    mdiThumbDown,
    mdiThumbDownOutline,
    mdiCommentArrowRight,
    mdiPencil,
} from "@mdi/js";

// Déclaration des stores
const commentStore = useCommentStore();
const userStore = useUserStore();

// Définition des props du composant
const props = defineProps({
    id: Number,
    questionId: Number,
    parentId: Number,
    comment: String,
    avatar: String,
    userName: String,
    ago: String,
    ownComment: Boolean,
    approvalsCount: Number,
    disapprovalsCount: Number,
    hasReacted: {
        type: [Number, Boolean],
        required: false,
    },
    responseDisplay: Boolean,
    modified: Boolean,
});

//? Reaction sur un commentaire
function prepareReact(commentid, ispositive) {
    const data = { commentid, ispositive };
    commentStore.reactQuestionComment(data);
}
</script>
