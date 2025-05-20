<template>
    <div id="app" class="flex min-h-screen bg-gray-100">
  <!-- Sidebar -->
  <div id="sidebar" class="w-52 bg-blue-900 text-white flex-shrink-0">
    <div class="logo-container px-4 py-4">
      <h3 class="text-xl font-bold">Suivi des Bandes</h3>
    </div>
    <nav class="mt-6 space-y-2">
      <button
        class="nav-link w-full text-left px-4 py-3 text-white hover:bg-blue-800 transition duration-200"
        @click="setActiveTab('bands')"
      >
        <i class="fas fa-users text-yellow-400 mr-3"></i> Bandes
      </button>
      <button
        class="nav-link w-full text-left px-4 py-3 text-white hover:bg-blue-800 transition duration-200"
        @click="setActiveTab('mortalities')"
      >
        <i class="fas fa-heart text-red-400 mr-3"></i> Mortalités
      </button>
      <button
        class="nav-link w-full text-left px-4 py-3 text-white hover:bg-blue-800 transition duration-200"
        @click="setActiveTab('feedConsumptions')"
      >
        <i class="fas fa-utensils text-green-400 mr-3"></i> Consommations
      </button>
      <button
        class="nav-link w-full text-left px-4 py-3 text-white hover:bg-blue-800 transition duration-200"
        @click="setActiveTab('totalMortalities')"
      >
      </button>
    </nav>
  </div>

  
    <!-- Main content -->
<div class="flex-1 p-6 bg-gray-50">
  <header class="flex items-center gap-4 mb-6 flex-wrap">
    <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700 capitalize">
      {{ activeTab.charAt(0).toUpperCase() + activeTab.slice(1) }}
    </h2>

    <button
      @click="openAddModal"
      class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium px-5 py-2.5 rounded-full shadow-md hover:from-blue-600 hover:to-indigo-700 transition duration-300"
    >
      <i class="fas fa-plus-circle text-white text-lg"></i>
      <span class="hidden sm:inline">Ajouter</span>
    </button>
  </header>

  
  <section v-if="activeTab === 'bands'" class="dashboard-section px-2 sm:px-4">
  <div class="card bg-white p-4 sm:p-6 rounded-lg shadow-md overflow-x-auto">
    <h3 class="text-2xl font-semibold mb-4">Liste des Bandes</h3>
    <table class="table-auto w-full text-left text-sm">
      <thead class="bg-gray-200 text-gray-700">
        <tr>
          <th class="px-4 py-2">Code</th>
          <th class="px-4 py-2">Type</th>
          <th class="px-4 py-2">Compte Initial</th>
          <th class="px-4 py-2">Compte Actuel</th>
          <th class="px-4 py-2">Date de Début</th>
          <th class="px-4 py-2">Date d'Abattage</th>
          <th class="px-4 py-2">Mortalité Totale</th>
          <th class="px-4 py-2">Quantité Totale</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="band in getPaginatedData().items" :key="band.id" class="border-b hover:bg-gray-50">
          <td class="px-4 py-2">{{ band.code }}</td>
          <td class="px-4 py-2">{{ band.type }}</td>
          <td class="px-4 py-2">{{ band.compte_initial }}</td>
          <td class="px-4 py-2">{{ band.compte_actuel }}</td>
          <td class="px-4 py-2">{{ new Date(band.date_debut).toLocaleDateString() }}</td>
          <td class="px-4 py-2 text-red-600">{{ new Date(band.date_fin).toLocaleDateString() }}</td>
          <td class="px-4 py-2">{{ band.mortalite_totale }}</td>
          <td class="px-4 py-2">{{ band.quantite_aliment_totale }}</td>
          <td class="px-4 py-2 flex gap-3 justify-center items-center text-lg">
            <button
              class="text-yellow-500 hover:text-yellow-600 transition"
              @click="openEditModal(band)"
              title="Modifier"
            >
              <i class="fas fa-edit"></i>
            </button>
            <button
              class="text-red-600 hover:text-red-700 transition"
              @click="deleteBand(band.id)"
              title="Supprimer"
            >
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
  <!-- Bouton Précédent -->
  <button
    @click="currentPage--"
    :disabled="currentPage === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages -->
  <button
    v-for="page in getPaginatedData().totalPages"
    :key="page"
    @click="currentPage = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': currentPage === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="currentPage++"
    :disabled="currentPage === getPaginatedData().totalPages"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>


  <p v-if="loading" class="text-center text-gray-500 mt-4">Chargement des données...</p>
  <p v-if="errorMessage" class="text-red-500 text-center mt-4">{{ errorMessage }}</p>
