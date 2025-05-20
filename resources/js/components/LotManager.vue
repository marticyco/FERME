<template>
    <div class="flex">
      <!-- Sidebar -->
      <div class="w-1/4 bg-gray-800 p-4">
  <!-- Titre avec icône -->
  <div class="flex items-center text-white text-xl font-bold mb-6 space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
    </svg>
    <span>Gestion des Lots</span>
  </div>

  <div class="space-y-4">
    <!-- Bouton Liste des Lots -->
    <button @click="fetchLots" class="flex items-center space-x-3 w-full text-white bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <span>Liste des Lots</span>
    </button>

    <!-- Bouton Ajouter un Lot -->
    <button @click="openAddLotModal" class="flex items-center space-x-3 w-full text-white bg-green-600 hover:bg-green-700 py-2 px-4 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      <span>Ajouter un Lot</span>
    </button>

    <!-- Bouton Statistiques -->
    <button @click="openStatsModal" class="flex items-center space-x-3 w-full text-white bg-purple-600 hover:bg-purple-700 py-2 px-4 rounded-md">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3m4 14v-4m-8 8V7m8 8h4m-4 4h4M3 3h18" />
      </svg>
      <span>Statistiques</span>
    </button>
  </div>
</div>

  
      <!-- Main Content -->
      <div class="flex-1 p-6">
        <h2 class="text-2xl font-bold mb-4">Liste des Lots</h2>
  
         <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th class="px-4 py-2 border-b text-left">Nom</th>
              <th class="px-4 py-2 border-b text-left">Mâles</th>
              <th class="px-4 py-2 border-b text-left">Femelles</th>
              <th class="px-4 py-2 border-b text-left">Mortalité Totale</th>
              <th class="px-4 py-2 border-b text-left">Taux de Mortalités</th>
              <th class="px-4 py-2 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="lot in lots" :key="lot.id">
              <td class="px-4 py-2 border-b">{{ lot.nom }}</td>
              <td class="px-4 py-2 border-b">{{ lot.nombre_males }}</td>
              <td class="px-4 py-2 border-b">{{ lot.nombre_femelles }}</td>
              <td class="px-4 py-2 border-b text-center">{{ lot.totalMortalite }}</td>
              <td
            class="px-4 py-2 border-b text-center"
            :class="{
            'text-red-600 font-semibold': lot.tauxMortalite > 0
            }"
            >
           {{ lot.tauxMortalite.toFixed(2) }}%
           </td>

              <td class="px-4 py-2 border-b text-center space-x-2">
                <button @click="editLot(lot)" class="text-yellow-500 hover:text-yellow-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 3l4 4m0 0L7 17l-4 4V14l4-4 10-10z"></path>
                  </svg>
                </button>
                <button @click="deleteLot(lot.id)" class="text-red-500 hover:text-red-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
                <button @click="showPerformanceModal(lot.id)" class="text-green-500 hover:text-green-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                  </svg>
                </button>
                <button @click="openMortaliteModal(lot.id)" class="text-orange-500 hover:text-orange-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-2 2m0 0l-2-2m2 2l2-2m-2 2l-2 2m-8-4l-2-2m0 0l-2 2m2-2l2-2m-2 2l-2 2m4 4l-4 4m4-4l4 4"></path>
                  </svg>
                </button>
                <button @click="openExportModal" class="text-blue-500 hover:text-blue-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m4 4l-4-4m0 0l4-4m-4 4h-4m4-4l-4-4"></path>
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

  
        <!-- Modals -->
  
        <!-- Modal pour Ajouter/Modifier un Lot -->
        <div v-if="showAddLotModal" class="modal fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-md shadow-lg w-96">
            <div class="flex justify-between items-center">
              <h5 class="text-xl font-semibold">Ajouter un Lot</h5>
              <button type="button" class="text-gray-500 hover:text-gray-700" @click="closeAddLotModal">
                <span class="material-icons">close</span>
              </button>
            </div>
            <div class="mt-4">
              <form @submit.prevent="saveLot">
                <div class="mb-4">
                  <label for="nom" class="block text-sm font-medium">Nom du Lot</label>
                  <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" v-model="currentLot.nom" required />
                </div>
                <div class="mb-4">
                  <label for="nombre_males" class="block text-sm font-medium">Nombre de Mâles</label>
                  <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" v-model="currentLot.nombre_males" required />
                </div>
                <div class="mb-4">
                  <label for="nombre_femelles" class="block text-sm font-medium">Nombre de Femelles</label>
                  <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" v-model="currentLot.nombre_femelles" required />
                </div>
                <div v-if="successMessage" class="alert alert-success text-green-600 mt-3">{{ successMessage }}</div>
                <div v-if="errorMessage" class="alert alert-danger text-red-600 mt-3">{{ errorMessage }}</div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mt-4">Enregistrer</button>
              </form>
            </div>
          </div>
        </div>
  
        <!-- Modal pour Exporter les Données -->
        <div v-if="showExportModal" class="modal fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-md shadow-lg w-96">
            <div class="flex justify-between items-center">
              <h5 class="text-xl font-semibold">Exporter les Données</h5>
              <button type="button" class="text-gray-500 hover:text-gray-700" @click="closeExportModal">
                <span class="material-icons">close</span>
              </button>
            </div>
            <div class="mt-4">
              <form @submit.prevent="exportData">
                <div class="mb-4">
                  <label for="startDate" class="block text-sm font-medium">Date de Début</label>
                  <input type="date" v-model="exportStartDate" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                </div>
                <div class="mb-4">
                  <label for="endDate" class="block text-sm font-medium">Date de Fin</label>
                  <input type="date" v-model="exportEndDate" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mt-4">Exporter</button>
              </form>
            </div>
          </div>
        </div>
  
        <!-- Modal pour la Mortalité -->
        <div v-if="showMortaliteModal" class="modal fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-md shadow-lg w-96">
            <div class="flex justify-between items-center">
              <h5 class="text-xl font-semibold">Ajouter une Mortalité</h5>
              <button type="button" class="text-gray-500 hover:text-gray-700" @click="closeMortaliteModal">
                <span class="material-icons">close</span>
              </button>
            </div>
            <div class="mt-4">
              <form @submit.prevent="submitMortalite">
                <div class="mb-4">
                  <label for="date_mortalite" class="block text-sm font-medium">Date de la mortalité</label>
                  <input type="date" v-model="mortalite.date_mortalite" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                </div>
                <div class="mb-4">
                  <label for="nombre_males_morts" class="block text-sm font-medium">Nombre de Mâles Morts</label>
                  <input type="number" v-model="mortalite.nombre_males_morts" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" />
                </div>
                <div class="mb-4">
                  <label for="nombre_femelles_morts" class="block text-sm font-medium">Nombre de Femelles Morts</label>
                  <input type="number" v-model="mortalite.nombre_femelles_morts" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" />
                </div>
                <!-- Calcul automatique du nombre total de morts -->
                <div class="mb-4">
                  <label for="nombre_morts" class="block text-sm font-medium">Nombre de morts (total)</label>
                  <input type="number" v-model="mortalite.nombre_morts" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" :readonly="true" :value="calculateTotalMorts()" />
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mt-4">Enregistrer la mortalité</button>
              </form>
            </div>
          </div>
        </div>
  
        <!-- Stats Modal -->
        <div v-if="showStatsModal" class="modal-overlay fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-md shadow-lg w-96">
            <h3 class="text-xl font-semibold">Statistiques</h3>
            <p><strong>Total des Lots : </strong>{{ totalLots }}</p>
            <p><strong>Mortalité Totale : </strong>{{ totalMortalite }}</p>
            <p><strong>Total Œufs Pondus : </strong>{{ totalEggs }}</p>
            <div class="mt-4">
              <button @click="closeStatsModal" class="w-full bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600">Fermer</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  

