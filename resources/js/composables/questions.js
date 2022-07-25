import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useQuestions() {
    const question = ref([]);
    const questions = ref([]);

    const errors = ref("");
    const router = useRouter();

    const getQuestions = async () => {
        let response = await axios.get("/api/questions");
        questions.value = response.data.data;
    };

    return {
        errors,
        question,
        questions,
        getQuestions,
    };
}
