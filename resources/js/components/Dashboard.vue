<template>
    <div>
     <!-- Header du tableau de bord compact avec améliorations -->
<div class="w-full bg-gradient-to-r from-yellow-100 via-white to-yellow-50 shadow rounded-xl px-4 py-3 mb-4 border border-yellow-200">
  <div class="flex flex-col items-center text-center sm:flex-row sm:justify-between sm:text-left">
    
    <!-- Titre -->
    <h1 class="text-2xl sm:text-3xl font-bold text-yellow-600 uppercase tracking-wide flex items-center gap-2">
      🐔 AFRICA VOLAILLE D’OR
    </h1>

    <!-- Sous-titre avec texte noir -->
    <p class="mt-2 sm:mt-0 text-sm sm:text-base text-black font-medium italic animate-pulse">
      Nous vous offrons un mode de vie sain
    </p>
    
  </div>
</div>

  
      <!-- Section des widgets -->
      <div class="content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  
         <!-- Widget Combiné : Commandes & Abonnements -->
<div class="bg-white shadow-xl rounded-2xl p-6 min-h-[200px] transition-transform duration-300 hover:scale-[1.02] relative overflow-hidden group">

  <!-- Halo animé -->
  <div class="absolute -top-10 -right-10 w-40 h-40 bg-yellow-100 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>

  <!-- En-tête -->
  <div class="flex items-center gap-3 mb-6 z-10 relative">
    <i class="fas fa-chart-bar text-yellow-600 text-3xl"></i>
    <h3 class="text-xl font-bold text-gray-800 tracking-wide">Statistiques du Mois</h3>
  </div>

  <!-- Données -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-gray-700 z-10 relative">
    
    <!-- Commandes -->
    <div class="flex flex-col gap-1">
      <span class="text-gray-500 font-medium flex items-center gap-1">
        <i class="fas fa-shopping-cart text-green-500"></i> Commandes
      </span>
      <span class="text-2xl font-extrabold text-green-600">
        {{ orderStats?.total_orders ?? 0 }}
      </span>
      <span class="text-xs text-gray-500">
        Total : 
        <span class="inline-block bg-green-100 text-green-700 font-semibold px-2 py-1 rounded-full shadow-sm">
          {{ orderStats?.total_revenue?.toLocaleString() ?? 0 }} FCFA
        </span>
      </span>
    </div>

    <!-- Abonnements -->
    <div class="flex flex-col gap-1">
      <span class="text-gray-500 font-medium flex items-center gap-1">
        <i class="fas fa-user-plus text-blue-500"></i> Abonnements
      </span>
      <span class="text-2xl font-extrabold text-blue-600">
        {{ subscriptionStats?.total_subscriptions ?? 0 }}
      </span>
      <span class="text-xs text-gray-500">
        Total : 
        <span class="inline-block bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded-full shadow-sm">
          {{ subscriptionStats?.total_revenue?.toLocaleString() ?? 0 }} FCFA
        </span>
      </span>
    </div>

  </div>