<script>
import axios from 'axios';
import jsPDF from 'jspdf';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
 axios.defaults.withCredentials = true; // Cela permet d'envoyer les cookies avec les requêtes
export default {
  data() {
    return {
      lots: [],
      currentLot: {
        id: null,
        nom: '',
        nombre_males: 0,
        nombre_femelles: 0,
      },
      showExportModal: false,
      exportStartDate: '',
      exportEndDate: '',
      successMessage: '',
      errorMessage: '',
      showAddLotModal: false,
      showStatsModal: false,
      showMortaliteModal: false, // Pour afficher/masquer le modal de mortalité
      mortalite: {
        date_mortalite: '',
        nombre_males_morts: 0,
        nombre_femelles_morts: 0,
      },
      lotId: null, // ID du lot pour la mortalité
      totalLots: 0,
      totalMortalite: 0,
      totalEggs: 0,
    };
  },
  methods: {
    // Calculer le nombre total de morts (males + femelles)
    calculateTotalMorts() {
      return this.mortalite.nombre_males_morts + this.mortalite.nombre_femelles_morts;
    },

    async saveLot() {
      const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
      const url = this.currentLot.id
        ? `${apiBaseUrl}/lots/${this.currentLot.id}`
        : `${apiBaseUrl}/lots`;

      try {
        const response = this.currentLot.id
          ? await axios.put(url, this.currentLot)
          : await axios.post(url, this.currentLot);

        this.successMessage = this.currentLot.id ? 'Lot mis à jour avec succès!' : 'Lot ajouté avec succès!';
        this.errorMessage = '';
        this.closeAddLotModal();
        this.fetchLots();
        this.resetForm();
      } catch (error) {
        console.error('Erreur lors de l\'ajout ou de la mise à jour du lot', error);
        this.errorMessage = 'Une erreur s\'est produite lors de l\'enregistrement du lot.';
        this.successMessage = '';
      }
    },

    async fetchLots() {
  try {
    // Appel à l'API pour récupérer les lots et les valeurs calculées
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/lots`);
    
    // Les données récupérées contiennent déjà la mortalité totale et le taux de mortalité
    this.lots = response.data;

    // Vérification des données récupérées
    console.log('Données des lots récupérées:', this.lots);
    
  } catch (error) {
    console.error('Erreur lors de la récupération des lots', error);
  }
},


    calculateStats() {
  // Calcul de la mortalité totale et du taux de mortalité pour chaque lot
  this.lots.forEach(lot => {
    const nombreMalesMorts = lot.nombre_males_morts || 0;
    const nombreFemellesMorts = lot.nombre_femelles_morts || 0;

    // Calcul de la mortalité totale
    lot.totalMortalite = nombreMalesMorts + nombreFemellesMorts;

    // Calcul du taux de mortalité
    const totalReproducteurs = lot.nombre_males + lot.nombre_femelles;
    lot.tauxMortalite = totalReproducteurs > 0
      ? ((lot.totalMortalite / totalReproducteurs) * 100).toFixed(2)
      : 0;

    // Log pour vérifier les calculs
    console.log(`Lot ${lot.nom} - Mortalité Totale: ${lot.totalMortalite}, Taux de Mortalité: ${lot.tauxMortalite}%`);
  });

  // Calcul des statistiques globales si nécessaire
  this.totalLots = this.lots.length;
  this.totalMortalite = this.lots.reduce((acc, lot) => acc + (lot.totalMortalite || 0), 0);
  this.totalEggs = this.lots.reduce((acc, lot) => acc + (lot.nombre_femelles * 2), 0);  // Exemple de calcul pour les œufs

  // Log pour vérifier les totaux
  console.log(`Total Lots: ${this.totalLots}, Total Mortalité: ${this.totalMortalite}, Total Œufs: ${this.totalEggs}`);
},

    
    openAddLotModal(lot = null) {
      if (lot) {
        this.currentLot = { ...lot };
      } else {
        this.resetForm();
      }
      this.showAddLotModal = true;
    },

    closeAddLotModal() {
      this.showAddLotModal = false;
    },

    resetForm() {
      this.currentLot = {
        id: null,
        nom: '',
        nombre_males: 0,
        nombre_femelles: 0,
      };
    },

    editLot(lot) {
      this.currentLot = { ...lot };
      this.showAddLotModal = true;
    },

    openStatsModal() {
      this.showStatsModal = true;
    },

    closeStatsModal() {
      this.showStatsModal = false;
    },

    async deleteLot(id) {
      const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
      try {
        const response = await axios.delete(`${apiBaseUrl}/lots/${id}`);
        console.log('Lot supprimé', response.data);
        this.fetchLots();
      } catch (error) {
        console.error('Erreur lors de la suppression du lot', error);
      }
    },

    openMortaliteModal(lotId) {
      this.lotId = lotId;
      this.showMortaliteModal = true;
    },

    closeMortaliteModal() {
      this.showMortaliteModal = false;
      this.resetMortaliteForm();
    },

    openExportModal() {
      this.showExportModal = true;
    },

    closeExportModal() {
      this.showExportModal = false;
      this.exportStartDate = '';
      this.exportEndDate = '';
    },

    async exportData() {
      if (!this.exportStartDate || !this.exportEndDate) {
        alert("Veuillez sélectionner les dates");
        return;
      }

      const startDate = new Date(this.exportStartDate);
      const endDate = new Date(this.exportEndDate);

      const filteredLots = this.lots.filter(lot => {
        const lotDate = new Date(lot.date_mortalite);
        return lotDate >= startDate && lotDate <= endDate;
      });

      if (filteredLots.length === 0) {
        alert("Aucun lot trouvé dans cette plage de dates.");
        return;
      }

      const { jsPDF } = await import('jspdf');
      const doc = new jsPDF();
      doc.setFontSize(18);
      doc.text("Rapport des Lots - Exportation", 20, 20);

      let yPosition = 30;
      const tableHeaders = ['Nom', 'Nombre de Reproducteurs', 'Date de Mortalité', 'Taux de Mortalité'];

      doc.autoTable({
        startY: yPosition,
        head: [tableHeaders],
        body: filteredLots.map(lot => [
          lot.nom,
          `${lot.nombre_males + lot.nombre_femelles}`,
          lot.date_mortalite || 'Non définie',
          lot.nombre_morts ? `${((lot.nombre_morts / (lot.nombre_males + lot.nombre_femelles)) * 100).toFixed(2)}%` : '0%',
        ]),
      });

      doc.save("lots_export.pdf");
      this.closeExportModal();
    },

    async submitMortalite() {
      const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
      
      // Créer un objet de données à envoyer à l'API
      const mortaliteData = {
        date_mortalite: this.mortalite.date_mortalite,
        nombre_males_morts: this.mortalite.nombre_males_morts,
        nombre_femelles_morts: this.mortalite.nombre_femelles_morts
      };

      try {
        // Envoi de la requête API pour enregistrer la mortalité
        const response = await axios.post(`${apiBaseUrl}/lots/${this.lotId}/mortalite`, mortaliteData);

        // Affichage du succès dans la console
        console.log('Mortalité enregistrée', response.data);

        // Mettre à jour l'affichage des lots
        this.fetchLots();

        // Fermer la modal
        this.closeMortaliteModal();
      } catch (error) {
        // Gestion de l'erreur en cas d'échec
        console.error('Erreur lors de l\'enregistrement de la mortalité', error);
        this.errorMessage = 'Une erreur s\'est produite lors de l\'enregistrement de la mortalité.';
        this.successMessage = '';
      }
    },

    resetMortaliteForm() {
      this.mortalite = {
        date_mortalite: '',
        nombre_males_morts: 0,
        nombre_femelles_morts: 0,
      };
    },

    // Fonction pour rendre la modale déplaçable
    makeModalDraggable() {
      this.$nextTick(() => {
        const modal = document.querySelector('.modal-content');
        if (modal) {
          let isMouseDown = false;
          let offsetX, offsetY;

          modal.addEventListener('mousedown', (e) => {
            isMouseDown = true;
            offsetX = e.clientX - modal.getBoundingClientRect().left;
            offsetY = e.clientY - modal.getBoundingClientRect().top;
            modal.style.cursor = 'move';
          });

          document.addEventListener('mousemove', (e) => {
            if (isMouseDown) {
              modal.style.left = `${e.clientX - offsetX}px`;
              modal.style.top = `${e.clientY - offsetY}px`;
            }
          });

          document.addEventListener('mouseup', () => {
            isMouseDown = false;
            modal.style.cursor = 'default';
          });
        }
      });
    },
  },

  mounted() {
    this.fetchLots();
    this.makeModalDraggable(); // Appel de la fonction ici pour rendre la modale déplaçable
  },
};
</script>
