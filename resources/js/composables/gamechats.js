import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { useToast } from "vue-toastification";

export function useGameChats() {
    const game = ref([]);

    const errors = ref("");
    const router = useRouter();

    //? Fonctions Asynchrones
    // Envoi d'un nouveau message
    const sendGameChat = async (data) => {
        try {
            // Création d'un formulaire
            let formData = new FormData();
            formData.append("message", data.message);
            formData.append("gameId", data.gameId);
            // Envoi dans le back
            let response = await axios.post(`/api/gamechats`, formData);

            // Si retour ok
            if (response.data) {
            }
        } catch (error) {
            // Si on a un retour du back
            if (error.response.data.success == false) {
                // Notification
                const toast = useToast();
                toast.error(error.response.data.message);
            }
            // Vérification de l'erreur
            const userStore = useUserStore();
            userStore.checkError(error);
        }
    };

    return {
        errors,
        game,
        sendGameChat,
    };
}
