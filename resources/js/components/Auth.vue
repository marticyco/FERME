<template>
    <div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-100 to-gray-100">
      <div class="w-full max-w-sm p-8 bg-white rounded-xl shadow-lg transform transition-all duration-300">
       
  
      <!-- Formulaire de connexion -->
<div v-if="isLoginForm" class="animate-fade-in">
  <h2 class="text-2xl font-extrabold text-center mb-6 text-gray-800 whitespace-nowrap">
    🔐 Connexion à votre espace
  </h2>

  <form @submit.prevent="login" class="space-y-5">
    
    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
        Adresse email
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="fas fa-envelope"></i>
        </span>
        <input
          type="email"
          id="email"
          v-model="loginForm.email"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
          placeholder="exemple@domaine.com"
        />
      </div>
      <span v-if="formErrors.email" class="text-red-500 text-xs">{{ formErrors.email }}</span>
    </div>

    <!-- Mot de passe -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
        Mot de passe
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="fas fa-lock"></i>
        </span>
        <input
          type="password"
          id="password"
          v-model="loginForm.password"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
          placeholder="Votre mot de passe"
        />
      </div>
      <span v-if="formErrors.password" class="text-red-500 text-xs">{{ formErrors.password }}</span>
    </div>

    <!-- Bouton -->
    <button
      type="submit"
      :disabled="loading"
      class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition"
    >
      <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
    </button>

    <!-- Lien vers inscription -->
    <p class="text-center text-sm text-gray-600 mt-4">
      Pas encore de compte ? 
      <a href="#" @click.prevent="toggleForm" class="text-blue-500 font-medium hover:underline">
        S'inscrire
      </a>
    </p>
  </form>
</div>

       <!-- Formulaire d'inscription -->
<div v-else class="animate-fade-in">
  <h2 class="text-2xl font-extrabold text-center mb-6 text-gray-800 whitespace-nowrap">
    📝 Création de compte
  </h2>

  <form @submit.prevent="register" class="space-y-5">
    
    <!-- Nom -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
        Nom complet
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-500">
          <i class="fas fa-user"></i>
        </span>
        <input
          type="text"
          id="name"
          v-model="registerForm.name"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition"
          placeholder="Jean Dupont"
        />
      </div>
      <span v-if="formErrors.name" class="text-red-500 text-xs">{{ formErrors.name }}</span>
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
        Adresse email
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-500">
          <i class="fas fa-envelope"></i>
        </span>
        <input
          type="email"
          id="email"
          v-model="registerForm.email"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
          placeholder="exemple@domaine.com"
        />
      </div>
      <span v-if="formErrors.email" class="text-red-500 text-xs">{{ formErrors.email }}</span>
    </div>

    <!-- Mot de passe -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
        Mot de passe
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-yellow-500">
          <i class="fas fa-lock"></i>
        </span>
        <input
          type="password"
          id="password"
          v-model="registerForm.password"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
          placeholder="••••••••"
        />
      </div>
      <span v-if="formErrors.password" class="text-red-500 text-xs">{{ formErrors.password }}</span>
    </div>

    <!-- Confirmation -->
    <div>
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
        Confirmation mot de passe
      </label>
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-500">
          <i class="fas fa-check-circle"></i>
        </span>
        <input
          type="password"
          id="password_confirmation"
          v-model="registerForm.password_confirmation"
          required
          class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-purple-400 focus:outline-none transition"
          placeholder="••••••••"
        />
      </div>
      <span v-if="formErrors.password_confirmation" class="text-red-500 text-xs">{{ formErrors.password_confirmation }}</span>
    </div>

    <!-- Bouton -->
    <button
      type="submit"
      :disabled="loading"
      class="w-full bg-green-600 text-white font-semibold py-2 rounded-md hover:bg-green-700 transition"
    >
      <i class="fas fa-user-plus mr-2"></i> S'inscrire
    </button>

    <!-- Lien vers connexion -->
    <p class="text-center text-sm text-gray-600 mt-4">
      Vous avez déjà un compte ?
      <a href="#" @click.prevent="toggleForm" class="text-blue-500 font-medium hover:underline">
        Se connecter
      </a>
    </p>
  </form>
</div>

<!-- Déconnexion -->
<div v-if="isAuthenticated" class="mt-6">
  <button
    @click="logout"
    class="w-full bg-red-600 text-white font-semibold py-2 rounded-md hover:bg-red-700 transition"
  >
    <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
  </button>
</div>

      </div>
    </div>
  </template>
  
  
    <script>
    import axios from 'axios';
    import { ref } from 'vue';
    
    
    export default {
      setup() {
        const isLoginForm = ref(true);
        const loading = ref(false);
        const isAuthenticated = ref(localStorage.getItem('token') !== null); // Vérifie si l'utilisateur est déjà connecté
        const loginForm = ref({
          email: '',
          password: '',
        });
        const registerForm = ref({
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
        });
    
        // Erreurs de formulaire
        const formErrors = ref({
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
        });
    
        // Basculer entre le formulaire de connexion et d'inscription
        const toggleForm = () => {
          isLoginForm.value = !isLoginForm.value;
        };
    
        // Fonction pour gérer l'inscription
        const register = async () => {
          try {
            loading.value = true;
            const response = await axios.post('/api/register', registerForm.value);
            console.log('Inscription réussie:', response.data);
            // Rediriger vers le dashboard
            window.location.href = '/dashboard';
          } catch (error) {
            console.error('Erreur lors de l\'inscription:', error.response.data);
            
            // Vérification de l'existence d'erreurs dans la réponse
            if (error.response && error.response.data.errors) {
              const errorMessages = error.response.data.errors;
    
              // Réinitialiser les erreurs
              formErrors.value = {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
              };
    
              // Mettre à jour les erreurs pour chaque champ
              for (let field in errorMessages) {
                formErrors.value[field] = errorMessages[field].join(', ');
              }
            } else {
              alert('Erreur inconnue lors de l\'inscription.');
            }
          } finally {
            loading.value = false;
          }
        };
    
        // Fonction pour gérer la connexion
        const login = async () => {
    try {
      loading.value = true;
      const response = await axios.post('/api/login', loginForm.value);
      localStorage.setItem('token', response.data.token);
      isAuthenticated.value = true;
      window.location.href = '/dashboard';
    } catch (error) {
      loading.value = false;
      // Vérification d'une réponse d'erreur d'API spécifique
      if (error.response) {
        console.error('Erreur lors de la connexion:', error.response.data);
        alert(error.response.data.message || 'Erreur inconnue');
      } else {
        console.error('Erreur réseau ou autre erreur:', error);
        alert('Une erreur est survenue. Vérifiez votre connexion internet.');
      }
    }
  };
  
    
        // Fonction pour gérer la déconnexion
        const logout = async () => {
          try {
            await axios.post('/api/logout');
            localStorage.removeItem('token');
            isAuthenticated.value = false;
            window.location.href = '/'; // Rediriger vers la page de connexion
          } catch (error) {
            console.error('Erreur lors de la déconnexion:', error);
          }
        };
    
        return {
          isLoginForm,
          loading,
          loginForm,
          registerForm,
          formErrors,
          toggleForm,
          register,
          login,
          logout,
          isAuthenticated,
        };
      },
    };
  </script>
  
  
  <style>
  .animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  </style>