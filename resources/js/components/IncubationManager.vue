<template>
    <div id="app" class="flex flex-col sm:flex-row h-screen bg-gray-100">

<!-- Sidebar -->
<div id="sidebar" class="bg-gray-900 text-white p-6 sm:w-56 w-full sm:h-auto h-full shadow-md">
  
  <!-- Logo & Title -->
  <div class="logo-container flex items-center mb-10 space-x-3">
    <i class="fas fa-dove text-blue-400 text-2xl"></i>
    <h1 class="text-lg font-bold tracking-wide text-white">Gestion des Incubations</h1>
  </div>

  <!-- Navigation -->
  <nav class="flex flex-col gap-3">
    <!-- Incubations -->
    <button
      class="flex items-center gap-3 text-white hover:bg-blue-600 px-4 py-2 rounded-lg transition duration-150"
      @click="setActiveTab('incubations')"
    >
      <i class="fas fa-egg text-yellow-400"></i>
      <span class="text-sm font-medium">Incubations</span>
    </button>

    <!-- Statistiques -->
    <button
      class="flex items-center gap-3 text-white hover:bg-green-600 px-4 py-2 rounded-lg transition duration-150"
      @click="setActiveTab('statistics')"
    >
      <i class="fas fa-chart-bar text-green-400"></i>
      <span class="text-sm font-medium">Statistiques</span>
    </button>

    <!-- Œufs Fertiles -->
    <button
      class="flex items-center gap-3 text-white hover:bg-indigo-600 px-4 py-2 rounded-lg transition duration-150"
      @click="openEggsFertileModal"
    >
      <i class="fas fa-clipboard-list text-indigo-400"></i>
      <span class="text-sm font-medium">Œufs Fertiles</span>
    </button>

    <!-- Œufs Éclos -->
    <button
      class="flex items-center gap-3 text-white hover:bg-orange-600 px-4 py-2 rounded-lg transition duration-150"
      @click="openEggsHatchModal"
    >
      <i class="fas fa-kiwi-bird text-orange-400"></i>
      <span class="text-sm font-medium">Œufs Éclos</span>
    </button>
  </nav>
</div>

  
      <!-- Main content -->
<div class="content flex-1 p-6 bg-white overflow-y-auto">
  <header class="flex justify-between items-center mb-6 border-b pb-4">
    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-700 tracking-wide">
      {{ activeTab.charAt(0).toUpperCase() + activeTab.slice(1) }}
    </h2>

    <!-- Icon Add Button with Tooltip -->
    <button
      @click="openAddModal"
      class="relative group bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full shadow-lg transition duration-300"
    >
      <i class="fas fa-plus text-lg"></i>
      <!-- Tooltip -->
      <div
        class="absolute right-full mr-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10"
      >
        Ajouter une incubation
      </div>
    </button>
  </header>

        <!-- Table -->
