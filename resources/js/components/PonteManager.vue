<template>
    <div class="dashboard-layout min-h-screen flex">
  
      <!-- Sidebar -->
<div class="sidebar w-1/5 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-700 text-white p-4 hidden md:block shadow-xl">
  <!-- Header -->
  <div class="sidebar-header mb-8 flex items-center space-x-2">
    <i class="fas fa-egg text-yellow-400 text-2xl"></i>
    <h2 class="text-lg font-semibold tracking-wide text-white">Gestion des Œufs</h2>
  </div>

  <!-- Navigation -->
  <ul class="sidebar-nav space-y-3">
    <!-- Liste des Pontes -->
    <li
      @click="setActiveTab('ponte')"
      :class="[
        'cursor-pointer px-3 py-2 rounded-lg flex items-center transition duration-200',
        activeTab === 'ponte' ? 'bg-yellow-500 text-gray-900' : 'hover:bg-yellow-400 hover:text-gray-900'
      ]"
    >
      <i class="fas fa-list-ul mr-3 text-sm"></i>
      <span class="text-sm font-medium">Liste des Pontes</span>
    </li>

    <!-- Rapport de Pontes -->
    <li
      @click="setActiveTab('report')"
      :class="[
        'cursor-pointer px-3 py-2 rounded-lg flex items-center transition duration-200',
        activeTab === 'report' ? 'bg-pink-500 text-white' : 'hover:bg-pink-400 hover:text-white'
      ]"
    >
      <i class="fas fa-chart-line mr-3 text-sm"></i>
      <span class="text-sm font-medium">Rapport de Pontes</span>
    </li>

    <!-- Vue d'Ensemble -->
    <li
      @click="setActiveTab('overview')"
      :class="[
        'cursor-pointer px-3 py-2 rounded-lg flex items-center transition duration-200',
        activeTab === 'overview' ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-400 hover:text-white'
      ]"
    >
      <i class="fas fa-eye mr-3 text-sm"></i>
      <span class="text-sm font-medium">Vue d’Ensemble</span>
    </li>
  </ul>
</div>

  
      <!-- Main Content -->
      <div class="main-content flex-1 p-6 bg-gray-50">
  
      <!-- Header -->
<div class="main-header mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
  <!-- Titre principal -->
  <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
    Gestion des Pontes - Lot {{ currentLot.nom }} (ID: {{ currentLot.id }})
  </h1>

  <!-- Actions -->
  <div class="main-actions flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
    
    <!-- Sélecteur de lot -->
    <div class="lot-selector flex items-center space-x-2">
      <label for="lotSelect" class="text-gray-700 font-medium">Sélectionner un Lot :</label>
      <select
        id="lotSelect"
        v-model="lotId"
        @change="onLotChange"
        class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
      >
        <option v-for="lot in lots" :key="lot.id" :value="lot.id">{{ lot.nom }}</option>
      </select>
    </div>

    <!-- Bouton Ajouter une Ponte -->
    <button
      @click="openPonteModal"
      class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-md shadow-md transition duration-200 flex items-center space-x-2"
    >
      <i class="fas fa-plus-circle text-white"></i>
      <span>Ajouter une Ponte</span>
    </button>
  </div>
</div>

  
        <!-- Ponte Table -->
