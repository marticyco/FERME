<template>
    <div class="flex min-h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside class="w-64 bg-blue-700 text-white shadow-lg p-6 space-y-8">
        <div>
          <h2 class="text-2xl font-bold">Traitements</h2>
        </div>
        <div class="space-y-6">
          <div>
            <h3 class="text-sm font-semibold text-blue-100 mb-2">Bande: {{ currentBand.code }}</h3>
            <button
              class="flex items-center w-full bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-white transition"
              @click="fetchTreatmentRecords"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
              </svg>
              Voir les Traitements
            </button>
          </div>
  
          <div>
            <h3 class="text-sm font-semibold text-blue-100 mb-2">Plan de Prophylaxie</h3>
            <button
              class="flex items-center w-full bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-white transition"
              @click="downloadProphylaxisPlan"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4v16h16V4H4zm4 4h8M8 12h8m-8 4h5">
                </path>
              </svg>
              Télécharger le Plan
            </button>
          </div>
  
          <div>
            <h3 class="text-sm font-semibold text-blue-100 mb-2">Alertes</h3>
            <button
              class="flex items-center w-full bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-white transition"
              @click="viewAlerts"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13 16h-1v-4h-1m1-4h.01M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
              </svg>
              Voir les Alertes
            </button>
          </div>
        </div>
      </aside>
  
      <!-- Main Content Header -->
<main class="flex-1 p-8 space-y-8">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <!-- Titre -->
    <h1 class="text-3xl font-bold text-gray-800">
      {{ currentBand.code }}
    </h1>

    <!-- Bande Select + Ajouter -->
    <div class="flex items-center space-x-4">
      <!-- Label + Select -->
      <label for="bandSelect" class="text-gray-700 font-medium whitespace-nowrap">
        Sélectionner une Bande :
      </label>
      <select
        id="bandSelect"
        v-model="bandCode"
        @change="onBandChange"
        class="border border-gray-300 rounded px-3 py-2 text-gray-800 focus:ring focus:ring-blue-200"
      >
        <option v-for="band in bands" :key="band.code" :value="band.code">
          {{ band.nom }} ({{ band.code }})
        </option>
      </select>

      <!-- Icône Ajout -->
      <button
        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full shadow-md transition relative group"
        @click="openTreatmentModal"
        aria-label="Ajouter un Traitement"
      >
        <!-- Plus Icon (Heroicons outline) -->
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>

        <!-- Tooltip -->
        <span
          class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity"
        >
          Ajouter un traitement
        </span>
      </button>
    </div>
  </div>

<!-- Alertes de traitement -->
<div v-if="alertes.length > 0" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded mb-6">
  <h2 class="font-bold text-lg mb-2">Alertes de Traitement</h2>
  <ul class="list-disc pl-5 space-y-1">
    <li v-for="(alerte, index) in alertes" :key="index">
      {{ alerte.message }}
    </li>
  </ul>
</div>



  
        <!-- Traitement Table -->