<table class="table w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
  <thead class="bg-gray-200">
    <tr>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">N°</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Date d'Incubation</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Nombre d'Œufs Incubés</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Température(°C)</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Humidité(%)</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Date de Mirage</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Date d'Éclosion</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Nombre Œufs Fertiles</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Nombre Œufs Éclos</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Taux de Fertilité (%)</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Taux d'Éclosion (%)</th>
      <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="incubation in paginatedIncubations" :key="incubation.id" :style="getRowStyle(incubation)" class="border-b">
      <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ incubation.id }}</td>
      <td class="px-6 py-4 text-sm text-gray-700">{{ new Date(incubation.date_debut).toLocaleDateString() }}</td>
      <td class="px-6 py-4 text-sm text-gray-700">{{ incubation.nombre_oeufs_incubes }}</td>
      <td class="px-6 py-4 text-sm text-gray-700">{{ incubation.temperature }}</td>
      <td class="px-6 py-4 text-sm text-gray-700">{{ incubation.humidite }}</td>

      <!-- Date de Mirage with conditional styling -->
      <td :class="{
        'text-red-500': new Date(incubation.date_mirage) < new Date(),
        'text-yellow-500': new Date(incubation.date_mirage) >= new Date() && new Date(incubation.date_mirage) < new Date().setDate(new Date().getDate() + 3),
        'text-green-500': new Date(incubation.date_mirage) >= new Date().setDate(new Date().getDate() + 3)
      }" class="px-6 py-4 text-sm">
        {{ new Date(incubation.date_mirage).toLocaleDateString() }}
      </td>

      <!-- Date d'Éclosion with conditional styling -->
      <td :class="{
        'text-red-500': new Date(incubation.date_fin_eclosion) < new Date(),
        'text-yellow-500': new Date(incubation.date_fin_eclosion) >= new Date() && new Date(incubation.date_fin_eclosion) < new Date().setDate(new Date().getDate() + 3),
        'text-green-500': new Date(incubation.date_fin_eclosion) >= new Date().setDate(new Date().getDate() + 3)
      }" class="px-6 py-4 text-sm">
        {{ new Date(incubation.date_fin_eclosion).toLocaleDateString() }}
      </td>

      <!-- Nombre Œufs Fertiles -->
      <td class="px-6 py-4 text-sm text-gray-700">{{ incubation.nombre_oeufs_fertiles }}</td>

      <!-- Nombre Œufs Éclos -->
      <td class="px-6 py-4 text-sm text-gray-700">{{ incubation.nombre_oeufs_fertiles_eclos }}</td>

      <!-- Taux de Fertilité with conditional styling -->
      <td :class="{
        'text-red-500': incubation.taux_fertilite < 50,
        'text-yellow-500': incubation.taux_fertilite >= 50 && incubation.taux_fertilite < 75,
        'text-green-500': incubation.taux_fertilite >= 75
      }" class="px-6 py-4 text-sm">
        {{ incubation.taux_fertilite.toFixed(2) }}
      </td>

      <!-- Taux d'Éclosion with conditional styling -->
      <td :class="{
        'text-red-500': incubation.taux_eclosion < 50,
        'text-yellow-500': incubation.taux_eclosion >= 50 && incubation.taux_eclosion < 75,
        'text-green-500': incubation.taux_eclosion >= 75
      }" class="px-6 py-4 text-sm">
        {{ incubation.taux_eclosion.toFixed(2) }}
      </td>

      <!-- Actions with Icons -->
      <td class="px-6 py-4 flex space-x-2">
        <!-- Edit Button with Icon -->
        <button
          class="bg-yellow-500 text-white p-2 rounded-md hover:bg-yellow-600"
          @click="openEditModal(incubation)"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M17.121 2.879a3 3 0 010 4.242L7 14.364V17h2.636l10.121-10.121a3 3 0 010-4.242l-2.121-2.121a3 3 0 00-4.242 0zM3 5a3 3 0 014.243 0l8.485 8.485-2.828 2.829-8.485-8.485A3 3 0 013 5z" clip-rule="evenodd"/>
          </svg>
        </button>
        
        <!-- Delete Button with Icon -->
        <button
          class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600"
          @click="deleteIncubation(incubation.id)"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M6 2a2 2 0 012-2h4a2 2 0 012 2h3a1 1 0 011 1v1H5V3a1 1 0 011-1h3zm-3 3h12v12a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
          </svg>
        </button>
      </td>
    </tr>
  </tbody>
</table>


<div class="flex justify-center items-center mt-6 text-sm text-gray-700">
  <!-- Bouton Précédent -->
  <button
    @click="previousPage"
    :disabled="pagination.currentPage === 1"
    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full disabled:opacity-50 transition duration-300 ease-in-out transform hover:scale-105"
  >
    <i class="fas fa-arrow-left"></i> Précédent
  </button>

  <!-- Info de la Page -->
  <span class="mx-6 text-lg font-medium text-gray-800">
    Page {{ pagination.currentPage }} sur {{ totalPages }}
  </span>

  <!-- Bouton Suivant -->
  <button
    @click="nextPage"
    :disabled="pagination.currentPage === totalPages"
    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full disabled:opacity-50 transition duration-300 ease-in-out transform hover:scale-105"
  >
    Suivant <i class="fas fa-arrow-right"></i>
  </button>
</div>


  
        <!-- Statistics Section -->
        <section v-if="activeTab === 'statistics'" class="dashboard-section mt-8">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Statistiques des Incubations</h3>
          <p class="text-sm text-gray-600 mb-4">Total des Incubations: {{ totalIncubations }}</p>
          <canvas id="incubationChart" width="400" height="200"></canvas>
        </section>
  
        <!-- Success/Error Messages -->
        <p v-if="successMessage" class="mt-4 text-green-600">{{ successMessage }}</p>
        <p v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</p>
  
        <!-- Modal for Add/Edit -->
<div
  v-if="isModalOpen"
  class="modal-overlay fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50"
  @click="closeModal"