<div v-if="activeTab === 'ponte'" class="table-wrapper overflow-x-auto bg-white p-4 rounded-lg shadow-md">
  <table class="min-w-full table-auto text-sm text-gray-800">
    <thead>
      <tr class="bg-gray-100 text-left text-gray-700 uppercase tracking-wider text-xs">
        <th class="px-4 py-3 rounded-tl-lg">Date de Ponte</th>
        <th class="px-4 py-3">Œufs</th>
        <th class="px-4 py-3">Taux de Ponte</th>
        <th class="px-4 py-3 text-center rounded-tr-lg">Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Aucune donnée -->
      <tr v-if="ponteRecords.length === 0">
        <td colspan="4" class="text-center py-4 text-gray-500 italic">Aucune ponte trouvée pour ce lot.</td>
      </tr>

      <!-- Données de ponte -->
      <tr
        v-for="ponte in ponteRecords"
        :key="ponte.id"
        :class="[
          'border-b transition-colors duration-150',
          ponte.taux_ponte < 50 ? 'bg-red-100' : 'hover:bg-gray-50'
        ]"
      >
        <td class="px-4 py-3">{{ new Date(ponte.date_ponte).toLocaleDateString() }}</td>
        <td class="px-4 py-3">{{ ponte.nombre_oeufs }}</td>
        <td class="px-4 py-3 font-semibold">
          {{ ponte.taux_ponte || 0 }} %
        </td>
        <td class="px-4 py-3 text-center">
          <div class="flex justify-center space-x-3">
            <button
              @click="editPonte(ponte)"
              class="text-yellow-500 hover:text-yellow-600 transition"
              title="Modifier"
            >
              <i class="fas fa-edit text-base"></i>
            </button>
            <button
              @click="deletePonte(ponte.id)"
              class="text-red-500 hover:text-red-600 transition"
              title="Supprimer"
            >
              <i class="fas fa-trash-alt text-base"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>

        <!-- Rapport Section -->
        <div v-if="activeTab === 'report'" class="report-section">
          <h3 class="text-xl font-semibold mb-4">Rapport de Pontes</h3>
          <div class="report-content">
            <table class="table min-w-full bg-white rounded-lg shadow-md">
              <thead>
                <tr class="bg-gray-200">
                  <th class="px-6 py-3 text-left">Date de Ponte</th>
                  <th class="px-6 py-3 text-left">Total Œufs Pondus</th>
                  <th class="px-6 py-3 text-left">Moyenne du Taux de Ponte</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="rapportData.length === 0">
                  <td colspan="3" class="text-center py-4 text-gray-500">Aucune donnée pour ce rapport.</td>
                </tr>
                <tr v-for="report in rapportData" :key="report.date">
                  <td class="px-6 py-4">{{ new Date(report.date).toLocaleDateString() }}</td>
                  <td class="px-6 py-4">{{ report.total_oeufs }}</td>
                  <td class="px-6 py-4">{{ report.moyenne_taux_ponte }} %</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
       <!-- Pagination pour la section Ponte -->
<div
  v-if="activeTab === 'ponte'"
  class="pagination mt-6 flex justify-center items-center space-x-6 text-sm"
>
  <!-- Bouton Précédent -->
  <button
    @click="prevPage('ponte')"
    :disabled="currentPagePonte === 1"
    class="px-4 py-2 rounded-md font-medium shadow-sm transition duration-200"
    :class="[
      currentPagePonte === 1
        ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
        : 'bg-blue-600 text-white hover:bg-blue-700'
    ]"
  >
    <i class="fas fa-chevron-left mr-2"></i> Précédent
  </button>

  <!-- Indicateur de page -->
  <span class="text-gray-700 font-medium">
    Page {{ currentPagePonte }} / {{ totalPagesPonte }}
  </span>

  <!-- Bouton Suivant -->
  <button
    @click="nextPage('ponte')"
    :disabled="currentPagePonte === totalPagesPonte"
    class="px-4 py-2 rounded-md font-medium shadow-sm transition duration-200"
    :class="[
      currentPagePonte === totalPagesPonte
        ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
        : 'bg-blue-600 text-white hover:bg-blue-700'
    ]"
  >
    Suivant <i class="fas fa-chevron-right ml-2"></i>
  </button>