</div>


  
          <!-- Widget 2 : Stock d'Aliments -->
          <div class="bg-white shadow-xl rounded-xl p-6 transition-all duration-300 hover:scale-[1.01] animate-fade-in min-h-[300px]">
  <!-- En-tête du widget -->
  <div class="flex items-center justify-between mb-4">
    <div class="space-y-1">
      <div class="flex items-center gap-2">
        <i class="fas fa-dove text-yellow-600 text-lg"></i>
        <h3 class="text-lg font-semibold text-gray-800 tracking-tight">
          Bandes de ce mois
        </h3>
      </div>
      <div>
        <span v-if="!loadingBands" class="text-3xl font-bold text-yellow-700">
          {{ bandCountThisMonth }}
        </span>
        <span v-else class="text-gray-400 text-base italic">Chargement...</span>
      </div>
    </div>
    <div class="bg-yellow-100 p-3 rounded-full text-yellow-700 shadow-md animate-pulse">
      <i class="fas fa-drumstick-bite text-2xl"></i>
    </div>
  </div>

  <!-- Dates d’abattage -->
  <div v-if="bandListThisMonth?.length" class="border-t border-gray-200 pt-4 mt-4">
    <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
      <i class="fas fa-calendar-alt text-yellow-600"></i>
      Dates d'abattage prévues
    </h4>
    <ul class="space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 pr-1 max-h-[140px]">
      <li
        v-for="band in bandListThisMonth"
        :key="band.id"
        class="flex justify-between items-center px-2 py-1 bg-yellow-50 rounded-md hover:bg-yellow-100 transition"
      >
        <span class="text-sm text-gray-700 font-medium">🐔 Bande <span class="font-semibold">{{ band.code }}</span></span>
        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
          {{ new Date(band.date_fin).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' }) }}
        </span>
      </li>
    </ul>
  </div>
</div>


  
          <!-- Widget 3 : Nombre d'Œufs Pondus -->
          <!-- Widget 3 : Nombre d'Œufs Pondus -->
<!-- Widget : Œufs Pondus -->
<div class="relative bg-white shadow-xl rounded-2xl p-6 min-h-[200px] transition-transform duration-300 hover:scale-[1.02] animate-fade-in flex flex-col items-center text-center gap-4">

  <!-- Icône décorative en haut -->
  <div class="bg-purple-100 p-4 rounded-full text-purple-700 shadow-md animate-bounce-slow">
    <i class="fas fa-egg text-3xl"></i>
  </div>

  <!-- Titre -->
  <h3 class="text-lg font-bold text-gray-800 tracking-wide flex items-center gap-2">
    <i class="fas fa-egg text-purple-600"></i> Œufs Pondus
  </h3>

  <!-- Chargement -->
  <div v-if="loadingOeufs" class="flex items-center justify-center gap-2 text-gray-500 text-sm">
    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
    </svg>
    <span class="italic">Chargement des données...</span>
  </div>

  <!-- Erreur -->
  <p v-else-if="errorOeufs" class="text-sm text-red-500 font-medium">
    Erreur lors du chargement
  </p>

  <!-- Données -->
  <div v-else class="space-y-1">
    <p class="text-4xl font-extrabold text-purple-700 tracking-wide">
      {{ totalOeufs.toLocaleString() }}
    </p>
    <p class="text-xs text-gray-500">
      Moyenne taux de ponte :
      <span class="inline-block bg-purple-100 text-purple-700 font-semibold px-2 py-1 rounded-full shadow-sm">
        {{ moyenneTauxPonte }}%
      </span>
    </p>
  </div>

</div>



  
        </div>
  
        <!-- Section des Graphiques -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
  
         <!-- Graphique des ventes -->
<div class="bg-white shadow-md rounded-xl p-5 transition-all duration-300 hover:shadow-xl animate-fade-in">
  <h3 class="text-base font-medium text-gray-600 mb-4 tracking-wide">
    Ventes, Dépenses & Cash Flow Mensuels
  </h3>
  <div class="relative">
    <canvas ref="financeChart" class="w-full h-64"></canvas>
  </div>
</div>


<div class="bg-white shadow-lg rounded-xl p-6 transition-all duration-300 hover:scale-[1.01] animate-fade-in">
  <div class="flex items-center justify-between mb-4">
    <div class="space-y-1">
      <div class="flex items-center gap-2">
        <i class="fas fa-egg text-yellow-600 text-xl"></i>
        <h3 class="text-lg font-medium text-gray-800 tracking-tight">
          Suivi des Incubations
        </h3>
      </div>
      <div class="text-sm text-gray-500">
        <span class="font-semibold">Incubations en cours :</span> 12
      </div>
    </div>
    <div class="bg-yellow-100 p-4 rounded-full text-yellow-700 shadow-md animate-pulse">
      <i class="fas fa-hatching text-3xl"></i>
    </div>
  </div>
  <canvas ref="incubChart"></canvas>
</div>

  
        </div>
      </div>
    </div>
  </template>
 <script>
 import { ref, onMounted, nextTick } from 'vue';
 import axios from 'axios';
 import Chart from 'chart.js/auto';
 
 export default {
   name: 'Dashboard',
   setup() {
     // Refs pour les canvas
     const salesChart = ref(null);
     const incubChart = ref(null);
     const financeChart = ref(null); // 👈 Nouveau graphique des finances
 
     // Données des widgets
     const totalOeufs = ref(0);
     const loadingOeufs = ref(true);
     const errorOeufs = ref(false);
     const bandCountThisMonth = ref(0);
     const loadingBands = ref(true);
     const errorBands = ref(false);
     const bandListThisMonth = ref([]);
     const moyenneTauxPonte = ref(0);
     const orderStats = ref({ total_orders: 0, total_revenue: 0 });
     const subscriptionStats = ref({ total_subscriptions: 0, total_revenue: 0 });

 
     // Données financières
     const financesData = ref(null);
 
     const fetchTotalOeufs = async () => {
       try {
         loadingOeufs.value = true;
         errorOeufs.value = false;
         const response = await axios.get('/api/total-oeufs');
         totalOeufs.value = response.data.total_oeufs;
         moyenneTauxPonte.value = response.data.moyenne_taux_ponte;
       } catch (error) {
         console.error("Erreur lors de la récupération des œufs pondus :", error);
         errorOeufs.value = true;
       } finally {
         loadingOeufs.value = false;
       }
     };

     const fetchOrderStats = async () => {
  try {
    const response = await axios.get('/api/orders/stats/month');
    orderStats.value = response.data;
  } catch (err) {
    console.error("Erreur lors de la récupération des stats commandes :", err);
  }
};

const fetchSubscriptionStats = async () => {
  try {
    const response = await axios.get('/api/subscriptions/stats/month');
    subscriptionStats.value = response.data;
  } catch (err) {
    console.error("Erreur lors de la récupération des stats abonnements :", err);
  }
};

 
     const fetchBandCountThisMonth = async () => {
       try {
         loadingBands.value = true;
         const response = await axios.get('/api/bands/count/month');
         const listResponse = await axios.get('/api/bands/list/month');
         bandCountThisMonth.value = response.data.count;
         bandListThisMonth.value = listResponse.data.bands;
       } catch (error) {
         console.error("Erreur lors du chargement des bandes :", error);
         errorBands.value = true;
       } finally {
         loadingBands.value = false;
       }
     };
 
     // 📊 Graphique des finances : ventes, dépenses, cash flow
     const fetchFinances = async () => {
       try {
         const response = await axios.get('/api/finances-cash');
         console.log("Finances récupérées :", response.data); // 👀 debug
         financesData.value = response.data;
         createFinanceChart();
       } catch (error) {
         console.error("Erreur lors de la récupération des finances :", error);
       }
     };
 
     const createFinanceChart = () => {
       if (financeChart.value && financesData.value) {
         const { sales, expenses } = financesData.value;
 
         // Génération des mois
         const months = [...new Set([...sales, ...expenses].map(item =>
           new Date(item.date).toLocaleString('fr-FR', { month: 'short', year: 'numeric' })
         ))];
 
         const sumByMonth = (items) => {
           return months.map(month => {
             return items
               .filter(i => new Date(i.date).toLocaleString('fr-FR', { month: 'short', year: 'numeric' }) === month)
               .reduce((sum, i) => sum + i.montant, 0);
           });
         };
 
         const salesData = sumByMonth(sales);
         const expenseData = sumByMonth(expenses);
         const cashFlowData = salesData.map((val, idx) => val - expenseData[idx]);
 
         new Chart(financeChart.value, {
  type: 'bar',
  data: {
    labels: months,
    datasets: [
      {
        label: 'Ventes (FCFA)',
        data: salesData,
        backgroundColor: '#4CAF50'
      },
      {
        label: 'Dépenses (FCFA)',
        data: expenseData,
        backgroundColor: '#EF5350'
      },
      {
        label: 'Cash Flow (FCFA)',
        data: cashFlowData,
        backgroundColor: '#FFEB3B'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      },
      tooltip: {
        mode: 'index',
        intersect: false
      }
    },
    scales: {
      x: {
        stacked: false
      },
      y: {
        beginAtZero: true
      }
    }
  }
});

       }
     };
 
     const createIncubChart = async () => {
       try {
         const response = await axios.get('/api/incubations');
         const data = response.data;
 
         data.sort((a, b) => new Date(a.date_debut) - new Date(b.date_debut));
 
         const labels = data.map(item =>
           new Date(item.date_debut).toLocaleDateString('fr-FR')
         );
         const oeufsIncubes = data.map(item => item.nombre_oeufs_incubes);
         const tauxFertilite = data.map(item => item.taux_fertilite?.toFixed(1) || 0);
         const tauxEclosion = data.map(item => item.taux_eclosion?.toFixed(1) || 0);
         const datesMirage = data.map(item =>
           item.date_mirage ? new Date(item.date_mirage).toLocaleDateString('fr-FR') : '—'
         );
         const datesEclosion = data.map(item =>
           item.date_fin_eclosion ? new Date(item.date_fin_eclosion).toLocaleDateString('fr-FR') : '—'
         );
 
         if (incubChart.value) {
           new Chart(incubChart.value, {
             type: 'bar',
             data: {
               labels,
               datasets: [
                 {
                   label: 'Œufs Incubés',
                   data: oeufsIncubes,
                   backgroundColor: 'rgba(52, 152, 219, 0.7)',
                   borderColor: '#3498db',
                   borderWidth: 1
                 },
                 {
                   label: 'Taux de Fertilité (%)',
                   data: tauxFertilite,
                   backgroundColor: 'rgba(155, 89, 182, 0.6)',
                   borderColor: '#9b59b6',
                   borderWidth: 1,
                   yAxisID: 'y1'
                 },
                 {
                   label: 'Taux d’éclosion (%)',
                   data: tauxEclosion,
                   type: 'line',
                   backgroundColor: '#e67e22',
                   borderColor: '#e67e22',
                   borderWidth: 2,
                   fill: false,
                   yAxisID: 'y1'
                 }
               ]
             },
             options: {
               responsive: true,
               interaction: {
                 mode: 'index',
                 intersect: false
               },
               scales: {
                 y: {
                   beginAtZero: true,
                   title: { display: true, text: 'Œufs Incubés' }
                 },
                 y1: {
                   beginAtZero: true,
                   position: 'right',
                   title: { display: true, text: 'Taux (%)' },
                   grid: { drawOnChartArea: false }
                 }
               },
               plugins: {
                 tooltip: {
                   callbacks: {
                     afterBody: (tooltipItems) => {
                       const index = tooltipItems[0].dataIndex;
                       return [
                         `📅 Mirage : ${datesMirage[index]}`,
                         `🐣 Éclosion : ${datesEclosion[index]}`
                       ];
                     },
                     label: (context) => {
                       const label = context.dataset.label || '';
                       const value = context.raw;
                       return label.includes('Taux') ? `${label}: ${value}%` : `${label}: ${value} œufs`;
                     }
                   }
                 }
               }
             }
           });
         }
       } catch (err) {
         console.error('Erreur lors du chargement des incubations :', err);
       }
     };
 
     onMounted(() => {
       fetchTotalOeufs();
       fetchBandCountThisMonth();
       fetchFinances(); // 👈 Charger les finances
       fetchOrderStats();           // 👈 ajout
       fetchSubscriptionStats();    // 👈 ajout
 
       nextTick(() => {
         createIncubChart();
       });
     });
 
     return {
       salesChart,
       incubChart,
       financeChart,
       totalOeufs,
       loadingOeufs,
       errorOeufs,
       bandCountThisMonth,
       loadingBands,
       errorBands,
       bandListThisMonth,
       moyenneTauxPonte,
       orderStats,           // 👈 Ajouter ceci
       subscriptionStats     // 👈 Et ceci aussi
     };
   }
 };
 </script>
 
  
  
  <style scoped>
  /* Ajout de styles personnalisés si nécessaire */
  </style>
  