</section>




<section v-if="activeTab === 'mortalities'" class="dashboard-section">
  <div class="card bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-2xl font-semibold mb-4">Mortalités Journalières</h3>
    <table class="table-auto w-full text-left">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">Bande</th>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Mortalité</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="mortality in getPaginatedData().items" :key="mortality.id" class="border-b">
          <td class="px-4 py-2">{{ getBandCode(mortality.band_id) }}</td>
          <td class="px-4 py-2">
            {{ new Date(mortality.date).toLocaleDateString('fr-FR') }}
          </td> <!-- Format de date appliqué ici -->
          <td class="px-4 py-2">{{ mortality.mortalite_journaliere }}</td>
          <td class="px-4 py-2 text-center">
            <div class="flex items-center justify-center gap-4">
              <i class="fas fa-edit text-yellow-500 hover:text-yellow-600 cursor-pointer" @click="openEditModal(mortality)" title="Modifier"></i>
              <i class="fas fa-trash-alt text-red-600 hover:text-red-700 cursor-pointer" @click="deleteMortality(mortality.id, mortality.band_id)" title="Supprimer"></i>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
  <!-- Bouton Précédent -->
  <button
    @click="currentPage--"
    :disabled="currentPage === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages -->
  <button
    v-for="page in getPaginatedData().totalPages"
    :key="page"
    @click="currentPage = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': currentPage === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="currentPage++"
    :disabled="currentPage === getPaginatedData().totalPages"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>

  <p v-if="loading" class="text-center text-gray-500 mt-4">Chargement des données...</p>
  <p v-if="errorMessage" class="text-red-500 text-center mt-4">{{ errorMessage }}</p>
</section>



<section v-if="activeTab === 'feedConsumptions'" class="dashboard-section">
  <div class="card bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-2xl font-semibold mb-4">Consommations d'Aliment</h3>
    <table class="table-auto w-full text-left">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">Bande</th>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Quantité (kg)</th> <!-- Ajout de kg ici -->
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="consumption in getPaginatedData().items" :key="consumption.id" class="border-b">
          <td class="px-4 py-2">{{ getBandCode(consumption.band_id) }}</td>
          <td class="px-4 py-2">
            {{ new Date(consumption.date).toLocaleDateString('fr-FR') }}
          </td>
          <td class="px-4 py-2">{{ consumption.quantite_aliment }}</td>
          <td class="px-4 py-2 text-center">
            <div class="flex items-center justify-center gap-4">
              <i class="fas fa-edit text-yellow-500 hover:text-yellow-600 cursor-pointer" @click="openEditModal(consumption)" title="Modifier"></i>
              <i class="fas fa-trash-alt text-red-600 hover:text-red-700 cursor-pointer" @click="deleteFeedConsumption(consumption.id, consumption.band_id)" title="Supprimer"></i>

            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
  <!-- Bouton Précédent -->
  <button
    @click="currentPage--"
    :disabled="currentPage === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages -->
  <button
    v-for="page in getPaginatedData().totalPages"
    :key="page"
    @click="currentPage = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': currentPage === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="currentPage++"
    :disabled="currentPage === getPaginatedData().totalPages"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>


  <p v-if="loading" class="text-center text-gray-500 mt-4">Chargement des données...</p>
  <p v-if="errorMessage" class="text-red-500 text-center mt-4">{{ errorMessage }}</p>
</section>

  
        <!-- Modal for Add/Edit -->
