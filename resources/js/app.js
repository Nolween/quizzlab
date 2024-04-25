import "./bootstrap";

// Utilisé pour la page d'accueil 
import Alpine from "alpinejs";

import { createApp } from "vue";
import router from "./router";
// Remplaçant de vuex pour les state management
import { createPinia } from "pinia";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// Import du composant root
import App from "./App.vue";

window.Alpine = Alpine;

Alpine.start();

const app = createApp(App);
app.use(createPinia());
app.use(Toast, {});
app.use(router).mount("#app");
