<template>
    
    <div class="flex h-screen bg-gray-100">
  <!-- Sidebar -->
  <div class="w-64 bg-blue-600 text-white shadow-lg">
    <div class="p-6">
      <h2 class="text-2xl font-semibold">Gestion des Stocks</h2>
    </div>
    <ul class="space-y-2 mt-6">
      <li @click="setActiveTab('aliments')" 
          :class="{ 'bg-blue-500': activeTab === 'aliments' }" 
          class="px-6 py-3 flex items-center space-x-3 rounded-md hover:bg-blue-500 cursor-pointer transition-colors duration-200">
        <i class="fas fa-utensils text-orange-300 w-5 h-5 text-center leading-none flex-shrink-0"></i>
        <span>Aliments</span>
      </li>
      <li @click="setActiveTab('stocks')" 
          :class="{ 'bg-blue-500': activeTab === 'stocks' }" 
          class="px-6 py-3 flex items-center space-x-3 rounded-md hover:bg-blue-500 cursor-pointer transition-colors duration-200">
        <i class="fas fa-boxes text-yellow-300 w-5 h-5 text-center leading-none flex-shrink-0"></i>
        <span>Stocks</span>
      </li>
      <li @click="setActiveTab('entrees')" 
          :class="{ 'bg-blue-500': activeTab === 'entrees' }" 
          class="px-6 py-3 flex items-center space-x-3 rounded-md hover:bg-blue-500 cursor-pointer transition-colors duration-200">
        <i class="fas fa-arrow-down text-green-300 w-5 h-5 text-center leading-none flex-shrink-0"></i>
        <span>Entrées</span>
      </li>
      <li @click="setActiveTab('sorties')" 
          :class="{ 'bg-blue-500': activeTab === 'sorties' }" 
          class="px-6 py-3 flex items-center space-x-3 rounded-md hover:bg-blue-500 cursor-pointer transition-colors duration-200">
        <i class="fas fa-arrow-up text-red-300 w-5 h-5 text-center leading-none flex-shrink-0"></i>
        <span>Sorties</span>
      </li>
      <li @click="setActiveTab('fournisseurs')" 
          :class="{ 'bg-blue-500': activeTab === 'fournisseurs' }" 
          class="px-6 py-3 flex items-center space-x-3 rounded-md hover:bg-blue-500 cursor-pointer transition-colors duration-200">
        <i class="fas fa-truck text-purple-300 w-5 h-5 text-center leading-none flex-shrink-0"></i>
        <span>Fournisseurs</span>
      </li>
    </ul>
  </div>


      <!-- Main Content -->
<div class="flex-1 p-6 overflow-y-auto">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Suivi De Stocks</h1>
    <div class="flex space-x-3">
      <button class="p-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white shadow transition" 
              :disabled="isSubmitting" @click="openAlimentModal" title="Ajouter un Aliment">
        <i class="fas fa-utensils text-lg"></i>
      </button>
      <button class="p-3 rounded-full bg-gray-600 hover:bg-gray-700 text-white shadow transition" 
              :disabled="isSubmitting" @click="openStockModal" title="Ajouter un Stock">
        <i class="fas fa-boxes text-lg"></i>
      </button>
      <button class="p-3 rounded-full bg-green-600 hover:bg-green-700 text-white shadow transition" 
              :disabled="isSubmitting" @click="openEntreeModal" title="Ajouter une Entrée">
        <i class="fas fa-arrow-down text-lg"></i>
      </button>
      <button class="p-3 rounded-full bg-red-600 hover:bg-red-700 text-white shadow transition" 
              :disabled="isSubmitting" @click="openSortieModal" title="Ajouter une Sortie">
        <i class="fas fa-arrow-up text-lg"></i>
      </button>
      <button class="p-3 rounded-full bg-cyan-600 hover:bg-cyan-700 text-white shadow transition" 
              :disabled="isSubmitting" @click="openFournisseurModal" title="Ajouter un Fournisseur">
        <i class="fas fa-truck text-lg"></i>
      </button>
    </div>
  </div>


        <!-- Tab Content -->
  
        <!-- Aliments Tab -->