<div class="overflow-x-auto bg-white shadow rounded">
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date de Traitement</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Prix</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Reçu</th>
        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      <tr v-if="!Array.isArray(traitements) || traitements.length === 0">
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
          Aucun traitement trouvé pour cette bande.
        </td>
      </tr>
      <tr v-for="traitement in paginatedTraitements" :key="traitement.id">
        <td class="px-6 py-4">{{ new Date(traitement.date_planifiee).toLocaleDateString() }}</td>
        <td class="px-6 py-4">{{ traitement.nom }}</td>
        <td class="px-6 py-4">{{ traitement.description || 'Aucune description' }}</td>

        <!-- Prix -->
        <td class="px-6 py-4">
          <span v-if="traitement.prix !== null && traitement.prix !== ''">
            {{ Number(traitement.prix).toFixed(2) }} €
          </span>
          <span v-else>Non défini</span>
        </td>

        <!-- Reçu -->
        <td class="px-6 py-4">
          <a
            v-if="traitement.recu_image_path"
            :href="'/storage/' + traitement.recu_image_path"
            target="_blank"
            class="text-blue-500 hover:underline"
          >
            Voir le reçu
          </a>
          <span v-else>Aucun reçu</span>
        </td>

        <!-- Actions -->
        <td class="px-6 py-4">
          <div class="flex items-center space-x-2">

            <!-- Modifier -->
            <button
              class="p-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-full group relative"
              @click="editTreatment(traitement)"
              aria-label="Modifier"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h4l6 6-4 4-6-6V5zM5 13l4 4-1 4-3-1-1-3 4-4z" />
              </svg>
              <span
                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10"
              >
                Modifier
              </span>
            </button>

            <!-- Supprimer -->
            <button
              class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-full group relative"
              @click="deleteTreatment(traitement.id)"
              aria-label="Supprimer"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
              </svg>
              <span
                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10"
              >
                Supprimer
              </span>
            </button>

          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div class="flex justify-center items-center mt-6 space-x-2">
  <button
    @click="currentPage--"
    :disabled="currentPage === 1"
    class="px-3 py-1 bg-blue-600 text-white rounded disabled:opacity-50 hover:bg-blue-700 transition"
  >
    Précédent
  </button>

  <button
    v-for="page in totalPages"
    :key="page"
    @click="currentPage = page"
    :class="[
      'px-3 py-1 rounded',
      currentPage === page ? 'bg-blue-700 text-white' : 'bg-white border border-gray-300 text-gray-800',
      'hover:bg-blue-600 hover:text-white transition'
    ]"
  >
    {{ page }}
  </button>

  <button
    @click="currentPage++"
    :disabled="currentPage === totalPages"
    class="px-3 py-1 bg-blue-600 text-white rounded disabled:opacity-50 hover:bg-blue-700 transition"
  >
    Suivant
  </button>
</div>


  
        <!-- Modal -->
<div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closeModal">
  <div class="bg-white w-full max-w-lg p-6 rounded shadow-lg">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ isEditing ? 'Modifier' : 'Ajouter' }} un Traitement</h3>
    <form @submit.prevent="submitTreatment" class="space-y-4">
      <!-- Nom du Traitement -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Nom du Traitement</label>
        <input
          type="text"
          v-model="traitement.nom"
          class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-200"
          required
        />
      </div>

      <!-- Description -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Description</label>
        <textarea
          v-model="traitement.description"
          class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-200"
        ></textarea>
      </div>

      <!-- Date Planifiée -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Date Planifiée</label>
        <input
          type="date"
          v-model="traitement.date_planifiee"
          class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-200"
          required
        />
      </div>

      <!-- Prix -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Prix</label>
        <input
          type="number"
          step="0.01"
          v-model="traitement.prix"
          class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-200"
        />
      </div>

      <!-- Reçu de paiement -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Reçu de Paiement</label>
        <input
          type="file"
          @change="handleFileUpload"
          accept="image/*"
          class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-200"
        />
        <span v-if="traitement.recu_image_path">Fichier sélectionné : {{ traitement.recu_image_path }}</span>
      </div>

      <!-- Boutons -->
      <div class="flex justify-end space-x-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          {{ isEditing ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400" @click="closeModal">
          Annuler
        </button>
      </div>
    </form>
  </div>
