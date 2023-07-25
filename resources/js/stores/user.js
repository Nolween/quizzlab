import { defineStore } from "pinia";
import axios from "axios";
import router from "@/router";
import {useToast} from "vue-toastification";

export const useUserStore = defineStore("user", {
    state: () => ({ isConnected: false, errors: [] }),
    actions: {
        // Modification du statut de connexion
        setIsConnected(value) {
            this.isConnected = value;
        },
        // Modification du statut de connexion
        checkAuth() {
            this.isConnected = !!localStorage.getItem('auth');
        },
        // L'utilisateur est-il connecté ?
        checkError(error) {
            // Si l'utilisateur n'est pas autorisé
            if (error.response?.status === 401 || error.response?.status === 403) {
                this.setIsConnected(false);
                // On vire la variable locale
                localStorage.removeItem('auth')
                // Redirect vers la connexion
                router.push({ name: "connexion.create" });
            }
        },
        // Tentative de connexion au back
        async doLogin(data) {
            this.errors = "";
            try {
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/login", data);
                // Stockage de l'ID de l'utilisateur
                this.setIsConnected(true);
                localStorage.setItem('auth', true)
                router.push({ name: "questions.index" });
            } catch (error) {
                this.checkError(error);
            }
        },
        // Tentative d'inscription'
        async doRegistration(data) {
            this.errors = "";
            try {
                await axios.post("/register", data);
                // // Stockage de l'ID de l'utilisateur
                // this.setIsConnected(true);
                // localStorage.setItem('auth', true)
                router.push({ name: "questions.index" });
            } catch (error) {
                this.checkError(error);
            }
        },
        // Tentative d'inscription'
        async doUpdateInformations(data) {
            this.errors = "";
            try {
                let response = await axios.patch("/api/profile/update", data);
                if(response.data.success){
                    // Toastr de réussite
                    const toast = useToast();
                    toast.info('Informations mises à jour !');
                    // Redirection
                    router.push({ name: "questions.index" });
                }
                else {
                    // Toastr de réussite
                    const toast = useToast();
                    toast.error(data.message);
                }
            } catch (error) {
                // Toastr de réussite
                const toast = useToast();
                toast.error(error.response.data.message );
                this.checkError(error);
            }
        },



        // Tentative de connexion au back
        async doLogout(data) {
            try {
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/logout");
                this.setIsConnected(false);
                localStorage.removeItem('auth')
                await router.push({ name: "questions.index" });
                // Reload de la page pour bien réinitialiser le composant
                window.location.reload();
            } catch (error) {
                this.checkError(error);
            }
        },
    },
    getters: {
        getIsConnected: (state) => state.isConnected,
    },
});