<div v-if="activeTab === 'aliments'" class="bg-white shadow rounded-xl p-6 mb-6">
  <h3 class="text-xl font-bold mb-4">🍽️ Liste des Aliments</h3>
  
  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-6 py-4">Nom</th>
          <th class="px-6 py-4">Type</th>
          <th class="px-6 py-4">Prix Unitaire</th>
          <th class="px-6 py-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="aliments.length === 0">
          <td colspan="4" class="text-center py-6 text-gray-500">Aucun aliment trouvé.</td>
        </tr>
        <tr v-for="aliment in getPaginatedData().items" :key="aliment.id" class="border-t hover:bg-gray-50">
          <td class="px-6 py-4 font-medium text-gray-800">{{ aliment.nom }}</td>
          <td class="px-6 py-4">{{ aliment.type }}</td>
          <td class="px-6 py-4">{{ aliment.prix_unitaire }} FCFA</td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
              <button class="text-yellow-500 hover:text-yellow-600" @click="editAliment(aliment)" title="Modifier">
                <i class="fas fa-edit text-lg"></i>
              </button>
              <button class="text-red-500 hover:text-red-600" @click="deleteAliment(aliment.id)" title="Supprimer">
                <i class="fas fa-trash-alt text-lg"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6 space-x-2" v-if="getPaginatedData().totalPages > 1">
    <!-- Bouton Précédent -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === 1"
      @click="currentPage--">
      ← Précédent
    </button>

    <!-- Boutons de pages -->
    <button 
      v-for="page in getPaginatedData().totalPages" 
      :key="page"
      @click="currentPage = page"
      class="px-4 py-2 rounded-lg border text-sm font-medium transition"
      :class="{
        'bg-blue-600 text-white border-blue-600': currentPage === page,
        'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
      }">
      {{ page }}
    </button>

    <!-- Bouton Suivant -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === getPaginatedData().totalPages"
      @click="currentPage++">
      Suivant →
    </button>
  </div>
</div>

        <!-- Stocks Tab -->
<div v-if="activeTab === 'stocks'" class="bg-white shadow rounded-xl p-6 mb-6">
  <h3 class="text-xl font-bold mb-4">📦 Liste des Stocks</h3>
  
  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-6 py-4">Aliment</th>
          <th class="px-6 py-4">Quantité</th>
          <th class="px-6 py-4">Date d'Entrée</th>
          <th class="px-6 py-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="stocks.length === 0">
          <td colspan="4" class="text-center py-6 text-gray-500">Aucun stock trouvé.</td>
        </tr>
        <tr v-for="stock in getPaginatedData().items" :key="stock.id" class="border-t hover:bg-gray-50">
          <td class="px-6 py-4">{{ stock.aliment.nom }}</td>
          <td class="px-6 py-4">{{ stock.quantite }} kg</td>
          <td class="px-6 py-4">{{ new Date(stock.date_entree).toLocaleDateString() }}</td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
              <button class="text-yellow-500 hover:text-yellow-600" @click="editStock(stock)" title="Modifier">
                <i class="fas fa-edit text-lg"></i>
              </button>
              <button class="text-red-500 hover:text-red-600" @click="deleteStock(stock.id)" title="Supprimer">
                <i class="fas fa-trash-alt text-lg"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6 space-x-2" v-if="getPaginatedData().totalPages > 1">
    <!-- Bouton Précédent -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === 1"
      @click="currentPage--">
      ← Précédent
    </button>

    <!-- Pages -->
    <button 
      v-for="page in getPaginatedData().totalPages" 
      :key="page"
      @click="currentPage = page"
      class="px-4 py-2 rounded-lg border text-sm font-medium transition"
      :class="{
        'bg-blue-600 text-white border-blue-600': currentPage === page,
        'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
      }">
      {{ page }}
    </button>

    <!-- Bouton Suivant -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === getPaginatedData().totalPages"
      @click="currentPage++">
      Suivant →
    </button>
  </div>
</div>


  
        <!-- Entrées Tab -->