</div>

      </main>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { jsPDF } from 'jspdf';
  import { autoTable } from 'jspdf-autotable';
  import { applyPlugin } from 'jspdf-autotable';
  applyPlugin(jsPDF);


  
  // Définir l'URL de base pour l'API
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
  
  export default {
    data() {
      return {
        bands: [], // Liste des bandes
        alertes: [],
        currentPage: 1,
        itemsPerPage: 3, // ou 10, selon ta préférence
        currentBand: {
          code: '', // Utiliser le code de la bande
          nom: '',
          date_debut: '', // Date de début de la bande
        },
        bandCode: '', // Code de la bande sélectionnée
        traitements: [], // Liste des traitements pour la bande sélectionnée
        traitement: {
          nom: '',
          description: '',
          date_planifiee: '',
          prix: '',             // Nouveau champ pour le prix
          recu: null,
         recu_image_path: ''   // Nouveau champ pour le chemin de l'image du reçu
        },
        showModal: false,
        isEditing: false,
        currentTreatmentId: null,
        loading: false, // Indicateur de chargement
        errorMessage: '', // Message d'erreur
      };
    },
    mounted() {
      this.fetchBands();
    },

    computed: {
  paginatedTraitements() {
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;
    return this.traitements.slice(start, end);
  },

  totalPages() {
    return Math.ceil(this.traitements.length / this.itemsPerPage);
  }
},

    methods: {
      // Récupérer la liste des bandes
      async fetchBands() {
        this.loading = true;
        try {
          const response = await axios.get(`${apiBaseUrl}/bands`);
          this.bands = response.data;
          if (this.bands.length > 0) {
            this.bandCode = this.bands[0].code;
            this.fetchTreatmentRecords();
          }
        } catch (error) {
          console.error('Erreur lors de la récupération des bandes:', error);
          this.handleError(error);
        } finally {
          this.loading = false;
        }
      },

      generateProphylaxisAlerts() {
  const startDate = new Date(this.currentBand.date_debut);
  const today = new Date();
  const traitementsEffectués = this.traitements;
  const alerts = [];

  const formatDate = (date) => date.toISOString().split('T')[0]; // format YYYY-MM-DD
  const getDateAfterDays = (days) => {
    const newDate = new Date(startDate);
    newDate.setDate(newDate.getDate() + days);
    return newDate;
  };

  const addRangePlan = (start, end, noms, label) => {
    for (let i = start; i <= end; i++) {
      plan.push({ date: getDateAfterDays(i), noms, label: `${label} (Jour ${i + 1})` });
    }
  };

  const plan = [];

  // Prophylaxie Médicale
  addRangePlan(0, 4, ['Olivitasol', 'Hépaturyl'], 'Prophylaxie médicale');
  addRangePlan(5, 10, ['Alfacéryl'], 'Prophylaxie médicale');
  addRangePlan(11, 13, [], 'Eau simple');
  addRangePlan(14, 19, ['Vitamine', 'Amprolium'], 'Prophylaxie médicale');
  addRangePlan(20, 27, [], 'Eau simple');
  addRangePlan(28, 33, ['Vitamine', 'Amprolium'], 'Prophylaxie médicale');
  addRangePlan(34, 40, [], 'Eau simple');
  plan.push({ date: getDateAfterDays(41), noms: ['Déparasitant'], label: '42ème jour' });

  // Prophylaxie Vaccinale
  plan.push({ date: getDateAfterDays(0), noms: ['Newcastle', 'Bronchite infectieuse'], label: '1ère semaine (vaccin)' });
  plan.push({ date: getDateAfterDays(7), noms: ['Gumboro'], label: '2ème semaine (vaccin)' });
  plan.push({ date: getDateAfterDays(14), noms: ['Newcastle', 'Bronchite infectieuse'], label: '3ème semaine (vaccin)' });
  plan.push({ date: getDateAfterDays(21), noms: ['Gumboro'], label: '4ème semaine (vaccin)' });

  // Vérification des traitements
  plan.forEach(item => {
    const planDate = item.date;
    if (planDate < today) {
      item.noms.forEach(nom => {
        const treatmentExists = traitementsEffectués.some(tr => {
          const trDate = new Date(tr.date_planifiee);
          return tr.nom.toLowerCase().includes(nom.toLowerCase()) &&
                 Math.abs(trDate - planDate) <= 86400000; // tolérance d’un jour
        });

        if (!treatmentExists) {
          alerts.push({
            type: 'manquant',
            message: `⚠️ Le traitement "${nom}" prévu le ${formatDate(planDate)} (${item.label}) n'a pas été effectué.`,
          });
        }
      });
    }
  });

  this.alertes = alerts;
},


downloadProphylaxisPlan() {
  const doc = new jsPDF();
  const startDate = new Date(this.currentBand.date_debut);

  const getDateAfterDays = (days) => {
    const newDate = new Date(startDate);
    newDate.setDate(newDate.getDate() + days);
    return newDate;
  };

  const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
  };

  const plan = [
    {
      start: formatDate(getDateAfterDays(0)),
      end: formatDate(getDateAfterDays(4)),
      description: 'Traitement Olivitasol + Hépaturyl (eau).',
      traitements: ['Olivitasol', 'Hépaturyl'],
      intervalle: 'Jour 1 à 5'
    },
    {
      start: formatDate(getDateAfterDays(5)),
      end: formatDate(getDateAfterDays(10)),
      description: 'Traitement Alfacéryl (eau).',
      traitements: ['Alfacéryl'],
      intervalle: 'Jour 6 à 11'
    },
    {
      start: formatDate(getDateAfterDays(11)),
      end: formatDate(getDateAfterDays(13)),
      description: 'Eau simple.',
      traitements: [],
      intervalle: 'Jour 12 à 14'
    },
    {
      start: formatDate(getDateAfterDays(14)),
      end: formatDate(getDateAfterDays(19)),
      description: 'Vitamine + Amprolium (eau).',
      traitements: ['Vitamine', 'Amprolium'],
      intervalle: 'Jour 15 à 20'
    },
    {
      start: formatDate(getDateAfterDays(20)),
      end: formatDate(getDateAfterDays(27)),
      description: 'Eau simple.',
      traitements: [],
      intervalle: 'Jour 21 à 28'
    },
    {
      start: formatDate(getDateAfterDays(28)),
      end: formatDate(getDateAfterDays(33)),
      description: 'Vitamine + Amprolium (eau).',
      traitements: ['Vitamine', 'Amprolium'],
      intervalle: 'Jour 29 à 34'
    },
    {
      start: formatDate(getDateAfterDays(34)),
      end: formatDate(getDateAfterDays(40)),
      description: 'Eau simple.',
      traitements: [],
      intervalle: 'Jour 35 à 41'
    },
    {
      start: formatDate(getDateAfterDays(41)),
      end: formatDate(getDateAfterDays(41)),
      description: 'Déparasitant.',
      traitements: ['Déparasitant'],
      intervalle: 'Jour 42'
    },
    {
      start: formatDate(getDateAfterDays(0)),
      end: formatDate(getDateAfterDays(6)),
      description: 'Vaccins Newcastle + Bronchite infectieuse.',
      traitements: ['Newcastle', 'Bronchite infectieuse'],
      intervalle: '1ère semaine (vaccin)'
    },
    {
      start: formatDate(getDateAfterDays(7)),
      end: formatDate(getDateAfterDays(13)),
      description: 'Vaccin Gumboro.',
      traitements: ['Gumboro'],
      intervalle: '2ème semaine (vaccin)'
    },
    {
      start: formatDate(getDateAfterDays(14)),
      end: formatDate(getDateAfterDays(20)),
      description: 'Rappel Newcastle + Bronchite infectieuse.',
      traitements: ['Newcastle', 'Bronchite infectieuse'],
      intervalle: '3ème semaine (vaccin)'
    },
    {
      start: formatDate(getDateAfterDays(21)),
      end: formatDate(getDateAfterDays(27)),
      description: 'Rappel Gumboro.',
      traitements: ['Gumboro'],
      intervalle: '4ème semaine (vaccin)'
    }
  ];

  // Trier les traitements par date de début
  plan.sort((a, b) => new Date(a.start) - new Date(b.start));

  // En-tête
  doc.setFont('helvetica', 'bold');
  doc.setFontSize(16);
  doc.text('Plan de Prophylaxie - Bande ' + this.currentBand.code, 14, 20);

  doc.setFontSize(12);
  doc.setFont('helvetica', 'normal');
  doc.text('Date de début : ' + formatDate(startDate), 14, 28);

  // Tableau
  autoTable(doc, {
    head: [['Date de début', 'Date de fin', 'Description', 'Traitements', 'Intervalle de temps']],
    body: plan.map(item => [
      item.start,
      item.end,
      item.description,
      item.traitements.join(', '),
      item.intervalle,
    ]),
    startY: 35,
    theme: 'grid',
    headStyles: { fillColor: [22, 160, 133] }, // Vert foncé
    styles: { fontSize: 10, cellPadding: 3 },
    alternateRowStyles: { fillColor: [240, 240, 240] }
  });

  // Enregistrer
  doc.save(`Plan_Prophylaxie_${this.currentBand.code}.pdf`);
},


  
      // Récupérer les traitements pour la bande sélectionnée
      async fetchTreatmentRecords() {
        if (!this.bandCode) return;
  
        this.loading = true;
        try {
          const response = await axios.get(`${apiBaseUrl}/bands/${this.bandCode}/traitements`);
          this.traitements = response.data;
          this.generateProphylaxisAlerts();
          this.currentBand = this.bands.find(band => band.code === this.bandCode);
        } catch (error) {
          console.error('Erreur lors de la récupération des traitements:', error);
          this.handleError(error);
        } finally {
          this.loading = false;
        }
      },
  
      // Gérer les erreurs de l'API
      handleError(error) {
        if (error.response) {
          this.errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || error.response.statusText}`;
        } else if (error.request) {
          this.errorMessage = 'Impossible de recevoir une réponse du serveur. Veuillez vérifier votre connexion.';
        } else {
          this.errorMessage = 'Une erreur inconnue est survenue. Veuillez réessayer.';
        }
        console.error(this.errorMessage);  // Affiche l'erreur dans la console pour la déboguer
      },
  
      // Soumettre le traitement (création ou mise à jour)
      async submitTreatment() {
  const method = this.isEditing ? 'put' : 'post';
  const url = this.isEditing
    ? `${apiBaseUrl}/bands/${this.bandCode}/traitements/${this.currentTreatmentId}`
    : `${apiBaseUrl}/bands/${this.bandCode}/traitements`;

  const formData = new FormData();
  formData.append('nom', this.traitement.nom);
  formData.append('description', this.traitement.description || '');
  formData.append('date_planifiee', this.traitement.date_planifiee);

  if (this.traitement.prix !== '') {
    formData.append('prix', parseFloat(this.traitement.prix));
  }

  if (this.traitement.recu) {
    formData.append('recu', this.traitement.recu);
  }

  this.loading = true;
  try {
    await axios.post(url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    this.fetchTreatmentRecords();
    this.closeModal();
    alert(this.isEditing ? 'Traitement mis à jour!' : 'Traitement ajouté!');
  } catch (error) {
    console.error("Erreur lors de l'enregistrement du traitement:", error);
    this.handleError(error);
  } finally {
    this.loading = false;
  }
},

handleFileUpload(event) {
  this.traitement.recu = event.target.files[0];
},


      // Ouvrir la modal de traitement
      openTreatmentModal() {
        this.showModal = true;
        this.resetForm();
      },
  
      // Fermer la modal de traitement
      closeModal() {
        this.showModal = false;
        this.resetForm();
      },
  
      // Réinitialiser le formulaire
      resetForm() {
        this.traitement = {
          nom: '',
          description: '',
          date_planifiee: '',
          prix: '',   
          recu: null,       
          recu_image_path: ''   
        };
        this.isEditing = false;
        this.currentTreatmentId = null;
      },
  
      // Modifier un traitement existant
      editTreatment(traitement) {
        this.traitement = { ...traitement };
        this.isEditing = true;
        this.currentTreatmentId = traitement.id;
        this.showModal = true;
      },
  
      // Supprimer un traitement
      async deleteTreatment(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce traitement ?')) {
          this.loading = true;
          try {
            await axios.delete(`${apiBaseUrl}/bands/${this.bandCode}/traitements/${id}`);
            this.fetchTreatmentRecords();
            alert('Traitement supprimé avec succès!');
          } catch (error) {
            console.error('Erreur lors de la suppression du traitement:', error);
            this.handleError(error);
          } finally {
            this.loading = false;
          }
        }
      },
  
      // Changer de bande et mettre à jour les traitements
      onBandChange() {
        this.fetchTreatmentRecords();
      },
  
      // Afficher le plan de prophylaxie (fonction fictive)
      viewProphylaxisPlan() {
        this.downloadProphylaxisPlan();
      },
  
      // Afficher les alertes (fonction fictive)
      viewAlerts() {
        alert('Afficher les Alertes');
      },
    },
  };
  
  </script>
  