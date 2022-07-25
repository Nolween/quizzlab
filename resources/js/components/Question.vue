<template>
    <div class="mb-2 bg-white">
        <div class="flex flex-wrap justify-start px-3 lg:px-8">
            <div
                class="w-40 border-gray-300 border-2 p-1 m-2 flex flex-wrap justify-between"
            >
                <svg-icon
                    :path="mdiMinusThickPath"
                    class="text-quizzlab-ternary cursor-pointer"
                    type="mdi"
                ></svg-icon>
                <div class="text-quizzlab-primary font-bold text-xl">-153°</div>
                <svg-icon
                    :path="mdiPlusThickPath"
                    class="text-quizzlab-secondary cursor-pointer"
                    type="mdi"
                ></svg-icon>
            </div>
            <!-- Thèmes -->
            <span
                class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                >Histoire</span
            >
            <span
                class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                >Henri 4</span
            >
            <span
                class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                >Rois</span
            >
            <span
                class="bg-quizzlab-quaternary text-white font-semibold m-2 p-2 text-2xl cursor-pointer"
                >Cheval</span
            >
        </div>
        <!-- Question -->
        <div class="text-quizzlab-primary font-medium text-3xl px-3 lg:px-8 py-2 cursor-pointer">
            Quelle est la couleur du cheval blanc d’Henri 4?
        </div>
        <!-- Réponse -->
        <div
            class="bg-quizzlab-secondary text-white text-3xl font-semibold text-right px-3 lg:px-8 py-3"
        >
            Blanc
        </div>
        <!-- Infos -->
        <div class="flex flex-wrap justify-around py-2 px-3 lg:px-8">
            <div class="flex flex-wrap cursor-pointer">
                <img
                    src="../../../public/img/profile/3.jpg"
                    class="w-10 h-10 object-cover rounded-md mr-3"
                    alt=""
                />
                <span class="text-quizzlab-primary text-xl font-medium pt-1"
                    >Jamy</span
                >
            </div>
            <div class="flex flex-wrap pt-1">
                <svg-icon
                    :path="mdiTimerOutlinePath"
                    class="text-quizzlab-ternary w-7 h-7"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-ternary text-xl font-medium"
                    >16 min</span
                >
            </div>
            <div class="flex flex-wrap cursor-pointer pt-1">
                <svg-icon
                    :path="mdiCommentTextPath"
                    class="text-quizzlab-quaternary w-7 h-7 mr-2"
                    type="mdi"
                ></svg-icon>
                <span class="text-quizzlab-quaternary text-xl font-medium"
                    >4</span
                >
            </div>
        </div>
    </div>
</template>
<script>
import SvgIcon from "@jamescoyle/vue-icon";
import {
    mdiAccount,
    mdiMinusThick,
    mdiPlusThick,
    mdiTimerOutline,
    mdiCommentText
} from "@mdi/js";

export default {
    components: {
        SvgIcon,
    },

    data() {
        return {
            userProfilePath: "3.jpg",
            mdiAccountPath: mdiAccount,
            mdiMinusThickPath: mdiMinusThick,
            mdiPlusThickPath: mdiPlusThick,
            mdiTimerOutlinePath: mdiTimerOutline,
            mdiCommentTextPath: mdiCommentText,
        };
    },
};
</script>
