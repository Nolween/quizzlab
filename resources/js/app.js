import "./bootstrap";

// Utilisé pour la page d'accueil 
import Alpine from "alpinejs";

import { createApp } from "vue";
import router from "./router";
// Remplaçant de vuex pour les state management
import { createPinia } from "pinia";

// Import du composant root
import App from "./App.vue";

window.Alpine = Alpine;

Alpine.start();

const app = createApp(App);
app.use(createPinia());
app.use(router).mount("#app");