>
  <div
    class="modal-content relative bg-white rounded-xl shadow-xl w-full sm:w-[420px] p-6 sm:p-8"
    @click.stop
  >
    <!-- Modal Title -->
    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">{{ modalTitle }}</h3>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <input
        v-model="newItem.date_debut"
        type="date"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />

      <input
        v-model="newItem.nombre_oeufs_incubes"
        type="number"
        placeholder="Nombre d'Œufs"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
        min="1"
      />

      <input
        v-model="newItem.temperature"
        type="number"
        placeholder="Température (°C)"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />

      <input
        v-model="newItem.humidite"
        type="number"
        placeholder="Humidité (%)"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />

      <!-- Submit button -->
      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2.5 rounded-md font-medium hover:bg-blue-700 transition duration-200"
      >
        {{ modalAction }}
      </button>
    </form>

    <!-- Close button (top right corner) -->
    <button
      class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition"
      @click="closeModal"
      aria-label="Fermer"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</div>

        <!-- Modal for Nombre Œufs Fertiles -->
<div
  v-if="isEggsFertileModalOpen"
  class="modal-overlay fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50"
  @click="closeEggsFertileModal"
>
  <div
    class="modal-content relative bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-full sm:w-[420px]"
    @click.stop
  >
    <!-- Titre -->
    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">
      Modifier Nombre d'Œufs Fertiles
    </h3>

    <!-- Formulaire -->
    <form @submit.prevent="handleEggsFertileSubmit" class="space-y-4">
      <!-- Sélection Incubation -->
      <select
        v-model="selectedIncubationId"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      >
        <option disabled value="">Sélectionner l'Incubation</option>
        <option
          v-for="incubation in incubations"
          :key="incubation.id"
          :value="incubation.id"
        >
          {{ incubation.id }} - {{ new Date(incubation.date_debut).toLocaleDateString('fr-FR') }}
        </option>
      </select>

      <!-- Champ nombre -->
      <input
        v-model="eggsFertileCount"
        type="number"
        placeholder="Nombre d'Œufs Fertiles"
        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
        required
        min="0"
        :disabled="!isDateOfMirageValid(selectedIncubationId)"
      />

      <!-- Message d'erreur -->
      <p
        v-if="!isDateOfMirageValid(selectedIncubationId)"
        class="text-red-500 text-sm"
      >
        Vous ne pouvez entrer les œufs fertiles que le jour de la date de mirage ou après.
      </p>

      <!-- Bouton de soumission -->
      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2.5 mt-2 rounded-md font-medium hover:bg-blue-700 transition duration-200"
        :disabled="!isDateOfMirageValid(selectedIncubationId)"
      >
        Mettre à jour
      </button>
    </form>

    <!-- Bouton de fermeture -->
    <button
      class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition"
      @click="closeEggsFertileModal"
      aria-label="Fermer"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</div>

  
        <!-- Modal for Nombre Œufs Éclos -->
        <div v-if="isEggsHatchModalOpen" class="modal-overlay fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center" @click="closeEggsHatchModal">
          <div class="modal-content bg-white p-8 rounded-lg shadow-lg w-full sm:w-96" @click.stop>
            <h3 class="text-xl font-semibold mb-4">Modifier Nombre d'Œufs Éclos</h3>
            <form @submit.prevent="handleEggsHatchSubmit">
              <div class="space-y-4">
                <select v-model="selectedIncubationId" class="w-full p-3 border rounded-md" required>
                  <option disabled value="">Sélectionner l'Incubation</option>
                  <option v-for="incubation in incubations" :key="incubation.id" :value="incubation.id">
                    {{ incubation.id }} - {{ new Date(incubation.date_debut).toLocaleDateString('fr-FR') }}
                  </option>
                </select>
                <input
                  v-model="eggsHatchCount"
                  type="number"
                  placeholder="Nombre d'Œufs Éclos"
                  class="w-full p-3 border rounded-md"
                  required
                  min="0"
                />
              </div>
              <button type="submit" class="w-full bg-blue-500 text-white py-2 mt-4 rounded-md hover:bg-blue-600">
                Mettre à jour
              </button>
            </form>
            <button class="close-btn absolute top-2 right-2 text-gray-500" @click="closeEggsHatchModal">X</button>
          </div>
        </div>
      </div>
    </div>
  </template>
 <script>
 import axios from 'axios';
 // Assurez-vous que Axios est configuré correctement
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
 axios.defaults.withCredentials = true; // Cela permet d'envoyer les cookies avec les requêtes

 import { Chart, registerables } from 'chart.js';

