import { ref, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useQuestions() {
    const question = ref([]);
    // const forbiddenQuestion = ref(false);

    const errors = ref("");
    const router = useRouter();


    // const updateForbiddenQuestion = (value) => {
    //     forbiddenQuestion.value = value
    // }

    
    return {
        errors,
        question,
        // forbiddenQuestion,
        // updateForbiddenQuestion,
    };
}