</div>

        <!-- Pagination pour la section Rapport -->
        <div v-if="activeTab === 'report'" class="pagination mt-6 flex justify-center items-center space-x-4">
          <button @click="prevPage('report')" :disabled="currentPageReport === 1" class="btn btn-secondary" :class="{ 'cursor-not-allowed opacity-50': currentPageReport === 1 }">Précédent</button>
          <span>Page {{ currentPageReport }} / {{ totalPagesReport }}</span>
          <button @click="nextPage('report')" :disabled="currentPageReport === totalPagesReport" class="btn btn-secondary" :class="{ 'cursor-not-allowed opacity-50': currentPageReport === totalPagesReport }">Suivant</button>
        </div>
  
        <!-- Pagination pour la section Vue d'Ensemble -->
        <div v-if="activeTab === 'overview'" class="pagination mt-6 flex justify-center items-center space-x-4">
          <button @click="prevPage('overview')" :disabled="currentPageOverview === 1" class="btn btn-secondary" :class="{ 'cursor-not-allowed opacity-50': currentPageOverview === 1 }">Précédent</button>
          <span>Page {{ currentPageOverview }} / {{ totalPagesOverview }}</span>
          <button @click="nextPage('overview')" :disabled="currentPageOverview === totalPagesOverview" class="btn btn-secondary" :class="{ 'cursor-not-allowed opacity-50': currentPageOverview === totalPagesOverview }">Suivant</button>
        </div>
  
        <!-- Vue d'Ensemble du Lot Section -->
        <div v-if="activeTab === 'overview'" class="overview-section mt-6">
          <h3 class="text-xl font-semibold mb-4">Vue d'Ensemble du Lot</h3>
          <div class="overview-content">
            <table class="table min-w-full bg-white rounded-lg shadow-md">
              <thead>
                <tr class="bg-gray-200">
                  <th class="px-6 py-3 text-left">Nom du Lot</th>
                  <th class="px-6 py-3 text-left">Nombre de Males</th>
                  <th class="px-6 py-3 text-left">Nombre de Femelles</th>
                  <th class="px-6 py-3 text-left">Total d'Œufs Pondus</th>
                  <th class="px-6 py-3 text-left">Taux Ponte Moyen (%)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="overviewData.length === 0">
                  <td colspan="5" class="text-center py-4 text-gray-500">Aucune donnée pour ce lot.</td>
                </tr>
                <tr v-for="overview in overviewData" :key="overview.lotId">
                  <td class="px-6 py-4">{{ overview.lotNom }}</td>
                  <td class="px-6 py-4">{{ overview.nombre_males }}</td>
                  <td class="px-6 py-4">{{ overview.nombre_femelles }}</td>
                  <td class="px-6 py-4">{{ overview.total_oeufs }}</td>
                  <td class="px-6 py-4">{{ overview.moyenne_taux_ponte }}%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
      </div>
  
      <!-- Modal -->
      <div v-if="showModal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" @click.self="closeModal">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
          <h3 class="text-xl font-semibold mb-4">{{ isEditing ? 'Modifier' : 'Ajouter' }} une Ponte</h3>
          <div class="form-container">
            <form @submit.prevent="submitPonte">
              <div class="form-group mb-4">
                <label for="date_ponte" class="block text-gray-700">Date de la Ponte</label>
                <input type="date" id="date_ponte" class="form-control w-full px-4 py-2 border rounded-md" v-model="ponte.date_ponte" required />
              </div>
              <div class="form-group mb-4">
                <label for="nombre_oeufs" class="block text-gray-700">Nombre d'œufs</label>
                <input type="number" id="nombre_oeufs" class="form-control w-full px-4 py-2 border rounded-md" v-model="ponte.nombre_oeufs" required min="0" />
              </div>
              <button type="submit" class="btn btn-primary w-full py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                {{ isEditing ? 'Mettre à jour' : 'Ajouter' }} la Ponte
              </button>
              <button type="button" class="btn btn-secondary w-full py-2 mt-4 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" @click="closeModal">
                Annuler
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  
  <script>
    import axios from 'axios';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
 axios.defaults.withCredentials = true; // Cela permet d'envoyer les cookies avec les requêtes
  
 export default {
  data() {
    return {
      lots: [],
      currentLot: { id: null, nom: '', nombre_males: 0, nombre_femelles: 0 },
      lotId: null,
      ponteRecords: [],
      rapportData: [], // Données pour le rapport
      overviewData: [], // Données pour la vue d'ensemble du lot
      ponte: { date_ponte: '', nombre_oeufs: 0 },
      showModal: false,
      isEditing: false,
      currentPonteId: null,
      activeTab: 'ponte',
      currentPagePonte: 1, // Pagination spécifique à la section Ponte
      currentPageReport: 1, // Pagination spécifique à la section Rapport
      currentPageOverview: 1, // Pagination spécifique à la section Vue d'Ensemble
      pageSize: 3,
      totalPagesPonte: 0,
      totalPagesReport: 0,
      totalPagesOverview: 0,
      totalItemsPonte: 0,
      totalItemsReport: 0,
      totalItemsOverview: 0,
      loading: false, // Variable pour indiquer si la page est en chargement
    };
  },
  mounted() {
    this.fetchLots();
  },
  methods: {
    // Récupérer la liste des lots
    async fetchLots() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/lots`);
        this.lots = response.data;
        if (this.lots.length > 0) {
          this.lotId = this.lots[0].id;
          this.fetchPonteRecords();
        }
      } catch (error) {
        console.error('Erreur lors de la récupération des lots', error.response || error);
      }
    },

    // Méthode pour récupérer les enregistrements de ponte avec pagination
async fetchPonteRecords() {
  if (!this.lotId) return;

  this.loading = true;

  try {
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/lots/${this.lotId}/ponte`);
    this.ponteRecords = response.data.data || []; // Toutes les données

    // Calculer la pagination côté client
    this.totalItemsPonte = this.ponteRecords.length;
        this.totalPagesPonte = Math.ceil(this.totalItemsPonte / this.pageSize);

    // Filtrer les pontes pour afficher uniquement celles de la page actuelle
    const startIndex = (this.currentPagePonte - 1) * this.pageSize;
    const endIndex = startIndex + this.pageSize;
    this.ponteRecords = this.ponteRecords.slice(startIndex, endIndex); // Afficher les éléments pour la page actuelle

    // Mise à jour des données du lot
    this.currentLot = this.lots.find(lot => lot.id === this.lotId);
  } catch (error) {
    console.error('Erreur lors de la récupération des pontes', error.response || error);
    alert('Erreur lors de la récupération des pontes');
  } finally {
    this.loading = false;
  }
},
        
        // Récupérer les données pour le rapport de pontes avec pagination
    async fetchRapportData() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/lots/${this.lotId}/rapport`);
        this.rapportData = response.data || [];
        this.totalItemsReport = this.rapportData.length;
        this.totalPagesReport = Math.ceil(this.totalItemsReport / this.pageSize);

        // Filtrer pour afficher les éléments de la page actuelle
        const startIndex = (this.currentPageReport - 1) * this.pageSize;
        const endIndex = startIndex + this.pageSize;
        this.rapportData = this.rapportData.slice(startIndex, endIndex);
      } catch (error) {
        console.error('Erreur lors de la récupération du rapport', error.response || error);
      }
    },

       
   // Récupérer les données pour la vue d'ensemble du lot
async fetchOverviewData() {
  try {
    console.log("Début de la récupération des données...");

    // Effectuer la requête API
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/lots/${this.lotId}/overview`);
    
    // Vérifier la structure complète de la réponse
    console.log('Réponse complète de l\'API:', response);

    // Vérifier si `response.data` contient des données
    if (response && response.data) {
      console.log('Structure de la réponse:', response.data);
      
      // Vérification spécifique de la partie qui devrait contenir les données
      if (response.data.data) {
        console.log('Données extraites:', response.data.data);
        
        // Si la réponse est un objet (pas un tableau), le convertir en tableau pour permettre la pagination (si nécessaire)
        if (typeof response.data.data === 'object') {
          this.overviewData = [response.data.data]; // Mettre les données dans un tableau
        } else {
          console.warn('Les données ne sont pas sous forme d\'objet attendu:', response.data.data);
          this.overviewData = [];
        }
      } else {
        console.warn('Aucune donnée dans la clé "data". Structure:', response.data);
        this.overviewData = [];
      }
    } else {
      console.error('Réponse de l\'API non valide:', response);
      this.overviewData = [];
    }

    // Calcul de la pagination si nécessaire
    this.totalItemsOverview = this.overviewData.length;
    this.totalPagesOverview = Math.ceil(this.totalItemsOverview / this.pageSize);

    // Calcul des éléments à afficher selon la page actuelle
    const startIndex = (this.currentPageOverview - 1) * this.pageSize;
    const endIndex = startIndex + this.pageSize;
    this.overviewData = this.overviewData.slice(startIndex, endIndex);

  } catch (error) {
    console.error('Erreur lors de la récupération de la vue d\'ensemble:', error);
    this.overviewData = [];
  }
},


    // Changer d'onglet actif
    setActiveTab(tab) {
      this.activeTab = tab;
      if (tab === 'report') {
        this.fetchRapportData();
      } else if (tab === 'overview') {
        this.fetchOverviewData();
      }
    },

    // Gérer le changement de lot
    onLotChange() {
      this.fetchPonteRecords();
    },

    // Ouvrir le modal pour ajouter une ponte
    openPonteModal() {
      this.showModal = true;
      this.isEditing = false;
      this.ponte = { date_ponte: '', nombre_oeufs: 0 };
    },

    // Fermer le modal
    closeModal() {
      this.showModal = false;
    },

    // Modifier une ponte existante
    editPonte(ponte) {
      this.isEditing = true;
      this.ponte = { ...ponte };
      this.currentPonteId = ponte.id;
      this.showModal = true;
    },

    // Soumettre le formulaire de la ponte (ajout ou mise à jour)
    // Soumettre le formulaire de la ponte (ajout ou mise à jour)
