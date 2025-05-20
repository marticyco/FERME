import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';  // Importation du composant principal Vue
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import { jsPDF } from 'jspdf';
import '../css/tailwind.css'; // Si tu utilises Tailwind CSS
import 'font-awesome/css/font-awesome.min.css'; // Importation des icônes FontAwesome
import '@fortawesome/fontawesome-free/css/all.css'; // Importation de FontAwesome




// Importation des composants Vue pour les routes
import Dashboard from './components/Dashboard.vue';
import IncubationManager from './components/IncubationManager.vue';
import Auth from './components/Auth.vue';
import LotManager from './components/LotManager.vue';
import PonteManager from './components/PonteManager.vue';
import ClientManager from './components/ClientManager.vue';
import AlimentManager from './components/AlimentManager.vue';
import BandManager from './components/BandManager.vue';
import TraitementManager from './components/TraitementManager.vue';
import CashManager from './components/CashManager.vue';
import Accounting from './components/Accounting.vue';
import RolePermissionManager from './components/RolePermissionManager.vue';



// Définition des routes
const routes = [
    { path: '/dashboard', component: Dashboard, name: 'dashboard' },
    { path: '/', redirect: '/dashboard' },  // Redirection automatique vers Dashboard
    { path: '/incubation-manager', component: IncubationManager, name: 'incubation-manager' },
    { path: '/auth', component: Auth, name: 'auth' },
    { path: '/lot-manager', component: LotManager, name: 'lot-manager' },
    { path: '/ponte-manager', component: PonteManager, name: 'ponte-manager' },
    { path: '/client-manager', component: ClientManager, name: 'client-manager' },
    { path: '/aliment-manager', component: AlimentManager, name: 'aliment-manager' },
    { path: '/band-manager', component: BandManager, name: 'band-manager' },
    { path: '/traitement-manager', component: TraitementManager, name: 'traitement-manager' },
    { path: '/cash-manager', component: CashManager, name: 'cash-manager' },
    { path: '/account', component: Accounting, name: 'account' },
    { path: '/permission-manager', component: RolePermissionManager, name: 'permission-manager' },
];

// Création du routeur Vue Router
const router = createRouter({
    history: createWebHistory(),
    routes
});

// Configuration d'Axios pour la gestion des erreurs globales
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            router.push('/auth'); // Redirection vers la page Auth en cas d'erreur 401
        }
        return Promise.reject(error);
    }
);

// Création de l'application Vue
const app = createApp(App);

// Intégration du routeur
app.use(router);

// Mounting de l'app Vue
app.mount('#app');
