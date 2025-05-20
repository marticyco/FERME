<template>
    <div class="flex flex-col lg:flex-row min-h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside class="w-full lg:w-64 bg-white shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Gestion des Finances</h2>
    
        <div class="space-y-4">
          <div>
            <h3 class="text-gray-600 font-medium mb-2">
              Bande : {{ currentBand?.nom || 'Global' }}
            </h3>
    
            <h1 class="text-2xl font-semibold text-gray-800">
              Finances pour {{ currentBand?.nom || 'Vue Globale' }}
            </h1>
    
            <button
              class="w-full flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-800 hover:bg-blue-200 rounded"
              @click="fetchFinanceRecords"
            >
              <i class="fas fa-chart-line"></i> Voir les Finances
            </button>
          </div>
          
          <!-- Ajouter des catégories -->
          <div class="space-y-2 mt-4">
            <h3 class="text-gray-700 font-medium">Gérer les catégories</h3>
  
            <!-- Ajouter une catégorie de vente -->
            <div>
              <label class="block text-sm font-medium">Catégorie de Vente</label>
              <input
                v-model="newSaleCategory"
                type="text"
                placeholder="Ajouter une catégorie de vente"
                class="w-full border px-3 py-2 rounded"
              />
              <button
                @click="addSaleCategory"
                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
              >
                Ajouter une catégorie de vente
              </button>
            </div>
    
            <!-- Ajouter une catégorie de dépense -->
            <div>
              <label class="block text-sm font-medium">Catégorie de Dépense</label>
              <input
                v-model="newExpenseCategory"
                type="text"
                placeholder="Ajouter une catégorie de dépense"
                class="w-full border px-3 py-2 rounded"
              />
              <button
                @click="addExpenseCategory"
                class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
              >
                Ajouter une catégorie de dépense
              </button>
            </div>
          </div>
  
          <!-- Reste des boutons -->
          <div>
            <button
              class="w-full flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 hover:bg-green-200 rounded"
              @click="openExpenseModal"
            >
              <i class="fas fa-dollar-sign"></i> Ajouter une Dépense
            </button>
          </div>
          <div>
            <button
              class="w-full flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-800 hover:bg-yellow-200 rounded"
              @click="openSaleModal"
            >
              <i class="fas fa-cart-plus"></i> Ajouter une Vente
            </button>
          </div>
        </div>
      </aside>
  
      <!-- Main Content -->
      <main class="flex-1 p-6 space-y-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <h1 class="text-2xl font-semibold text-gray-800">
            Finances pour la Bande {{ currentBand?.nom || 'Vue Globale' }}
          </h1>
          <div class="mt-4 md:mt-0">
            <label for="bandSelect" class="block mb-1 text-sm font-medium text-gray-700">
              Sélectionner une Bande :
            </label>
            <select
              id="bandSelect"
              v-model="bandCode"
              @change="onBandChange"
              class="w-full md:w-64 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">🌍 Finances globales</option> <!-- ✅ Correct -->
              <option v-for="band in bands" :key="band.code" :value="band.id">
                  {{ band.nom }} ({{ band.code }})
                </option>
            </select>
          </div>
        </div>
    
        <div class="flex flex-col sm:flex-row gap-4 mt-4">
          <div>
            <label class="text-sm font-medium">Du :</label>
            <input type="date" v-model="dateFrom" class="w-full border px-3 py-2 rounded" />
          </div>
          <div>
            <label class="text-sm font-medium">Au :</label>
            <input type="date" v-model="dateTo" class="w-full border px-3 py-2 rounded" />
          </div>
          <button
            @click="fetchFinanceRecords"
            class="self-end px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Filtrer
          </button>
        </div>
  
        <!-- Finance Records -->
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700 text-left text-sm uppercase">
              <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Montant</th>
                <th class="px-4 py-3">Description</th>
              </tr>
            </thead>
            <tbody class="text-gray-800">
              <tr v-if="(!finances.expenses?.length && !finances.sales?.length)">
                <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                  Aucune donnée financière trouvée pour cette bande.
                </td>
              </tr>
              <tr v-for="finance in finances.expenses" :key="'e' + finance.id" class="border-t">
                <td class="px-4 py-2">{{ formatDate(finance.date) }}</td>
                <td class="px-4 py-2">Dépense</td>
                <td class="px-4 py-2 text-red-500">{{ formatCurrency(finance.montant) }}</td>
                <td class="px-4 py-2">{{ finance.description || 'Aucune description fournie' }}</td>
              </tr>
              <tr v-for="finance in finances.sales" :key="'s' + finance.id" class="border-t">
                <td class="px-4 py-2">{{ formatDate(finance.date) }}</td>
                <td class="px-4 py-2">Vente</td>
                <td class="px-4 py-2 text-green-600">{{ formatCurrency(finance.montant) }}</td>
                <td class="px-4 py-2">{{ finance.description || 'Aucune description fournie' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <!-- Résumé Financier -->
        <div class="bg-white p-8 rounded-lg shadow-xl">
          <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">📊 Résumé Financier</h3>
  
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
              <thead class="bg-gray-100 border-b border-gray-300">
                <tr>
                  <th class="py-3 px-4 font-semibold">Détails</th>
                  <th class="py-3 px-4 font-semibold text-red-600">Débit</th>
                  <th class="py-3 px-4 font-semibold text-green-600">Crédit</th>
                  <th class="py-3 px-4 font-semibold">Solde</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr>
                  <td class="py-3 px-4">Total Dépenses</td>
                  <td class="py-3 px-4 text-red-600 font-medium">{{ formatCurrency(totalExpenses) }}</td>
                  <td class="py-3 px-4"></td>
                  <td class="py-3 px-4">{{ formatCurrency(cashFlow) }}</td>
                </tr>
                <tr>
                  <td class="py-3 px-4">Total Ventes</td>
                  <td class="py-3 px-4"></td>
                  <td class="py-3 px-4 text-green-600 font-medium">{{ formatCurrency(totalSales) }}</td>
                  <td class="py-3 px-4">{{ formatCurrency(cashFlow) }}</td>
                </tr>
                <tr :class="{ 'text-red-500': cashFlow < 0, 'text-green-700': cashFlow >= 0 }" class="font-semibold">
                  <td class="py-3 px-4">💰 Cash Flow</td>
                  <td class="py-3 px-4"></td>
                  <td class="py-3 px-4">{{ formatCurrency(cashFlow) }}</td>
                  <td class="py-3 px-4">{{ formatCurrency(cashFlow) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
  
          <div class="mt-6 flex justify-end">
            <button
              class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded shadow transition"
              @click="downloadPDF"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Télécharger le PDF
            </button>
          </div>
        </div>
  
        <!-- Modales -->
        
        <div v-show="showExpenseModal"

       class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
       @click="closeExpenseModal"
  >
    <div class="bg-white p-6 rounded shadow-xl w-full max-w-md" @click.stop>
      <h3 class="text-lg font-semibold mb-4">Ajouter une Dépense</h3>
      <form @submit.prevent="submitExpense" class="space-y-4">
        <!-- Montant -->
        <div>
          <label for="montant" class="block text-sm font-medium">Montant</label>
          <input id="montant"
                 type="number"
                 v-model="expense.montant"
                 class="w-full border px-3 py-2 rounded"
                 required
                 min="0"
                 step="0.01"
          />
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium">Description</label>
          <textarea id="description"
                    v-model="expense.description"
                    class="w-full border px-3 py-2 rounded"
                    placeholder="Exemple: Achat matériel de bureau"
          ></textarea>
        </div>

        <!-- Catégorie -->
        <div>
          <label for="category" class="block text-sm font-medium">Catégorie</label>
          <select v-model="expense.category_id" class="w-full border px-3 py-2 rounded" required>
    <option v-for="category in expenseCategories" :key="category.id" :value="category.id">
      {{ category.name }}
    </option>
  </select>
        </div>

        <!-- Date -->
        <div>
          <label for="date" class="block text-sm font-medium">Date</label>
          <input id="date"
                 type="date"
                 v-model="expense.date"
                 class="w-full border px-3 py-2 rounded"
                 required
          />
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-2 mt-4">
          <button type="button" class="px-4 py-2 bg-gray-200 rounded" @click="closeExpenseModal">Annuler</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
        
        <div v-show="showSaleModal"
       class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
       @click.self="closeSaleModal"
  >
    <div class="bg-white p-6 rounded shadow-xl w-full max-w-md" @click.stop>
      <h3 class="text-lg font-semibold mb-4">Ajouter une Vente</h3>
      <form @submit.prevent="submitSale" class="space-y-4">
        <!-- Montant -->
        <div>
          <label for="montant" class="block text-sm font-medium">Montant</label>
          <input id="montant"
                 type="number"
                 v-model="sale.montant"
                 class="w-full border px-3 py-2 rounded"
                 required
                 min="0"
                 step="0.01"
          />
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium">Description</label>
          <textarea id="description"
                    v-model="sale.description"
                    class="w-full border px-3 py-2 rounded"
                    placeholder="Exemple: Vente de produits"
          ></textarea>
        </div>

        <!-- Catégorie -->
        <div>
          <label for="category" class="block text-sm font-medium">Catégorie</label>
          <select v-model="sale.category_id" class="w-full border px-3 py-2 rounded" required>
  <option v-for="category in saleCategories" :key="category.id" :value="category.id">
    {{ category.name }}
  </option>
</select>

        </div>

        <!-- Date -->
        <div>
          <label for="date" class="block text-sm font-medium">Date</label>
          <input id="date"
                 type="date"
                 v-model="sale.date"
                 class="w-full border px-3 py-2 rounded"
                 required
          />
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-2 mt-4">
          <button type="button" class="px-4 py-2 bg-gray-200 rounded" @click="closeSaleModal">Annuler</button>
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Ajouter</button>
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

  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;

  export default {
    data() {
      return {
        bands: [],
        dateFrom: '',
        dateTo: '',
        currentBand: { nom: 'Global' },  // Valeur par défaut
        bandCode: null,
        finances: [],
        expense: { montant: '', description: '', date: '', category: '' },
        sale: { montant: '', description: '', date: '', category: '' },
        totalExpenses: 0,
        totalSales: 0,
        cashFlow: 0,
        showExpenseModal: false,
        showSaleModal: false,
        loading: false,
        errorMessage: '',
        newSaleCategory: '',
        newExpenseCategory: '',
        saleCategories: [],
        expenseCategories: []
      };
    },
    mounted() {
      this.fetchBands();
      this.fetchCategories();  // Charger les catégories lors de la montée du composant
    },
    methods: {
      getUrl(path) {
        return this.bandCode
          ? `${apiBaseUrl}/bands/${this.bandCode}${path}`
          : `${apiBaseUrl}${path}`; // Cas de la finance globale
      },

      async fetchBands() {
        this.loading = true;
        try {
          const response = await axios.get(`${apiBaseUrl}/bands`);
          this.bands = response.data;

          if (this.bands.length > 0) {
            this.bandCode = this.bands[0].code;
            this.currentBand = this.bands[0];
          }

          await this.fetchFinanceRecords();
        } catch (error) {
          console.error('Erreur lors de la récupération des bandes:', error);
          this.handleError(error);
        } finally {
          this.loading = false;
        }
      },

      async fetchFinanceRecords() {
        this.loading = true;
        try {
          const params = new URLSearchParams();
          if (this.dateFrom) params.append('from', this.dateFrom);
          if (this.dateTo) params.append('to', this.dateTo);

          const url = `${this.getUrl('/finances')}?${params.toString()}`;
          const response = await axios.get(url);

          this.finances = response.data;
          this.totalExpenses = this.finances.expenses?.reduce((sum, e) => sum + parseFloat(e.montant), 0) || 0;
          this.totalSales = this.finances.sales?.reduce((sum, s) => sum + parseFloat(s.montant), 0) || 0;
          this.cashFlow = this.totalSales - this.totalExpenses;
        } catch (error) {
          this.handleError(error);
        } finally {
          this.loading = false;
        }
      },

      async fetchCategories() {
        try {
          const expenseResponse = await axios.get(`${apiBaseUrl}/categories/expenses`);
          this.expenseCategories = expenseResponse.data;

          const saleResponse = await axios.get(`${apiBaseUrl}/categories/sales`);
          this.saleCategories = saleResponse.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des catégories:', error);
          this.handleError(error);
        }
      },

      async submitExpense() {
        const payload = {
          montant: this.expense.montant,
          description: this.expense.description,
          date: this.expense.date,
          category: this.expense.category,
          band_id: this.bandCode || null, // 🎯 Null pour une dépense globale
        };

        try {
          if (!this.bandCode) {
            const confirmGlobal = confirm("Aucune bande sélectionnée. Cette dépense sera enregistrée globalement. Continuer ?");
            if (!confirmGlobal) return;
          }

          await axios.post(`${apiBaseUrl}/expenses`, payload);
          this.fetchFinanceRecords();
          this.closeExpenseModal();
          alert('✅ Dépense ajoutée avec succès !');
        } catch (error) {
          console.error("Erreur lors de l'ajout de la dépense :", error);
          this.handleError(error);
        }
      },

      async submitSale() {
        const payload = {
          montant: this.sale.montant,
          description: this.sale.description,
          date: this.sale.date,
          category: this.sale.category,
          band_id: this.bandCode || null, // 🎯 Null pour une vente globale
        };

        try {
          if (!this.bandCode) {
            const confirmGlobal = confirm("Aucune bande sélectionnée. Cette vente sera enregistrée globalement. Continuer ?");
            if (!confirmGlobal) return;
          }

          await axios.post(`${apiBaseUrl}/sales`, payload);
          this.fetchFinanceRecords();
          this.closeSaleModal();
          alert('✅ Vente ajoutée avec succès !');
        } catch (error) {
          console.error("Erreur lors de l'ajout de la vente :", error);
          this.handleError(error);
        }
      },

      async addSaleCategory() {
        try {
          const response = await axios.post(`${apiBaseUrl}/categories/sales`, { name: this.newSaleCategory });
          this.saleCategories.push(response.data);
          this.newSaleCategory = ''; // Reset after adding
        } catch (error) {
          console.error('Erreur lors de l\'ajout de la catégorie de vente:', error);
          this.handleError(error);
        }
      },

      async addExpenseCategory() {
        try {
          const response = await axios.post(`${apiBaseUrl}/expense-categories`, { name: this.newExpenseCategory });
          this.expenseCategories.push(response.data);
          this.newExpenseCategory = ''; // Reset after adding
        } catch (error) {
          console.error('Erreur lors de l\'ajout de la catégorie de dépense:', error);
          this.handleError(error);
        }
      },

      formatDate(date) {
        if (!date) return 'Date non fournie';
        const formattedDate = new Date(date);
        return isNaN(formattedDate.getTime())
          ? 'Date invalide'
          : formattedDate.toLocaleDateString('fr-FR');
      },

      formatCurrency(amount) {
        const parsedAmount = parseFloat(amount);
        return isNaN(parsedAmount)
          ? '0 FCFA'
          : parsedAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + ' FCFA';
      },

      openExpenseModal() {
        this.showExpenseModal = true;
        this.expense = { montant: '', description: '', date: '', category: '' };
      },

      closeExpenseModal() {
        this.showExpenseModal = false;
      },

      openSaleModal() {
        this.showSaleModal = true;
        this.sale = { montant: '', description: '', date: '', category: '' };
      },

      closeSaleModal() {
        this.showSaleModal = false;
      },

      onBandChange() {
        this.fetchFinanceRecords();
      },

      handleError(error) {
        if (error.response) {
          this.errorMessage = `Erreur ${error.response.status}: ${error.response.data.message || error.response.statusText}`;
        } else if (error.request) {
          this.errorMessage = 'Impossible de recevoir une réponse du serveur.';
        } else {
          this.errorMessage = 'Une erreur inconnue est survenue.';
        }
        console.error(this.errorMessage);
      },

      downloadPDF() {
        const doc = new jsPDF();

        const title = this.currentBand
          ? `Résumé Financier pour la Bande ${this.currentBand.nom}`
          : 'Résumé Financier Global';

        doc.setFontSize(18);
        doc.text(title, 20, 20);

        doc.setFontSize(12);
        doc.text(`Total Dépenses: ${this.formatCurrency(this.totalExpenses)}`, 20, 30);
        doc.text(`Total Ventes: ${this.formatCurrency(this.totalSales)}`, 20, 40);
        doc.text(`Cash Flow: ${this.formatCurrency(this.cashFlow)}`, 20, 50);

        const fileName = this.currentBand
          ? `résumé_financier_${this.currentBand.nom}.pdf`
          : 'résumé_financier_global.pdf';

        doc.save(fileName);
      }
    }
  };
</script>