async submitPonte() {
  try {
    if (this.isEditing) {
      // Mise à jour d'une ponte existante
      const response = await axios.put(`${import.meta.env.VITE_API_BASE_URL}/lots/${this.lotId}/ponte/${this.currentPonteId}`, this.ponte);
      console.log('Ponte mise à jour avec succès', response.data);  // Log de succès
      this.fetchPonteRecords();
    } else {
      // Ajouter une nouvelle ponte
      const response = await axios.post(`${import.meta.env.VITE_API_BASE_URL}/lots/${this.lotId}/ponte`, this.ponte);
      console.log('Ponte ajoutée avec succès', response.data);  // Log de succès
      this.fetchPonteRecords();
    }
    this.closeModal();
  } catch (error) {
    console.error('Erreur lors de l\'ajout ou de la mise à jour de la ponte', error.response || error);
    if (error.response) {
      // Log plus détaillé de l'erreur
      console.error('Détails de la réponse d\'erreur:', error.response.data);
      alert(`Erreur: ${error.response.data.message || 'Une erreur est survenue lors de l\'ajout ou de la mise à jour de la ponte'}`);
    } else {
      alert('Une erreur est survenue, veuillez réessayer.');
    }
  }
},

    // Supprimer une ponte
    async deletePonte(id) {
      try {
        await axios.delete(`${import.meta.env.VITE_API_BASE_URL}/ponte/${id}`);
        this.fetchPonteRecords();
      } catch (error) {
        console.error('Erreur lors de la suppression de la ponte', error.response || error);
      }
    },

            // Gérer la pagination pour chaque section (ponte, rapport, vue d'ensemble)
    goToPage(page, section) {
      if (section === 'ponte') {
        if (page >= 1 && page <= this.totalPagesPonte) {
          this.currentPagePonte = page;
          this.fetchPonteRecords();
        }
      } else if (section === 'report') {
        if (page >= 1 && page <= this.totalPagesReport) {
          this.currentPageReport = page;
          this.fetchRapportData();
        }
      } else if (section === 'overview') {
        if (page >= 1 && page <= this.totalPagesOverview) {
          this.currentPageOverview = page;
          this.fetchOverviewData();
        }
      }
    },

    // Aller à la page suivante pour chaque section