// 📌 Enregistrer tous les modules nécessaires de chart.js
Chart.register(...registerables);

 export default {
   data() {
     return {
       activeTab: 'incubations',
       incubations: [],
       isModalOpen: false,
       isEggsFertileModalOpen: false,
       isEggsHatchModalOpen: false,
       eggsFertileCount: 0,
       eggsHatchCount: 0,
       selectedIncubationId: null,
       pagination: {
       currentPage: 1,
       perPage: 3, // Tu peux changer à 10, 20 selon ce que tu veux afficher par page
      },

       newItem: {},
       modalTitle: '',
       modalAction: '',
       successMessage: '',
       errorMessage: '',
       loading: false,
     };
   },
   created() {
     this.fetchIncubations();
   },
   computed: {
  paginatedIncubations() {
    const start = (this.pagination.currentPage - 1) * this.pagination.perPage;
    const end = start + this.pagination.perPage;
    return this.incubations.slice(start, end);
  },

  totalPages() {
    return Math.ceil(this.incubations.length / this.pagination.perPage);
  }
},

   methods: {
     setActiveTab(tab) {
       this.activeTab = tab;
     },
     async fetchIncubations() {
       const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
       this.loading = true;
       try {
         console.log(`Tentative de récupération des incubations depuis : ${apiBaseUrl}/incubations`);
         
         const response = await axios.get(`${apiBaseUrl}/incubations`);
         
         
         console.log('Réponse de l\'API:', response);
         
         this.incubations = response.data;
         
         console.log('Incubations récupérées:', this.incubations);
       } catch (error) {
         console.error('Erreur lors de la récupération des incubations', error);
         
         if (error.response) {
           this.errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || error.response.statusText}`;
         } else if (error.request) {
           this.errorMessage = 'Impossible de recevoir de réponse du serveur.';
         } else {
           this.errorMessage = 'Une erreur inconnue est survenue.';
         }
       } finally {
         this.loading = false;
       }
     },

     goToPage(page) {
  if (page >= 1 && page <= this.totalPages) {
    this.pagination.currentPage = page;
  }
},
nextPage() {
  this.goToPage(this.pagination.currentPage + 1);
},
previousPage() {
  this.goToPage(this.pagination.currentPage - 1);
},


     // Vérifie si la date de mirage est passée ou non
  isDateOfMirageValid(selectedIncubationId) {
    const selectedIncubation = this.incubations.find(incubation => incubation.id === selectedIncubationId);
    if (selectedIncubation) {
      const mirageDate = new Date(selectedIncubation.date_mirage);
      return new Date() >= mirageDate;  // La date actuelle est-elle après ou égale à la date de mirage ?
    }
    return false;
  },

 
     getRowStyle(incubation) {
   let rowStyle = {};
 
   if (incubation.nombre_oeufs_fertiles && incubation.nombre_oeufs_fertiles_eclos) {
     const hatchPercentage = (incubation.nombre_oeufs_fertiles_eclos / incubation.nombre_oeufs_fertiles) * 100;
 
     if (hatchPercentage > 75) {
       rowStyle = { backgroundColor: '#c8e6c9' }; // Vert
     } else if (hatchPercentage < 25) {
       rowStyle = { backgroundColor: '#ffcdd2' }; // Rouge
     } else {
       rowStyle = { backgroundColor: '#fff' }; // Blanc
     }
   }
 
   return rowStyle;
 },
 
 
 
 
     openAddModal() {
       this.newItem = {};
       this.modalTitle = 'Ajouter une Incubation';
       this.modalAction = 'Ajouter';
       this.isModalOpen = true;
     },
     openEditModal(item) {
       this.newItem = { ...item };
       this.modalTitle = 'Modifier une Incubation';
       this.modalAction = 'Mettre à jour';
       this.isModalOpen = true;
     },
     closeModal() {
       this.isModalOpen = false;
     },
     openEggsFertileModal() {
       this.isEggsFertileModalOpen = true;
     },
     closeEggsFertileModal() {
       this.isEggsFertileModalOpen = false;
     },
     openEggsHatchModal() {
       this.isEggsHatchModalOpen = true;
     },
     closeEggsHatchModal() {
       this.isEggsHatchModalOpen = false;
     },
     async handleEggsFertileSubmit() {
       console.log('Incubation ID:', this.selectedIncubationId);
       console.log('Nombre Œufs Fertiles:', this.eggsFertileCount);
       this.closeEggsFertileModal();
     },
     async handleEggsHatchSubmit() {
       console.log('Incubation ID:', this.selectedIncubationId);
       console.log('Nombre Œufs Éclos:', this.eggsHatchCount);
       this.closeEggsHatchModal();
     },
     async handleSubmit() {
       if (this.modalAction === 'Ajouter') {
         await this.addItem();
       } else {
         await this.updateItem();
       }
     },
     async addItem() {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  try {
    console.log("Données à ajouter:", this.newItem);
    const response = await axios.post(`${apiBaseUrl}/incubations`, this.newItem);
    this.successMessage = 'Incubation ajoutée avec succès!';
    this.errorMessage = '';
    this.closeModal();
    this.fetchIncubations();
  } catch (error) {
    console.error('Erreur lors de l\'ajout de l\'incubation:', error);

    // Affiche les détails de l'erreur
    if (error.response) {
      console.error('Réponse de l\'API:', error.response);
      this.errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || error.response.statusText}`;
    } else if (error.request) {
      console.error('Requête envoyée mais pas de réponse reçue:', error.request);
      this.errorMessage = 'Impossible de recevoir de réponse du serveur.';
    } else {
      console.error('Erreur inconnue:', error.message);
      this.errorMessage = 'Une erreur inconnue est survenue.';
    }
  }
},

 
     async handleEggsFertileSubmit() {
   // Vérifie si le nombre d'œufs fertiles est valide
   if (!this.selectedIncubationId || this.eggsFertileCount < 0) {
     this.errorMessage = 'Veuillez sélectionner une incubation et entrer un nombre valide d\'œufs fertiles.';
     return;
   }
 
   // Si le nombre d'œufs éclos n'est pas défini, on le met à 0 par défaut
   const eggsHatchCount = this.eggsHatchCount >= 0 ? this.eggsHatchCount : 0;  // Valeur par défaut si non valide
 
   const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
   try {
     console.log('Incubation ID:', this.selectedIncubationId);
     console.log('Nombre Œufs Fertiles:', this.eggsFertileCount);
     console.log('Nombre Œufs Éclos:', eggsHatchCount); // Affiche le nombre d'œufs éclos
 
     // Envoi de la requête PUT pour mettre à jour l'incubation avec les données
     const response = await axios.patch(`${apiBaseUrl}/incubations/${this.selectedIncubationId}`, {
       nombre_oeufs_fertiles: this.eggsFertileCount,
       nombre_oeufs_fertiles_eclos: eggsHatchCount,  // Utilise la valeur par défaut de 0 si nécessaire
     });
 
     console.log('Réponse de l\'API:', response);
 
     // Message de succès
     this.successMessage = 'Nombre d\'œufs fertiles  mis à jour avec succès!';
     this.errorMessage = '';
     this.closeEggsFertileModal();
     this.fetchIncubations();  // Actualiser la liste des incubations après mise à jour
   } catch (error) {
     // Affichage détaillé de l'erreur dans la console
     console.error('Erreur lors de la mise à jour des œufs fertiles', error);
 
     if (error.response) {
       this.errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || error.response.statusText}`;
     } else if (error.request) {
       this.errorMessage = 'Impossible de recevoir de réponse du serveur.';
     } else {
       this.errorMessage = 'Une erreur inconnue est survenue.';
     }
   }
 },
 
 
 
 async handleEggsHatchSubmit() {
   if (!this.selectedIncubationId || this.eggsHatchCount < 0) {
     this.errorMessage = 'Veuillez sélectionner une incubation et entrer un nombre valide d\'œufs éclos.';
     return;
   }
 
   const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
   try {
     console.log('Incubation ID:', this.selectedIncubationId);
     console.log('Nombre Œufs Éclos:', this.eggsHatchCount);
 
     // Envoi de la requête PUT pour mettre à jour l'incubation avec le nombre d'œufs éclos
     const response = await axios.put(`${apiBaseUrl}/incubations/${this.selectedIncubationId}`, {
       nombre_oeufs_fertiles_eclos: this.eggsHatchCount,
     });
 
     // Message de succès
     this.successMessage = 'Nombre d\'œufs éclos mis à jour avec succès!';
     this.errorMessage = '';
     this.closeEggsHatchModal();
     this.fetchIncubations();  // Actualiser la liste des incubations
   } catch (error) {
     console.error('Erreur lors de la mise à jour des œufs éclos', error);
     this.errorMessage = 'Une erreur s\'est produite lors de la mise à jour des œufs éclos.';
   }
 },
 
 
 
     async updateItem() {
       const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
       try {
         const response = await axios.put(`${apiBaseUrl}/incubations/${this.newItem.id}`, this.newItem);
         this.successMessage = 'Incubation mise à jour avec succès!';
         this.errorMessage = '';
         this.closeModal();
         this.fetchIncubations();
       } catch (error) {
         console.error('Erreur lors de la mise à jour de l\'incubation', error);
         this.errorMessage = 'Une erreur s\'est produite lors de la mise à jour.';
       }
     },
 
 
 
     async deleteIncubation(id) {
       const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
       try {
         await axios.delete(`${apiBaseUrl}/incubations/${id}`);
         this.fetchIncubations();
       } catch (error) {
         console.error('Erreur lors de la suppression de l\'incubation', error);
       }
     },
   },
 
 };
 </script> 