<div v-if="isModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 px-4 py-6" @click="closeModal">
  <div
    class="modal-content bg-white w-full max-w-lg mx-auto p-6 rounded-lg shadow-xl overflow-y-auto max-h-screen relative"
    @click.stop
  >
    <h3 class="text-2xl font-semibold mb-4 text-gray-800">{{ modalTitle }}</h3>
    <form @submit.prevent="handleSubmit">
      <!-- Dynamic form based on modal type -->
      <div v-if="activeTab === 'bands'">
        <input v-model="newItem.type" type="text" placeholder="Type" class="w-full p-2 border rounded-md mb-4" required />
        <input v-model="newItem.compte_initial" type="number" placeholder="Compte Initial" class="w-full p-2 border rounded-md mb-4" required />
        <input v-model="newItem.compte_actuel" type="number" placeholder="Compte Actuel" class="w-full p-2 border rounded-md mb-4" required />
        <input v-model="newItem.date_debut" type="date" class="w-full p-2 border rounded-md mb-4" required />
      </div>

      <div v-if="activeTab === 'mortalities'">
        <label class="block text-gray-700 mb-1">Bande</label>
        <select v-model="newItem.band_id" required class="w-full p-2 border rounded-md mb-4">
          <option v-for="band in bands" :key="band.id" :value="band.id">
            {{ band.code }} - {{ band.type }}
          </option>
        </select>
        <input v-model="newItem.date" type="date" class="w-full p-2 border rounded-md mb-4" required />
        <input v-model="newItem.mortalite_journaliere" type="number" placeholder="Mortalité" class="w-full p-2 border rounded-md mb-4" required />
      </div>

      <div v-if="activeTab === 'feedConsumptions'">
        <label class="block text-gray-700 mb-1">Bande</label>
        <select v-model="newItem.band_id" required class="w-full p-2 border rounded-md mb-4">
          <option v-for="band in bands" :key="band.id" :value="band.id">
            {{ band.code }} - {{ band.type }}
          </option>
        </select>
        <input v-model="newItem.date" type="date" class="w-full p-2 border rounded-md mb-4" required />
        <input v-model="newItem.quantite_aliment" type="number" placeholder="Quantité" class="w-full p-2 border rounded-md mb-4" required />
      </div>

      <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
          {{ modalAction }}
        </button>
      </div>
    </form>
    <button class="absolute top-2 right-2 text-gray-600 text-2xl font-bold hover:text-gray-800" @click="closeModal">×</button>
  </div>
</div>

      </div>
    </div>
  </template>
  <script>
  import axios from 'axios';
  // CSRF + Cookies
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;
  import { Chart } from 'chart.js';
  
  export default {
    data() {
      return {
        activeTab: 'bands',
        bands: [],
        mortalites: [],
        feedConsumptions: [],
        isModalOpen: false,
        currentPage: 1,
        itemsPerPage: 3,
        newItem: {},
        modalTitle: '',
        modalAction: '',
        successMessage: '',
        errorMessage: '',
        loading: false,
      };
    },
    created() {
      this.fetchBands();
      this.fetchMortalities();
      this.fetchFeedConsumptions();
    },
    methods: {
      setActiveTab(tab) {
        this.activeTab = tab;
        if (tab === 'mortalities') {
          this.fetchMortalities(this.bands[0]?.id); // Récupère les mortalités de la première bande par défaut
        } else if (tab === 'feedConsumptions') {
          this.fetchFeedConsumptions(this.bands[0]?.id); // Récupère les consommations de la première bande par défaut
        }
      },
      async fetchBands() {
    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
    this.loading = true;
    try {
      const response = await axios.get(`${apiBaseUrl}/bands`);
      this.bands = response.data;
    } catch (error) {
      console.error('Erreur lors de la récupération des bandes', error);
      if (error.response) {
        console.error('Détails de l\'erreur :', error.response.data);
        console.error('Status :', error.response.status);
      }
      this.errorMessage = 'Impossible de récupérer les données des bandes.';
    } finally {
      this.loading = false;
    }
  },


  getPaginatedData() {
    let fullData = [];

    switch (this.activeTab) {
      case 'bands':
        fullData = this.bands;
        break;
      case 'mortalities':
        fullData = this.mortalites;
        break;
      case 'feedConsumptions':
        fullData = this.feedConsumptions;
        break;
    }

    const totalPages = Math.ceil(fullData.length / this.itemsPerPage);
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;
    const items = fullData.slice(start, end);

    return { items, totalPages };
  },
         // Autres méthodes...
    getBandCode(bandId) {
      const band = this.bands.find(b => b.id === bandId);
      return band ? band.code : 'Inconnu'; // Retourne le code ou 'Inconnu' si la bande n'est pas trouvée
    },
  
    
      async fetchMortalities(bandId) {
        const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
        if (!bandId) return;
        this.loading = true;
        try {
          const response = await axios.get(`${apiBaseUrl}/bands/${bandId}/mortalities`);
          this.mortalites = response.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des mortalités', error);
          this.errorMessage = 'Impossible de récupérer les mortalités.';
        } finally {
          this.loading = false;
        }
      },
      async fetchFeedConsumptions(bandId) {
        const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
        if (!bandId) return;
        this.loading = true;
        try {
          const response = await axios.get(`${apiBaseUrl}/bands/${bandId}/feed-consumptions`);
          this.feedConsumptions = response.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des consommations d\'aliment', error);
          this.errorMessage = 'Impossible de récupérer les consommations d\'aliment.';
        } finally {
          this.loading = false;
        }
      },
      openAddModal() {
        this.newItem = {};
        this.modalTitle = `Ajouter ${this.activeTab === 'bands' ? 'une Bande' : this.activeTab === 'mortalities' ? 'une Mortalité' : 'une Consommation d\'aliment'}`;
        this.modalAction = 'Ajouter';
        this.isModalOpen = true;
      },
      openEditModal(item) {
        this.newItem = { ...item };
        this.modalTitle = `Modifier ${this.activeTab === 'bands' ? 'une Bande' : this.activeTab === 'mortalities' ? 'une Mortalité' : 'une Consommation d\'aliment'}`;
        this.modalAction = 'Mettre à jour';
        this.isModalOpen = true;
      },
      closeModal() {
        this.isModalOpen = false;
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
    const url = this.activeTab === 'bands' ? `${apiBaseUrl}/bands` : 
                this.activeTab === 'mortalities' ? `${apiBaseUrl}/bands/${this.newItem.band_id}/mortalities` :
                `${apiBaseUrl}/bands/${this.newItem.band_id}/feed-consumptions`;

    const response = await axios.post(url, this.newItem);

    this.successMessage = `${this.activeTab.charAt(0).toUpperCase() + this.activeTab.slice(1)} ajouté avec succès!`;
    this.errorMessage = '';
    this.closeModal();

    // Met à jour la vue selon le type d'élément ajouté
    this.fetchBands();

    if (this.activeTab === 'mortalities') {
      this.fetchMortalities(this.newItem.band_id);
    } else if (this.activeTab === 'feedConsumptions') {
      this.fetchFeedConsumptions(this.newItem.band_id);
    }

  } catch (error) {
    console.error(`Erreur lors de l'ajout de ${this.activeTab}`, error);
    this.errorMessage = 'Une erreur s\'est produite lors de l\'enregistrement.';
    this.successMessage = '';
  }
},