nextPage(section) {
  if (section === 'ponte' && this.currentPagePonte < this.totalPagesPonte) {
    this.currentPagePonte++; // Incrémente la page actuelle
    this.fetchPonteRecords(); // Recharger les enregistrements de ponte
  } else if (section === 'report' && this.currentPageReport < this.totalPagesReport) {
    this.currentPageReport++; // Incrémente la page actuelle
    this.fetchRapportData(); // Recharger les données du rapport
  } else if (section === 'overview' && this.currentPageOverview < this.totalPagesOverview) {
    this.currentPageOverview++; // Incrémente la page actuelle
    this.fetchOverviewData(); // Recharger les données de la vue d'ensemble
  }
},

// Aller à la page précédente pour chaque section
prevPage(section) {
  if (section === 'ponte' && this.currentPagePonte > 1) {
    this.currentPagePonte--; // Décrémente la page actuelle
    this.fetchPonteRecords(); // Recharger les enregistrements de ponte
  } else if (section === 'report' && this.currentPageReport > 1) {
    this.currentPageReport--; // Décrémente la page actuelle
    this.fetchRapportData(); // Recharger les données du rapport
  } else if (section === 'overview' && this.currentPageOverview > 1) {
    this.currentPageOverview--; // Décrémente la page actuelle
    this.fetchOverviewData(); // Recharger les données de la vue d'ensemble
  }
},







  },
};

  </script>