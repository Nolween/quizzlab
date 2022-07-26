import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function useUsers() {
    const user = ref([]);
    const users = ref([]);

    const errors = ref("");
    const router = useRouter();

    const getLogin = async (data) => {
        errors.value = "";
        try {
            let response = await axios.post("/login", data);
            await router.push({ name: 'questions.index' })
        } catch (error) {
            console.log(error);
            
            if (e.response.status === 422) {
                for (const key in e.response.data.errors) {
                    errors.value += e.response.data.errors[key][0] + " ";
                }
            } else {
                errors.value += "Erreur inconnue";
            }
        }
    };

    return {
        errors,
        user,
        users,
        getLogin,
    };
}