async updateItem() {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  try {
    const url = this.activeTab === 'bands' ? `${apiBaseUrl}/bands/${this.newItem.id}` : 
                this.activeTab === 'mortalities' ? `${apiBaseUrl}/bands/${this.newItem.band_id}/mortalities/${this.newItem.id}` :
                `${apiBaseUrl}/bands/${this.newItem.band_id}/feed-consumptions/${this.newItem.id}`;

    const response = await axios.put(url, this.newItem);

    this.successMessage = `${this.activeTab.charAt(0).toUpperCase() + this.activeTab.slice(1)} mis à jour avec succès!`;
    this.errorMessage = '';
    this.closeModal();

    this.fetchBands();

    if (this.activeTab === 'mortalities') {
      this.fetchMortalities(this.newItem.band_id);
    } else if (this.activeTab === 'feedConsumptions') {
      this.fetchFeedConsumptions(this.newItem.band_id);
    }

  } catch (error) {
    console.error(`Erreur lors de la mise à jour de ${this.activeTab}`, error);
    this.errorMessage = 'Une erreur s\'est produite lors de la mise à jour.';
    this.successMessage = '';
  }
},

      async deleteBand(id) {
        const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
        try {
          await axios.delete(`${apiBaseUrl}/bands/${id}`);
          this.fetchBands();
        } catch (error) {
          console.error('Erreur lors de la suppression de la bande', error);
        }
      },
     
  
      async deleteMortality(id, bandId) {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  try {
    await axios.delete(`${apiBaseUrl}/bands/${bandId}/mortalities/${id}`);
    this.fetchMortalities(bandId);
    this.successMessage = 'Mortalité supprimée avec succès.';
  } catch (error) {
    console.error('Erreur lors de la suppression de la mortalité', error);
  }
},


     



async deleteFeedConsumption(id, bandId) {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  try {
    await axios.delete(`${apiBaseUrl}/bands/${bandId}/feed-consumptions/${id}`);
    this.successMessage = "Consommation supprimée avec succès.";
    this.fetchFeedConsumptions(bandId);
  } catch (error) {
    console.error("Erreur lors de la suppression de la consommation", error);
    this.errorMessage = "Une erreur s'est produite lors de la suppression.";
  }
}


    }
  };
  </script> 