import { ref, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { useQuestionStore } from "@/stores/question";

export function useComments() {
    const comment = ref("");
    const responseMod = ref(false);

    const errors = ref("");
    const router = useRouter();
    


    return {
        errors,
        comment,
        responseMod,
    };
}