<div v-if="activeTab === 'entrees'" class="bg-white shadow rounded-xl p-6 mb-6">
  <h3 class="text-xl font-bold mb-4">⬇️ Liste des Entrées</h3>

  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-6 py-4">Aliment</th>
          <th class="px-6 py-4">Quantité</th>
          <th class="px-6 py-4">Fournisseur</th>
          <th class="px-6 py-4">Date d'Entrée</th>
          <th class="px-6 py-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="entrees.length === 0">
          <td colspan="5" class="text-center py-6 text-gray-500">Aucune entrée trouvée.</td>
        </tr>
        <tr v-for="entree in getPaginatedData().items" :key="entree.id" class="border-t hover:bg-gray-50">
          <td class="px-6 py-4">{{ entree.aliment.nom }}</td>
          <td class="px-6 py-4">{{ entree.quantite }}</td>
          <td class="px-6 py-4">{{ entree.fournisseur.nom }}</td>
          <td class="px-6 py-4">{{ new Date(entree.date_entree).toLocaleDateString() }}</td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
              <button class="text-yellow-500 hover:text-yellow-600" @click="editEntree(entree)" title="Modifier">
                <i class="fas fa-edit text-lg"></i>
              </button>
              <button class="text-red-500 hover:text-red-600" @click="deleteEntree(entree.id)" title="Supprimer">
                <i class="fas fa-trash-alt text-lg"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6 space-x-2" v-if="getPaginatedData().totalPages > 1">
    <!-- Bouton Précédent -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === 1"
      @click="currentPage--">
      ← Précédent
    </button>

    <!-- Pages -->
    <button 
      v-for="page in getPaginatedData().totalPages" 
      :key="page"
      @click="currentPage = page"
      class="px-4 py-2 rounded-lg border text-sm font-medium transition"
      :class="{
        'bg-blue-600 text-white border-blue-600': currentPage === page,
        'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
      }">
      {{ page }}
    </button>

    <!-- Bouton Suivant -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === getPaginatedData().totalPages"
      @click="currentPage++">
      Suivant →
    </button>
  </div>
</div>


  
       <!-- Sorties Tab -->
<div v-if="activeTab === 'sorties'" class="bg-white shadow rounded-xl p-6 mb-6">
  <h3 class="text-xl font-bold mb-4">⬆️ Liste des Sorties</h3>

  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-6 py-4">Aliment</th>
          <th class="px-6 py-4">Quantité</th>
          <th class="px-6 py-4">Date de Sortie</th>
          <th class="px-6 py-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="sorties.length === 0">
          <td colspan="4" class="text-center py-6 text-gray-500">Aucune sortie trouvée.</td>
        </tr>
        <tr v-for="sortie in getPaginatedData().items" :key="sortie.id" class="border-t hover:bg-gray-50">
          <td class="px-6 py-4">{{ sortie.aliment.nom }}</td>
          <td class="px-6 py-4">{{ sortie.quantite }}</td>
          <td class="px-6 py-4">{{ new Date(sortie.date_sortie).toLocaleDateString() }}</td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
              <button class="text-yellow-500 hover:text-yellow-600" @click="editSortie(sortie)" title="Modifier">
                <i class="fas fa-edit text-lg"></i>
              </button>
              <button class="text-red-500 hover:text-red-600" @click="deleteSortie(sortie.id)" title="Supprimer">
                <i class="fas fa-trash-alt text-lg"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6 space-x-2" v-if="getPaginatedData().totalPages > 1">
    <!-- Précédent -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === 1"
      @click="currentPage--">
      ← Précédent
    </button>

    <!-- Pages -->
    <button 
      v-for="page in getPaginatedData().totalPages" 
      :key="page"
      @click="currentPage = page"
      class="px-4 py-2 rounded-lg border text-sm font-medium transition"
      :class="{
        'bg-blue-600 text-white border-blue-600': currentPage === page,
        'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
      }">
      {{ page }}
    </button>

    <!-- Suivant -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === getPaginatedData().totalPages"
      @click="currentPage++">
      Suivant →
    </button>
  </div>
