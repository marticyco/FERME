<template>
    <div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-100 to-gray-100">
      <div class="w-full max-w-sm p-8 bg-white rounded-xl shadow-lg transform transition-all duration-300">
       
  
        <!-- Formulaire de connexion -->
        <div v-if="isLoginForm" class="animate-fade-in">
          <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">Connexion</h2>
          <form @submit.prevent="login">
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <div class="relative">
                <input type="email" id="email" v-model="loginForm.email" required 
                       class="mt-2 p-3 w-full border border-gray-300 rounded-md pl-10 focus:ring-2 focus:ring-blue-400 transition" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <span v-if="formErrors.email" class="text-red-500 text-xs">{{ formErrors.email }}</span>
            </div>
  
            <div class="mb-4">
              <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
              <div class="relative">
                <input type="password" id="password" v-model="loginForm.password" required 
                       class="mt-2 p-3 w-full border border-gray-300 rounded-md pl-10 focus:ring-2 focus:ring-blue-400 transition" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <span v-if="formErrors.password" class="text-red-500 text-xs">{{ formErrors.password }}</span>
            </div>
  
            <button type="submit" :disabled="loading"
                    class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">
              Se connecter
            </button>
  
            <p class="text-center text-sm text-gray-600 mt-4">
              Pas encore de compte ? 
              <a href="#" @click.prevent="toggleForm" class="text-blue-500 hover:underline">S'inscrire</a>
            </p>
          </form>
        </div>
  
        <!-- Formulaire d'inscription -->
        <div v-else class="animate-fade-in">
          <h2 class="text-2xl font-bold text-center mb-4 text-gray-800">Inscription</h2>
          <form @submit.prevent="register">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
              <input type="text" id="name" v-model="registerForm.name" required 
                     class="mt-2 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 transition" />
              <span v-if="formErrors.name" class="text-red-500 text-xs">{{ formErrors.name }}</span>
            </div>
  
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <div class="relative">
                <input type="email" id="email" v-model="registerForm.email" required 
                       class="mt-2 p-3 w-full border border-gray-300 rounded-md pl-10 focus:ring-2 focus:ring-blue-400 transition" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              <span v-if="formErrors.email" class="text-red-500 text-xs">{{ formErrors.email }}</span>
            </div>
  
            <div class="mb-4">
              <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
              <div class="relative">
                <input type="password" id="password" v-model="registerForm.password" required 
                       class="mt-2 p-3 w-full border border-gray-300 rounded-md pl-10 focus:ring-2 focus:ring-blue-400 transition" />
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <span v-if="formErrors.password" class="text-red-500 text-xs">{{ formErrors.password }}</span>
            </div>
  
            <div class="mb-4">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
              <input type="password" id="password_confirmation" v-model="registerForm.password_confirmation" required 
                     class="mt-2 p-3 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 transition" />
              <span v-if="formErrors.password_confirmation" class="text-red-500 text-xs">{{ formErrors.password_confirmation }}</span>
            </div>
  
            <button type="submit" :disabled="loading"
                    class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">
              S'inscrire
            </button>
  
            <p class="text-center text-sm text-gray-600 mt-4">
              Vous avez déjà un compte ? 
              <a href="#" @click.prevent="toggleForm" class="text-blue-500 hover:underline">Se connecter</a>
            </p>
          </form>
        </div>
  
        <!-- Déconnexion -->
        <div v-if="isAuthenticated" class="mt-4">
          <button @click="logout" 
                  class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition">
            Se déconnecter
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