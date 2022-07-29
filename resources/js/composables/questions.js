import { ref, reactive, toRefs } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useQuestions() {
    const question = ref([]);

    const errors = ref("");
    const router = useRouter();



    
    return {
        errors,
        question,
    };
}