</div>

        <!-- Fournisseurs Tab -->
<div v-if="activeTab === 'fournisseurs'" class="bg-white shadow rounded-xl p-6 mb-6">
  <h3 class="text-xl font-bold mb-4">🚚 Liste des Fournisseurs</h3>

  <div class="overflow-x-auto rounded-lg">
    <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 text-gray-700 uppercase">
        <tr>
          <th class="px-6 py-4">Nom</th>
          <th class="px-6 py-4">Contact</th>
          <th class="px-6 py-4 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="fournisseurs.length === 0">
          <td colspan="3" class="text-center py-6 text-gray-500">Aucun fournisseur trouvé.</td>
        </tr>
        <tr v-for="fournisseur in getPaginatedData().items" :key="fournisseur.id" class="border-t hover:bg-gray-50">
          <td class="px-6 py-4">{{ fournisseur.nom }}</td>
          <td class="px-6 py-4">{{ fournisseur.contact }}</td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center space-x-3">
              <button class="text-yellow-500 hover:text-yellow-600" @click="editFournisseur(fournisseur)" title="Modifier">
                <i class="fas fa-edit text-lg"></i>
              </button>
              <button class="text-red-500 hover:text-red-600" @click="deleteFournisseur(fournisseur.id)" title="Supprimer">
                <i class="fas fa-trash-alt text-lg"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center mt-6 space-x-2" v-if="getPaginatedData().totalPages > 1">
    <!-- Précédent -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === 1"
      @click="currentPage--">
      ← Précédent
    </button>

    <!-- Pages -->
    <button 
      v-for="page in getPaginatedData().totalPages" 
      :key="page"
      @click="currentPage = page"
      class="px-4 py-2 rounded-lg border text-sm font-medium transition"
      :class="{
        'bg-blue-600 text-white border-blue-600': currentPage === page,
        'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': currentPage !== page
      }">
      {{ page }}
    </button>

    <!-- Suivant -->
    <button 
      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition"
      :disabled="currentPage === getPaginatedData().totalPages"
      @click="currentPage++">
      Suivant →
    </button>
  </div>
</div>


      </div>
    </div>
  
    <!-- Modal Templates -->
    
   <!-- Aliment Modal -->
<div v-if="showAlimentModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeAlimentModal">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    
    <!-- Titre dynamique -->
    <h3 class="text-xl font-bold mb-4">
      {{ editingAliment ? 'Modifier l\'Aliment' : 'Ajouter un Aliment' }}
    </h3>
    
    <form @submit.prevent="submitAliment">
      <div class="mb-4">
        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
        <input v-model="aliment.nom" type="text" id="nom" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="mb-4">
        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
        <input v-model="aliment.type" type="text" id="type" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="mb-4">
        <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
        <input v-model="aliment.prix_unitaire" type="number" id="prix_unitaire" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      
      <!-- Boutons dynamiques -->
      <div class="flex justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="isSubmitting">
          {{ editingAliment ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" @click="closeAlimentModal" class="bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</button>
      </div>
    </form>
  </div>
</div>

<div v-if="showStockModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeStockModal">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">
      {{ editingStock ? 'Modifier le Stock' : 'Ajouter un Stock' }}
    </h3>
    <form @submit.prevent="submitStock">
      <div class="mb-4">
        <label for="aliment" class="block text-sm font-medium text-gray-700">Aliment</label>
        <select v-model="stock.aliment" id="aliment" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required>
          <option v-for="item in aliments" :value="item.id" :key="item.id">{{ item.nom }}</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
        <input v-model="stock.quantite" type="number" id="quantite" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="mb-4">
        <label for="date_entree" class="block text-sm font-medium text-gray-700">Date d'Entrée</label>
        <input v-model="stock.date_entree" type="date" id="date_entree" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="flex justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="isSubmitting">
          {{ editingStock ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" @click="closeStockModal" class="bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</button>
      </div>
    </form>
  </div>
</div>

  
   <!-- Modal Entrée -->
<div v-if="showEntreeModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeEntreeModal">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">
      {{ editingEntree ? 'Modifier une Entrée' : 'Ajouter une Entrée' }}
    </h3>
    <form @submit.prevent="submitEntree">
      <div class="mb-4">
        <label for="aliment" class="block text-sm font-medium text-gray-700">Aliment</label>
        <select v-model="entree.aliment_id" id="aliment" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required>
          <option v-for="item in aliments" :value="item.id" :key="item.id">{{ item.nom }}</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
        <input v-model="entree.quantite" type="number" id="quantite" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="mb-4">
        <label for="fournisseur" class="block text-sm font-medium text-gray-700">Fournisseur</label>
        <select v-model="entree.fournisseur_id" id="fournisseur" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required>
          <option v-for="fournisseur in fournisseurs" :value="fournisseur.id" :key="fournisseur.id">{{ fournisseur.nom }}</option>
        </select>
      </div>

      <!-- Ajout du champ Date d'Entrée -->
      <div class="mb-4">
        <label for="date_entree" class="block text-sm font-medium text-gray-700">Date d'Entrée</label>
        <input v-model="entree.date_entree" type="date" id="date_entree" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>

      <div class="flex justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="isSubmitting">
          {{ editingEntree ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" @click="closeEntreeModal" class="bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</button>
      </div>
    </form>
  </div>
</div>


   <!-- Modal Sortie -->
<div v-if="showSortieModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeSortieModal">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">
      {{ editingSortie ? 'Modifier une Sortie' : 'Ajouter une Sortie' }}
    </h3>
    <form @submit.prevent="submitSortie">
      <div class="mb-4">
        <label for="aliment" class="block text-sm font-medium text-gray-700">Aliment</label>
        <select v-model="sortie.aliment_id" id="aliment" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required>
          <option v-for="item in aliments" :value="item.id" :key="item.id">{{ item.nom }}</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
        <input v-model="sortie.quantite" type="number" id="quantite" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>

      <div class="mb-4">
        <label for="date_sortie" class="block text-sm font-medium text-gray-700">Date de Sortie</label>
        <input v-model="sortie.date_sortie" type="date" id="date_sortie" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      
      <div class="flex justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="isSubmitting">
          {{ editingSortie ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" @click="closeSortieModal" class="bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</button>
      </div>
    </form>
  </div>
</div>


   <!-- Modal Fournisseur -->
<div v-if="showFournisseurModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeFournisseurModal">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">
      {{ editingFournisseur ? 'Modifier un Fournisseur' : 'Ajouter un Fournisseur' }}
    </h3>
    <form @submit.prevent="submitFournisseur">
      <div class="mb-4">
        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
        <input v-model="fournisseur.nom" type="text" id="nom" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="mb-4">
        <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
        <input v-model="fournisseur.contact" type="text" id="contact" class="mt-2 w-full p-2 border border-gray-300 rounded-md" required />
      </div>
      <div class="flex justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="isSubmitting">
          {{ editingFournisseur ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <button type="button" @click="closeFournisseurModal" class="bg-gray-600 text-white px-4 py-2 rounded-md">Annuler</button>
      </div>
    </form>
  </div>
</div>




  </template>
  <script>
  import axios from 'axios';
// CSRF + Cookies
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;

  export default {
    data() {
      return {
        aliments: [],
        stocks: [],
        entrees: [],
        sorties: [],
        lowStocks: [],
        currentPage: 1,
        itemsPerPage: 2,
        fournisseurs: [],
        aliment: { nom: '', categorie: '', prix_unitaire: 0 },
        stock: { aliment_id: '', quantite: 0, date_entree: '' },
        entree: { aliment_id: '', quantite: 0, fournisseur_id: '', date_entree: '' },
        sortie: { aliment_id: '', quantite: 0, date_sortie: '' },
        fournisseur: { nom: '',  contact: '' },
        showAlimentModal: false,
        showStockModal: false,
        showEntreeModal: false,
        showSortieModal: false,
        showFournisseurModal: false,
        editingAliment: false,
        editingStock: false,
        editingEntree: false,
        editingSortie: false,
        editingFournisseur: false,
        activeTab: 'aliments',
        isSubmitting: false,
      };
    },

  
    mounted() {
      this.fetchAliments();
      this.fetchStocks();
      this.fetchEntrees();
      this.fetchSorties();
      this.fetchFournisseurs();
      this.fetchLow(); // Ajout ici
    },
    methods: {
      setActiveTab(tab) {
        this.activeTab = tab;
      },

      // Fetch all resources using the same structure as fetchClients
      fetchAliments() {
        axios.get(`${import.meta.env.VITE_API_BASE_URL}/aliments`)
          .then((response) => {
            this.aliments = Array.isArray(response.data) ? response.data : [];
          })
          .catch((error) => {
            console.error('Erreur lors de la récupération des aliments:', error);
            this.aliments = [];
          });
      },

      fetchLow() {
  axios.get(`${import.meta.env.VITE_API_BASE_URL}/stocks/low`)
    .then((response) => {
      this.lowStocks = Array.isArray(response.data) ? response.data : [];
      console.log('Stocks faibles récupérés:', this.lowStocks);
    })
    .catch((error) => {
      console.error('Erreur lors de la récupération des stocks faibles:', error);
      this.lowStocks = [];
    });
},

getPaginatedData() {
    const dataMap = {
      aliments: this.aliments,
      stocks: this.stocks,
      entrees: this.entrees,
      sorties: this.sorties,
      fournisseurs: this.fournisseurs,
    };

    const dataList = dataMap[this.activeTab] || [];
    const start = (this.currentPage - 1) * this.itemsPerPage;
    const end = start + this.itemsPerPage;

    return {
      items: dataList.slice(start, end),
      totalPages: Math.ceil(dataList.length / this.itemsPerPage),
      totalItems: dataList.length,
    };
  },

      fetchStocks() {
        axios.get(`${import.meta.env.VITE_API_BASE_URL}/stocks`)
          .then((response) => {
            this.stocks = Array.isArray(response.data) ? response.data : [];
          })
          .catch((error) => {
            console.error('Erreur lors de la récupération des stocks:', error);
            this.stocks = [];
          });
      },

      fetchEntrees() {
        axios.get(`${import.meta.env.VITE_API_BASE_URL}/entrees`)
          .then((response) => {
            this.entrees = Array.isArray(response.data) ? response.data : [];
          })
          .catch((error) => {
            console.error('Erreur lors de la récupération des entrées:', error);
            this.entrees = [];
          });
      },

      fetchSorties() {
        axios.get(`${import.meta.env.VITE_API_BASE_URL}/sorties`)
          .then((response) => {
            this.sorties = Array.isArray(response.data) ? response.data : [];
          })
          .catch((error) => {
            console.error('Erreur lors de la récupération des sorties:', error);
            this.sorties = [];
          });
      },

      fetchFournisseurs() {
        axios.get(`${import.meta.env.VITE_API_BASE_URL}/fournisseurs`)
          .then((response) => {
            this.fournisseurs = Array.isArray(response.data) ? response.data : [];
          })
          .catch((error) => {
            console.error('Erreur lors de la récupération des fournisseurs:', error);
            this.fournisseurs = [];
          });
      },

      
      // Modal handling
      openAlimentModal() {
        this.aliment = { nom: '', categorie: '', prix_unitaire: 0 };
        this.showAlimentModal = true;
      },

      closeAlimentModal() {
        this.showAlimentModal = false;
      },

      openStockModal() {
        this.stock = { aliment_id: '', quantite: 0, date_entree: '' };
        this.showStockModal = true;
      },

      closeStockModal() {
        this.showStockModal = false;
      },

      openEntreeModal() {
        this.entree = { aliment_id: '', quantite: 0, fournisseur_id: '' };
        this.showEntreeModal = true;
      },

      closeEntreeModal() {
        this.showEntreeModal = false;
      },

      openSortieModal() {
        this.sortie = { aliment_id: '', quantite: 0, date_sortie: ''  };
        this.showSortieModal = true;
      },

      closeSortieModal() {
        this.showSortieModal = false;
      },

      openFournisseurModal() {
        this.fournisseur = { nom: '',  contact: '' };
        this.showFournisseurModal = true;
      },

      closeFournisseurModal() {
        this.showFournisseurModal = false;
      },

      // Submit methods for adding or updating records
      submitAliment() {
        this.isSubmitting = true;
        if (this.aliment.id) {
          axios.put(`${import.meta.env.VITE_API_BASE_URL}/aliments/${this.aliment.id}`, this.aliment)
            .then(() => {
              this.fetchAliments();
              this.closeAlimentModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout de l\'aliment:', error);
              this.isSubmitting = false;
            });
        } else {
          axios.post(`${import.meta.env.VITE_API_BASE_URL}/aliments`, this.aliment)
            .then(() => {
              this.fetchAliments();
              this.closeAlimentModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout de l\'aliment:', error);
              this.isSubmitting = false;
            });
        }
      },

      submitStock() {
  this.isSubmitting = true;
  console.log('Données envoyées:', this.stock); // Log des données avant l'envoi

  // Assurez-vous que `aliment` est un ID et non un objet complet
  const stockData = {
    aliment_id: this.stock.aliment, // Assurez-vous que c'est l'ID de l'aliment
    quantite: this.stock.quantite,
    date_entree: this.stock.date_entree, // Assurez-vous que la date est au format 'YYYY-MM-DD'
  };

  if (this.stock.id) {
    // Mise à jour d'un stock existant
    axios.put(`${import.meta.env.VITE_API_BASE_URL}/stocks/${this.stock.id}`, stockData)
      .then(() => {
        this.fetchStocks();
        this.closeStockModal();
      })
      .catch((error) => {
        console.error('Erreur lors de l\'ajout du stock:', error);
        this.isSubmitting = false;
      });
  } else {
    // Ajout d'un nouveau stock
    axios.post(`${import.meta.env.VITE_API_BASE_URL}/stocks`, stockData)
      .then(() => {
        this.fetchStocks();
        this.closeStockModal();
      })
      .catch((error) => {
        console.error('Erreur lors de l\'ajout du stock:', error);
        this.isSubmitting = false;
      });
  }
},


submitEntree() {
  console.log("Données envoyées : ", this.entree); // Ajouter un log pour vérifier les données
  this.isSubmitting = true;
  if (this.entree.id) {
    axios.put(`${import.meta.env.VITE_API_BASE_URL}/entrees/${this.entree.id}`, this.entree)
      .then(() => {
        this.fetchEntrees();
        this.closeEntreeModal();
      })
      .catch((error) => {
        console.error('Erreur lors de l\'ajout de l\'entrée:', error);
        this.isSubmitting = false;
      });
  } else {
    axios.post(`${import.meta.env.VITE_API_BASE_URL}/entrees`, this.entree)
      .then(() => {
        this.fetchEntrees();
        this.closeEntreeModal();
      })
      .catch((error) => {
        console.error('Erreur lors de l\'ajout de l\'entrée:', error);
        this.isSubmitting = false;
      });
  }
},

      submitSortie() {
        this.isSubmitting = true;
        if (this.sortie.id) {
          axios.put(`${import.meta.env.VITE_API_BASE_URL}/sorties/${this.sortie.id}`, this.sortie)
            .then(() => {
              this.fetchSorties();
              this.closeSortieModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout de la sortie:', error);
              this.isSubmitting = false;
            });
        } else {
          axios.post(`${import.meta.env.VITE_API_BASE_URL}/sorties`, this.sortie)
            .then(() => {
              this.fetchSorties();
              this.closeSortieModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout de la sortie:', error);
              this.isSubmitting = false;
            });
        }
      },

      submitFournisseur() {
        this.isSubmitting = true;
        if (this.fournisseur.id) {
          axios.put(`${import.meta.env.VITE_API_BASE_URL}/fournisseurs/${this.fournisseur.id}`, this.fournisseur)
            .then(() => {
              this.fetchFournisseurs();
              this.closeFournisseurModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout du fournisseur:', error);
              this.isSubmitting = false;
            });
        } else {
          axios.post(`${import.meta.env.VITE_API_BASE_URL}/fournisseurs`, this.fournisseur)
            .then(() => {
              this.fetchFournisseurs();
              this.closeFournisseurModal();
            })
            .catch((error) => {
              console.error('Erreur lors de l\'ajout du fournisseur:', error);
              this.isSubmitting = false;
            });
        }
      },

      // Delete methods for removing records
      deleteAliment(id) {
        axios.delete(`${import.meta.env.VITE_API_BASE_URL}/aliments/${id}`)
          .then(() => {
            this.fetchAliments();
          })
          .catch((error) => {
            console.error('Erreur lors de la suppression de l\'aliment:', error);
          });
      },

      deleteStock(id) {
        axios.delete(`${import.meta.env.VITE_API_BASE_URL}/stocks/${id}`)
          .then(() => {
            this.fetchStocks();
          })
          .catch((error) => {
            console.error('Erreur lors de la suppression du stock:', error);
          });
      },

      deleteEntree(id) {
        axios.delete(`${import.meta.env.VITE_API_BASE_URL}/entrees/${id}`)
          .then(() => {
            this.fetchEntrees();
          })
          .catch((error) => {
            console.error('Erreur lors de la suppression de l\'entrée:', error);
          });
      },

      deleteSortie(id) {
        axios.delete(`${import.meta.env.VITE_API_BASE_URL}/sorties/${id}`)
          .then(() => {
            this.fetchSorties();
          })
          .catch((error) => {
            console.error('Erreur lors de la suppression de la sortie:', error);
          });
      },



      // Mettre à jour le stock après une entrée
  updateStockAfterEntree(alimentId, quantite) {
    this.isSubmitting = true;
    
    // Faire une requête POST ou PUT à l'API pour mettre à jour le stock après une entrée
    axios.post(`${import.meta.env.VITE_API_BASE_URL}/stocks/update-after-entree/${alimentId}`, {
      quantite: quantite
    })
    .then((response) => {
      // Mettre à jour la liste des stocks après la réponse
      this.fetchStocks();
      this.isSubmitting = false;
    })
    .catch((error) => {
      console.error('Erreur lors de la mise à jour du stock après une entrée:', error);
      this.isSubmitting = false;
    });
  },

  // Mettre à jour le stock après une sortie
  updateStockAfterSortie(alimentId, quantite) {
    this.isSubmitting = true;
    
    // Faire une requête POST ou PUT à l'API pour mettre à jour le stock après une sortie
    axios.post(`${import.meta.env.VITE_API_BASE_URL}/stocks/update-after-sortie/${alimentId}`, {
      quantite: quantite
    })
    .then((response) => {
      // Mettre à jour la liste des stocks après la réponse
      this.fetchStocks();
      this.isSubmitting = false;
    })
    .catch((error) => {
      console.error('Erreur lors de la mise à jour du stock après une sortie:', error);
      this.isSubmitting = false;
    });
  },

  editAliment(aliment) {
    this.editingAliment = true;
    this.aliment = { ...aliment }; // Cloner l'objet
    this.showAlimentModal = true;
  },

  editStock(item) {
    this.stock = { ...item };
    this.editingStock = true;
    this.showStockModal = true;
  },


  editEntree(item) {
    this.entree = { ...item };
    this.editingEntree = true;
    this.showEntreeModal = true;
  },

  editSortie(item) {
    this.sortie = { ...item };
    this.editingSortie = true;
    this.showSortieModal = true;
  },

  editFournisseur(item) {
    this.fournisseur = { ...item };
    this.editingFournisseur = true;
    this.showFournisseurModal = true;
  },
      deleteFournisseur(id) {
        axios.delete(`${import.meta.env.VITE_API_BASE_URL}/fournisseurs/${id}`)
          .then(() => {
            this.fetchFournisseurs();
          })
          .catch((error) => {
            console.error('Erreur lors de la suppression du fournisseur:', error);
          });
      }
    }
  };
</script>