<template>
    <div class="flex h-screen bg-gray-100">
      <!-- Sidebar -->
<div class="w-64 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-700 text-white p-6 shadow-lg">
  <!-- Titre avec icône -->
  <h2 class="text-2xl font-bold mb-8 text-center flex items-center justify-center space-x-2 text-teal-400">
    <i class="fas fa-briefcase text-teal-300"></i>
    <span>Gestion Commerciale</span>
  </h2>

  <!-- Navigation -->
  <ul class="space-y-4 text-sm">
    <!-- Clients -->
    <li>
      <button
        @click="currentSection = 'clients'"
        class="w-full text-left px-4 py-2 rounded flex items-center transition hover:bg-yellow-500 hover:text-gray-900"
        aria-label="Voir les clients"
      >
        <i class="fas fa-users mr-3 text-yellow-400"></i> Clients
      </button>
    </li>

    <!-- Types de Poulets -->
    <li>
      <button
        @click="currentSection = 'chickenTypes'"
        class="w-full text-left px-4 py-2 rounded flex items-center transition hover:bg-pink-500 hover:text-white"
        aria-label="Voir les types de poulets"
      >
        <i class="fas fa-drumstick-bite mr-3 text-pink-400"></i> Types de Poulets
      </button>
    </li>

    <!-- Abonnements -->
    <li>
      <button
        @click="currentSection = 'subscriptions'"
        class="w-full text-left px-4 py-2 rounded flex items-center transition hover:bg-blue-500 hover:text-white"
        aria-label="Voir les abonnements"
      >
        <i class="fas fa-cogs mr-3 text-blue-400"></i> Abonnements
      </button>
    </li>

    <!-- Commandes -->
    <li>
      <button
        @click="currentSection = 'orders'"
        class="w-full text-left px-4 py-2 rounded flex items-center transition hover:bg-green-500 hover:text-white"
        aria-label="Voir les commandes"
      >
        <i class="fas fa-shopping-cart mr-3 text-green-400"></i> Commandes
      </button>
    </li>
  </ul>
</div>

  
      <!-- Main Content -->
      <div class="flex-1 p-8">
      <!-- Clients Section -->
<div v-if="currentSection === 'clients'">
  <section class="mb-12">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Clients</h2>

    <!-- Bouton Ajouter un Client -->
    <button 
      @click="openClientModal" 
      class="bg-blue-600 text-white px-6 py-2 rounded-full mb-6 hover:bg-blue-700 transition duration-200 flex items-center" 
      aria-label="Ajouter un client"
    >
      <i class="fas fa-plus mr-2"></i> Ajouter un Client
    </button>

    <!-- Table des clients -->
    <table class="min-w-full table-auto border-collapse shadow-md rounded-lg bg-white">
      <thead>
        <tr class="bg-gray-200 text-sm text-gray-700 uppercase">
          <th class="px-4 py-3 text-left font-semibold">Nom</th>
          <th class="px-4 py-3 text-left font-semibold">Email</th>
          <th class="px-4 py-3 text-left font-semibold">Téléphone</th>
          <th class="px-4 py-3 text-left font-semibold">Adresse</th> 
          <th class="px-4 py-3 text-left font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="client in getPaginatedItems(clients, 'clients')" 
          :key="client.id" 
          class="border-t hover:bg-gray-50"
        >
          <td class="px-4 py-3 text-sm">{{ client.name }}</td>
          <td class="px-4 py-3 text-sm">{{ client.email }}</td>
          <td class="px-4 py-3 text-sm">{{ client.phone }}</td>
          <td class="px-4 py-3 text-sm">{{ client.address }}</td>
          <td class="px-4 py-3 text-sm">
            <button 
              @click="editClient(client)" 
              class="text-yellow-500 hover:text-yellow-700 focus:outline-none transition duration-200" 
              aria-label="Éditer ce client"
            >
              <i class="fas fa-edit text-lg"></i>
            </button>
            <button 
              @click="confirmDeleteClient(client.id)" 
              class="text-red-500 hover:text-red-700 ml-4 focus:outline-none transition duration-200" 
              aria-label="Supprimer ce client"
            >
              <i class="fas fa-trash-alt text-lg"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
  <!-- Bouton Précédent -->
  <button
    @click="pagination.chickenTypes.page--"
    :disabled="pagination.chickenTypes.page === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages dynamiques -->
  <button
    v-for="page in getTotalPages(chickenTypes, 'chickenTypes')"
    :key="page"
    @click="pagination.chickenTypes.page = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': pagination.chickenTypes.page === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': pagination.chickenTypes.page !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="pagination.chickenTypes.page++"
    :disabled="pagination.chickenTypes.page >= getTotalPages(chickenTypes, 'chickenTypes')"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>

  </section>
</div>


  
     <!-- Types de Poulets Section -->
<div v-if="currentSection === 'chickenTypes'" class="container mx-auto p-6">
  <section class="mb-12">
    <h2 class="text-3xl font-semibold mb-6 text-gray-800">Types de Poulets</h2>

    <button @click="openChickenTypeModal"
            class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition duration-300 flex items-center mb-6" 
            aria-label="Ajouter un type de poulet">
      <i class="fas fa-plus mr-3"></i> Ajouter un Type de Poulet
    </button>

    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
      <table class="min-w-full table-auto">
        <thead class="bg-indigo-100 text-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
            <th class="px-6 py-3 text-left text-sm font-medium">Description</th>
            <th class="px-6 py-3 text-left text-sm font-medium">Prix (FCFA)</th>
            <th class="px-6 py-3 text-left text-sm font-medium">Type</th>
            <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600">
          <tr 
            v-for="chickenType in getPaginatedItems(chickenTypes, 'chickenTypes')" 
            :key="chickenType.id" 
            class="border-b hover:bg-indigo-50"
          >
            <td class="px-6 py-4 text-sm font-medium">{{ chickenType.name }}</td>
            <td class="px-6 py-4 text-sm">{{ chickenType.description }}</td>
            <td class="px-6 py-4 text-sm">{{ chickenType.price }} FCFA</td>
            <td class="px-6 py-4 text-sm capitalize">{{ chickenType.type }}</td>
            <td class="px-6 py-4 text-center">
              <button @click="editChickenType(chickenType)" 
                      class="text-indigo-600 hover:text-indigo-800 focus:outline-none transition duration-200"
                      aria-label="Éditer ce type de poulet">
                <i class="fas fa-edit"></i>
              </button>
              <button @click="confirmDeleteChickenType(chickenType.id)" 
                      class="text-red-600 hover:text-red-800 ml-4 focus:outline-none transition duration-200"
                      aria-label="Supprimer ce type de poulet">
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
    @click="pagination.chickenTypes.page--"
    :disabled="pagination.chickenTypes.page === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages dynamiques -->
  <button
    v-for="page in getTotalPages(chickenTypes, 'chickenTypes')"
    :key="page"
    @click="pagination.chickenTypes.page = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': pagination.chickenTypes.page === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': pagination.chickenTypes.page !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="pagination.chickenTypes.page++"
    :disabled="pagination.chickenTypes.page >= getTotalPages(chickenTypes, 'chickenTypes')"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>

  </section>
</div>


  <!-- Section Abonnements -->
<div v-if="currentSection === 'subscriptions'">
  <section class="mb-12">
    <h2 class="text-2xl font-semibold mb-4">Abonnements</h2>

    <!-- Bouton Ajouter un abonnement -->
    <button
      @click="openSubscriptionModal"
      class="bg-blue-500 text-white px-6 py-3 rounded-lg mb-6 flex items-center hover:bg-blue-600 transition duration-300"
      aria-label="Ajouter un abonnement"
    >
      <i class="fas fa-plus mr-2"></i> Ajouter un Abonnement
    </button>

    <!-- Recherche -->
    <div class="mb-4 max-w-md">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Rechercher un client, un type de poulet ou un téléphone..."
        class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
    </div>

    <!-- Chargement / Erreur -->
    <div v-if="isLoading" class="text-center text-gray-500 my-4">
      Chargement des abonnements...
    </div>
    
    <div v-if="error" class="text-red-500 text-center my-4">
      {{ error }}
    </div>

    <!-- Tableau des abonnements -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full table-auto divide-y divide-gray-200">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold">Client</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Téléphone</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Types de Poulet</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Quantités</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Prix Total</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          <tr
            v-for="subscription in getPaginatedItems(subscriptions, 'subscriptions')"
            :key="subscription.id"
            class="hover:bg-gray-50 transition"
          >
            <!-- Client -->
            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
              {{ subscription.client.name }}
            </td>

            <!-- Téléphone -->
            <td class="px-6 py-4 text-sm text-gray-600">
              {{ subscription.client.phone }}
            </td>

            <!-- Types de poulet -->
            <td class="px-6 py-4 text-sm text-gray-600">
              <ul class="list-disc ml-4">
                <li v-for="type in subscription.chicken_types" :key="type.id">
                  {{ type.name }}
                </li>
              </ul>
            </td>

            <!-- Quantités -->
            <td class="px-6 py-4 text-sm text-gray-600">
              <ul class="list-disc ml-4">
                <li v-for="type in subscription.chicken_types" :key="type.id">
                  {{ type.pivot.quantity }} pièce(s)
                </li>
              </ul>
            </td>

            <!-- Prix Total -->
            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
              {{ subscription.price }} FCFA
            </td>

            <!-- Actions -->
            <td class="px-6 py-4 flex space-x-4">
              <button
                @click="editSubscription(subscription)"
                class="text-blue-600 hover:text-blue-800"
                aria-label="Éditer cet abonnement"
                title="Éditer"
              >
                <i class="fas fa-pen-to-square fa-lg"></i>
              </button>

              <button
                @click="confirmDeleteSubscription(subscription.id)"
                class="text-red-600 hover:text-red-800"
                aria-label="Supprimer cet abonnement"
                title="Supprimer"
              >
                <i class="fas fa-trash fa-lg"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
  <!-- Bouton Précédent -->
  <button
    @click="pagination.chickenTypes.page--"
    :disabled="pagination.chickenTypes.page === 1"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    ← Précédent
  </button>

  <!-- Numéros de pages dynamiques -->
  <button
    v-for="page in getTotalPages(chickenTypes, 'chickenTypes')"
    :key="page"
    @click="pagination.chickenTypes.page = page"
    class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
    :class="{
      'bg-blue-600 text-white border-blue-600 shadow-md': pagination.chickenTypes.page === page,
      'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': pagination.chickenTypes.page !== page
    }"
  >
    {{ page }}
  </button>

  <!-- Bouton Suivant -->
  <button
    @click="pagination.chickenTypes.page++"
    :disabled="pagination.chickenTypes.page >= getTotalPages(chickenTypes, 'chickenTypes')"
    class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
  >
    Suivant →
  </button>
</div>

  </section>
</div>


  
<div v-if="currentSection === 'orders'" class="container mx-auto">
  <section class="mb-12">
    <h2 class="text-2xl font-semibold mb-4">Commandes</h2>

    <button @click="openOrderModal" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 flex items-center" aria-label="Ajouter une commande">
      <i class="fas fa-plus mr-2"></i> Ajouter une Commande
    </button>

    <table class="min-w-full table-auto border-collapse bg-white shadow-md rounded-lg overflow-hidden">
      <thead class="bg-gray-100 text-gray-700 text-left">
        <tr>
          <th class="px-6 py-3 text-sm font-semibold">Client</th>
          <th class="px-6 py-3 text-sm font-semibold">Téléphone</th>
          <th class="px-6 py-3 text-sm font-semibold">Types de Poulet</th>
          <th class="px-6 py-3 text-sm font-semibold">Quantités</th>
          <th class="px-6 py-3 text-sm font-semibold">Type de Livraison</th>
          <th class="px-6 py-3 text-sm font-semibold">Date/Heure Livraison</th>
          <th class="px-6 py-3 text-sm font-semibold">Prix Total</th>
          <th class="px-6 py-3 text-sm font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="order in getPaginatedItems(orders, 'orders')"
          :key="order.id"
          :class="[
            'border-b transition hover:bg-gray-50',
            isExpressActive(order) ? 'bg-red-100' : ''
          ]"
        >
          <td class="px-6 py-4 text-sm text-gray-800 font-medium">
            {{ order.client.name }}
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">
            {{ order.client.phone }}
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">
            <ul class="list-disc ml-4">
              <li v-for="type in order.chicken_types" :key="type.id">
                {{ type.name }}
              </li>
            </ul>
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">
            <ul class="list-disc ml-4">
              <li v-for="type in order.chicken_types" :key="type.id">
                {{ type.pivot.quantity }} pièce(s)
              </li>
            </ul>
          </td>

          <td class="px-6 py-4 text-sm text-gray-600 capitalize">
            {{ order.delivery_type }}
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">
            {{ new Date(order.scheduled_time).toLocaleString() }}
          </td>

          <td class="px-6 py-4 text-sm font-semibold text-gray-800">
            {{ order.price }} FCFA
          </td>

          <td class="px-6 py-4 flex space-x-4">
            <button @click="editOrder(order)" class="text-blue-600 hover:text-blue-800" aria-label="Éditer cette commande" title="Éditer">
              <i class="fas fa-pen-to-square fa-lg"></i>
            </button>
            <button @click="confirmDeleteOrder(order.id)" class="text-red-600 hover:text-red-800" aria-label="Supprimer cette commande" title="Supprimer">
              <i class="fas fa-trash-alt fa-lg"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination stylée pour commandes -->
    <div class="mt-6 flex flex-wrap justify-center items-center gap-2 sm:gap-3">
      <button
        @click="pagination.orders.page--"
        :disabled="pagination.orders.page === 1"
        class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        ← Précédent
      </button>

      <button
        v-for="page in getTotalPages(orders, 'orders')"
        :key="page"
        @click="pagination.orders.page = page"
        class="w-10 h-10 flex items-center justify-center rounded-full font-medium border transition"
        :class="{
          'bg-blue-600 text-white border-blue-600 shadow-md': pagination.orders.page === page,
          'bg-white text-gray-700 border-gray-300 hover:bg-gray-100': pagination.orders.page !== page
        }"
      >
        {{ page }}
      </button>

      <button
        @click="pagination.orders.page++"
        :disabled="pagination.orders.page >= getTotalPages(orders, 'orders')"
        class="px-4 py-2 rounded-full bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Suivant →
      </button>
    </div>

  </section>
</div>


  
        <!-- Modal pour confirmation de suppression -->
        <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold mb-4">Êtes-vous sûr ?</h3>
            <p class="mb-4">Voulez-vous vraiment supprimer cet élément ? Cette action est irréversible.</p>
            <div class="flex justify-end space-x-4">
              <button @click="closeConfirmModal" class="bg-gray-500 text-white px-4 py-2 rounded">Annuler</button>
              <button @click="deleteItem" class="bg-red-500 text-white px-4 py-2 rounded">Supprimer</button>
            </div>
          </div>
        </div>
  
        <!-- Notifications pour les erreurs et succès -->
        <div v-if="notification" :class="notification.type === 'error' ? 'bg-red-500' : 'bg-green-500'" class="fixed bottom-0 right-0 m-4 p-4 text-white rounded-lg">
          <p>{{ notification.message }}</p>
        </div>
      </div>
  
      <!-- Modals pour Clients, Poulets, Abonnements, Commandes -->
  
      <!-- Modal pour Ajouter/Éditer Client -->
<div v-if="showClientModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h3 class="text-xl font-semibold mb-4">{{ clientForm.id ? 'Éditer un Client' : 'Ajouter un Client' }}</h3>
    <form @submit.prevent="saveClient">
      
      <!-- Champ Nom -->
      <label class="block mb-2">Nom</label>
      <input v-model="clientForm.name" type="text" class="w-full px-4 py-2 border rounded" required />
      
      <!-- Champ Email -->
      <label class="block mb-2 mt-4">Email</label>
      <input v-model="clientForm.email" type="email" class="w-full px-4 py-2 border rounded" required />
      
      <!-- Champ Téléphone -->
      <label class="block mb-2 mt-4">Téléphone</label>
      <input v-model="clientForm.phone" type="tel" class="w-full px-4 py-2 border rounded" required />

      <!-- Champ Adresse -->
      <label class="block mb-2 mt-4">Adresse</label>
      <input v-model="clientForm.address" type="text" class="w-full px-4 py-2 border rounded" />

      <!-- Boutons d'action -->
      <div class="flex justify-end space-x-4 mt-4">
        <button @click="closeClientModal" class="bg-gray-500 text-white px-4 py-2 rounded">Annuler</button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

  
    <!-- Modal pour Ajouter/Éditer un Type de Poulet -->
<div v-if="showChickenTypeModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full h-auto max-h-[90vh] overflow-y-auto">
    <h3 class="text-xl font-semibold mb-4 text-center">{{ editingChickenType ? 'Éditer' : 'Ajouter' }} un Type de Poulet</h3>
    <form @submit.prevent="saveChickenType">
      <!-- Champ Nom -->
      <label class="block mb-2 font-medium text-gray-700">Nom</label>
      <input v-model="chickenTypeForm.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required />

      <!-- Champ Description -->
      <label class="block mb-2 mt-4 font-medium text-gray-700">Description</label>
      <input v-model="chickenTypeForm.description" type="text" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />

      <!-- Champ Type -->
      <label class="block mb-2 mt-4 font-medium text-gray-700">Type</label>
      <select v-model="chickenTypeForm.type" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required @change="calculatePrice">
        <option value="frais">Frais</option>
        <option value="roti">Rôti</option>
        <option value="fume">Fumé</option>
      </select>

      <!-- Champ Poids -->
      <label class="block mb-2 mt-4 font-medium text-gray-700">Poids (kg)</label>
<select v-model="chickenTypeForm.weight" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required @change="calculatePrice">
  <option value="0.8">0.8 kg</option>
  <option value="0.9">0.9 kg</option>
  <option value="1.0">1.0 kg</option>
  <option value="1.2">1.2 kg</option>
  <option value="1.5">1.5 kg</option>
</select>


     <!-- Champ Prix Calculé -->
<label class="block mb-2 mt-4 font-medium text-gray-700">Prix</label>
<input v-model="chickenTypeForm.price" type="text" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" disabled />


      <!-- Boutons d'action -->
      <div class="flex justify-end space-x-4 mt-4">
        <button @click="closeChickenTypeModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-200">Annuler</button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">{{ editingChickenType ? 'Mettre à jour' : 'Ajouter' }}</button>
      </div>
    </form>
  </div>
</div>



 <!-- Modal pour Ajouter/Éditer un Abonnement -->
<div v-if="showSubscriptionModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-96 overflow-y-auto max-h-[80vh]">
    <h3 class="text-xl font-semibold mb-4">
      {{ editingSubscription ? 'Éditer' : 'Ajouter' }} un Abonnement
    </h3>

    <form @submit.prevent="saveSubscription">
      <!-- Client -->
      <div class="mb-4">
        <label class="block mb-1">Client</label>
        <select v-model="subscriptionForm.client_id" class="w-full border px-3 py-2 rounded">
          <option value="" disabled>Sélectionner un client</option>
          <option v-for="client in clients" :key="client.id" :value="client.id">
            {{ client.name }}
          </option>
        </select>
      </div>

      <!-- Chicken Types -->
      <div v-for="type in chickenTypes" :key="type.id" class="flex items-center gap-2 mb-2">
        <input type="checkbox" :id="'chk-' + type.id" :value="type.id"
               v-model="selectedChickenIds" @change="toggleChicken(type)">
        <label :for="'chk-' + type.id" class="flex-1 text-sm">
          {{ type.name }} ({{ type.weight }}kg) - {{ type.price }} €
        </label>

        <input v-if="subscriptionForm.chicken_types[type.id]"
               v-model.number="subscriptionForm.chicken_types[type.id].quantity"
               type="number" min="1" class="w-16 border px-2 py-1 rounded"
               placeholder="Qté" />
      </div>

      <!-- Description -->
      <div class="mb-4">
        <label class="block mb-1">Description</label>
        <textarea v-model="subscriptionForm.description" class="w-full border px-3 py-2 rounded"></textarea>
      </div>

      <!-- Dates -->
      <div class="mb-4">
        <label class="block mb-1">Date de début</label>
        <input type="date" v-model="subscriptionForm.start_date" class="w-full border px-3 py-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Date de fin</label>
        <input type="date" v-model="subscriptionForm.end_date" class="w-full border px-3 py-2 rounded" />
      </div>

      <!-- Type abonnement -->
      <div class="mb-4">
        <label class="block mb-1">Type d'abonnement</label>
        <select v-model="subscriptionForm.subscription_type" class="w-full border px-3 py-2 rounded">
          <option value="weekly">Hebdomadaire</option>
          <option value="monthly">Mensuel</option>
          <option value="bi-monthly">Bimensuel</option>
          <option value="quarterly">Trimestriel</option>
          <option value="semi-annual">Semestriel</option>
        </select>
      </div>

      <!-- Adresse -->
      <div class="mb-4">
        <label class="block mb-1">Adresse de livraison</label>
        <input v-model="subscriptionForm.delivery_address" type="text" class="w-full border px-3 py-2 rounded" />
      </div>

      <!-- Prix estimé -->
      <div class="mb-4 text-right font-semibold">
        Prix estimé : {{ totalPrice.toFixed(2) }} €
      </div>

      <!-- Boutons -->
      <div class="flex justify-between">
        <button type="button" @click="closeSubscriptionModal" class="bg-gray-400 text-white px-4 py-2 rounded">
          Annuler
        </button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
          {{ editingSubscription ? 'Mettre à jour' : 'Créer' }}
        </button>
      </div>
    </form>
  </div>
</div>


  
 <!-- Modal de Création/Édition de Commande -->
<div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto">
    <h3 class="text-xl font-semibold mb-4 text-center">
      {{ editingOrder ? 'Éditer' : 'Ajouter' }} une Commande
    </h3>

    <form @submit.prevent="saveOrder" class="space-y-4">
      <!-- Client -->
      <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Client</label>
        <select v-model="orderForm.client_id" class="w-full border px-3 py-2 rounded">
          <option value="" disabled>Sélectionner un client</option>
          <option v-for="client in clients" :key="client.id" :value="client.id">
            {{ client.name }}
          </option>
        </select>
      </div>

      <!-- Chicken Types -->
      <div>
        <label class="block mb-2 text-sm font-medium text-gray-700">Types de Poulet</label>
        <div v-for="type in chickenTypes" :key="type.id" class="flex items-center gap-2 mb-2">
          <input type="checkbox"
                 :id="'chk-' + type.id"
                 :value="type.id"
                 v-model="selectedOrderChickenIds"
                 @change="toggleOrderChicken(type)" />
          
          <label :for="'chk-' + type.id" class="flex-1 text-sm">
            {{ type.name }} ({{ type.weight }}kg) - {{ type.price }} €
          </label>

          <input v-if="orderForm.chicken_types[type.id]"
                 v-model.number="orderForm.chicken_types[type.id].quantity"
                 type="number" min="1"
                 class="w-16 border px-2 py-1 rounded"
                 placeholder="Qté" />
        </div>
      </div>

      <!-- Adresse -->
      <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Adresse de Livraison</label>
        <input v-model="orderForm.delivery_address" type="text" class="w-full border px-3 py-2 rounded" placeholder="Adresse de livraison" />
      </div>

      <!-- Date commande -->
      <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Date de Commande</label>
        <input v-model="orderForm.order_date" type="date" class="w-full border px-3 py-2 rounded" />
      </div>

      <!-- Type de livraison -->
      <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Type de livraison</label>
        <select v-model="orderForm.delivery_type" class="w-full border px-3 py-2 rounded">
          <option value="express">Express (2h)</option>
          <option value="scheduled">Différée</option>
        </select>
      </div>

      <!-- Date/heure livraison si différée -->
      <div v-if="orderForm.delivery_type === 'scheduled'">
        <label class="block mb-1 text-sm font-medium text-gray-700">Date et heure de livraison</label>
        <input type="datetime-local" v-model="orderForm.scheduled_time" class="w-full border px-3 py-2 rounded" />
      </div>

      <!-- Statut -->
      <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Statut</label>
        <select v-model="orderForm.status" class="w-full border px-3 py-2 rounded">
          <option value="pending">En Attente</option>
          <option value="shipped">Complétée</option>
          <option value="delivered">Annulée</option>
        </select>
      </div>

      <!-- Prix Total -->
      <div class="text-right font-semibold text-gray-800">
        Prix total : {{ totalPrice.toFixed(2) }} €
      </div>

      <!-- Boutons principaux -->
      <div class="flex justify-between items-center mt-6 gap-4">
        <!-- ❌ Bouton Annuler -->
        <button
          type="button"
          @click="closeOrderModal"
          class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-medium hover:bg-gray-300 transition"
        >
          <i class="fas fa-times-circle"></i>
          Annuler
        </button>

        <!-- ✅ Créer / Mettre à jour -->
        <button
          type="submit"
          class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
        >
          <i :class="editingOrder ? 'fas fa-save' : 'fas fa-plus-circle'"></i>
          {{ editingOrder ? 'Mettre à jour' : 'Créer' }}
        </button>
      </div>

      <!-- ✅ Message de succès et actions -->
      <transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
      >
        <div v-if="orderSaved" class="mt-6 p-5 rounded-xl bg-green-50 border border-green-200 shadow-sm">
          <!-- Message -->
          <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <p class="text-green-800 font-semibold text-sm sm:text-base">
              Commande enregistrée avec succès ! Vous pouvez maintenant effectuer le paiement.
            </p>
          </div>

          <!-- Actions Paiement -->
          <div class="flex flex-col sm:flex-row gap-3">
            <!-- 💳 Payer -->
            <button
              id="pay-btn"
              @click="payOrder(orderForm)"
              type="button"
              class="flex-1 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-3 rounded-lg shadow-md transition duration-300"
            >
              <i class="fas fa-credit-card"></i>
              Payer maintenant
            </button>

            <!-- ❌ Fermer sans payer -->
            <button
              @click="closeOrderModal"
              type="button"
              class="flex-1 flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-4 py-3 rounded-lg transition duration-300"
            >
              <i class="fas fa-times"></i>
              Fermer sans payer
            </button>
          </div>
        </div>
      </transition>
    </form>
  </div>
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
      currentSection: 'clients',
      clients: [],
      chickenTypes: [],
      subscriptions: [],
      orderSaved: false,  // 👉 pour activer le bouton paiement
      orders: [],
      currentSection: 'clients',

      pagination: {
        clients: { page: 1, perPage: 3 },
        chickenTypes: { page: 1, perPage: 3 },
        subscriptions: { page: 1, perPage: 3 },
        orders: { page: 1, perPage: 3 },
        },
      isLoading: false,     // Statut de chargement
      error: null,          // Message d'erreur
      searchQuery: '',      // Requête de recherche
      selectedChickenIds: [], // Poulets sélectionnés
      selectedOrderChickenIds: [],
      isModalOpen: false,   // Modal ouvert
      showClientModal: false,
      showChickenTypeModal: false,
      showOrderModal: false,
      showSubscriptionModal: false,
      calculatedPrice: 0,    // Prix calculé
      totalPrice: 0,        // Prix total pour un abonnement
      clientForm: { id: '', name: '', email: '', phone: '', address: '' },
      chickenTypeForm: { id: '', name: '', description: '', price: '0', type: '', weight: '0.8' },
      subscriptionForm: {
        client_id: null,
        chicken_types: {},
        description: '',
        start_date: '',
        end_date: '',
        subscription_type: 'monthly',
        delivery_address: '',
      },
      orderForm: {
  client_id: null,
  chicken_types: {}, // tableau d’objets { id, quantity }
  delivery_address: '',
  order_date: '', // date de commande
  status: 'pending', // valeurs possibles : 'pending', 'completed', 'cancelled'
  delivery_type: 'scheduled', // 🆕
  scheduled_time: '',         // 🆕
},

      editingClient: false,
      editingChickenType: false,
      editingOrder: false,
      editingSubscription: false,
    };
  },
 
  mounted() {
    this.fetchClients();
    this.fetchChickenTypes();
    this.fetchOrders();
    this.fetchSubscriptions();
    this.loadFedaPayScript(); // ⬅ Charger le script ici
  },
  methods: {
    // Calculer le prix basé sur les données du type de poulet
    calculatePrice() {
      const prices = {
        frais: { 0.8: 5.00, 0.9: 5.50, 1.0: 6.00, 1.2: 7.00, 1.5: 8.00 },
        roti: { 0.8: 7.00, 0.9: 7.50, 1.0: 8.00, 1.2: 9.00, 1.5: 10.00 },
        fume: { 0.8: 9.00, 0.9: 9.50, 1.0: 10.00, 1.2: 11.00, 1.5: 12.00 }
      };

      const weight = parseFloat(this.chickenTypeForm.weight);
      if (isNaN(weight)) {
        this.chickenTypeForm.price = 0;
        return;
      }

      const price = prices[this.chickenTypeForm.type]?.[weight];
      this.chickenTypeForm.price = price || 0;
    },


    isExpressActive(order) {
    if (order.delivery_type !== 'express') return false;

    const now = new Date();
    const scheduled = new Date(order.scheduled_time);

    return scheduled > now;
  },

  // Charger le script FedaPay de manière asynchrone
  loadFedaPayScript() {
      return new Promise((resolve, reject) => {
        if (window.FedaPay) {
          return resolve();
        }
        
        const script = document.createElement('script');
        script.src = "https://cdn.fedapay.com/checkout.js?v=1.1.7";
        script.async = true;
        
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Échec du chargement du script FedaPay'));
        
        document.head.appendChild(script);
      });
    },

    getPaginatedItems(items, section) {
    const { page, perPage } = this.pagination[section];
    const start = (page - 1) * perPage;
    const end = page * perPage;
    return items.slice(start, end);
  },

  getTotalPages(items, section) {
    return Math.ceil(items.length / this.pagination[section].perPage);
  },

async debugPayNow() {
  console.log("🟡 Bouton paiement cliqué");
  console.log("📦 orderForm avant envoi :", this.orderForm);
  await this.payOrder(this.orderForm); // appel réel
},


// Méthode pour démarrer le paiement
async payOrder(order) {
  console.log("🚀 Début du processus de paiement pour la commande :", order);

  try {
    // Charger le script FedaPay avant de continuer
    await this.loadFedaPayScript();

    if (!window.FedaPay) {
      console.warn("⚠️ Le script FedaPay n'est pas encore disponible.");
      alert("Le script FedaPay n'est pas encore prêt.");
      return;
    }

    console.log("✅ Script FedaPay chargé et prêt à être utilisé.");

    // Vérifier si chicken_types existe et est un objet
    if (!this.orderForm.chicken_types || typeof this.orderForm.chicken_types !== 'object') {
      console.error("❌ Chicken types manquants ou mal formatés.");
      alert("La commande ne contient pas de types de poulet valides.");
      return;
    }

    // Calcul du prix total
    let totalAmount = 0;
    Object.entries(this.orderForm.chicken_types).forEach(([typeId, data]) => {
      const chickenType = this.chickenTypes.find(type => type.id === parseInt(typeId));
      if (chickenType) {
        totalAmount += chickenType.price * data.quantity;
      } else {
        console.warn(`❓ Type de poulet introuvable pour ID: ${typeId}`);
      }
    });

    this.totalPrice = totalAmount;
    console.log(`💰 Montant total à payer : ${this.totalPrice} XOF`);

    // Récupérer les informations du client
    const client = this.clients.find(c => c.id === this.orderForm.client_id);
    if (!client) {
      console.error("❌ Client introuvable.");
      alert("Client introuvable.");
      return;
    }

    console.log("👤 Client identifié :", client);

    // Initialisation de FedaPay pour le paiement
    FedaPay.init("#pay-btn", {
      public_key: "pk_sandbox_kMOS8KmU3c61OHo18AMCNw7J", // Remplace avec ta clé publique
      transaction: {
        amount: this.totalPrice,
        description: `Paiement de la commande pour ${client.name}`,
        currency: { iso: "XOF" },
        customer: {
          firstname: client.name,
          email: client.email || "client@example.com",
          phone_number: {
            number: client.phone,
            country: "BJ"
          }
        }
      }
    });

    console.log("🧾 FedaPay.init() déclenché avec succès");

  } catch (error) {
    console.error("🔥 Erreur dans payOrder :", error);
    alert("Une erreur s'est produite lors du paiement.");
  }
},

  



    // Fonction de filtrage selon la recherche
    filterSubscriptions() {
      if (!this.searchQuery) {
        return this.subscriptions;
      }
      return this.subscriptions.filter(subscription => {
        return (
          subscription.client.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          subscription.client.phone.includes(this.searchQuery) ||
          subscription.chicken_types.some(type => type.name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        );
      });
    },

    async fetchClients() {
      try {
        const response = await axios.get('/api/clients');
        this.clients = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des clients", error);
      }
    },
    async fetchChickenTypes() {
      try {
        const response = await axios.get('/api/chicken-types');
        this.chickenTypes = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des types de poulets", error);
      }
    },
    async fetchOrders() {
      try {
        const response = await axios.get('/api/orders');
        this.orders = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des commandes", error);
      }
    },
    async fetchSubscriptions() {
      this.isLoading = true;
      try {
        const response = await axios.get('/api/subscriptions');
        this.subscriptions = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des abonnements", error);
        this.error = "Impossible de charger les abonnements.";
      } finally {
        this.isLoading = false;
      }
    },

    // Calcul du prix total de l'abonnement
    calculateTotalPrice() {
      let total = 0;
      Object.keys(this.subscriptionForm.chicken_types).forEach(typeId => {
        const chickenType = this.chickenTypes.find(type => type.id === parseInt(typeId));
        if (chickenType) {
          total += chickenType.price * this.subscriptionForm.chicken_types[typeId].quantity;
        }
      });
      this.totalPrice = total;
    },

    // Ouvrir le modal pour ajouter ou éditer un abonnement
    openSubscriptionModal() {
      this.subscriptionForm = {
        client_id: null,
        chicken_types: {},
        description: '',
        start_date: '',
        end_date: '',
        subscription_type: 'monthly',
        delivery_address: '',
      };
      this.editingSubscription = false;
      this.showSubscriptionModal = true;
    },
    closeSubscriptionModal() {
      this.showSubscriptionModal = false;
    },

    // Modifier un abonnement existant
    editSubscription(subscription) {
      this.editingSubscription = true;
      this.subscriptionForm = {
        id: subscription.id,
        client_id: subscription.client_id,
        chicken_types: subscription.chicken_types.reduce((acc, type) => {
          acc[type.id] = { quantity: type.pivot.quantity };
          return acc;
        }, {}),
        description: subscription.description,
        start_date: subscription.start_date,
        end_date: subscription.end_date,
        subscription_type: subscription.subscription_type,
        delivery_address: subscription.delivery_address,
      };
      this.showSubscriptionModal = true;
    },


    // Modifier une commande existante
editOrder(order) {
  this.editingOrder = true;

  this.orderForm = {
    id: order.id,
    client_id: order.client_id,
    delivery_address: order.delivery_address,
    order_date: order.order_date,
    status: order.status || 'pending',
    chicken_types: order.chicken_types.map((type) => ({
      id: type.id,
      quantity: type.pivot.quantity
    }))
  };

  this.showOrderModal = true;
},


    // Ajouter ou retirer un type de poulet dans l'abonnement
    toggleChicken(type) {
      if (this.selectedChickenIds.includes(type.id)) {
        if (!this.subscriptionForm.chicken_types[type.id]) {
          this.subscriptionForm.chicken_types[type.id] = { quantity: 1 };
        }
      } else {
        delete this.subscriptionForm.chicken_types[type.id];
      }
      this.calculateTotalPrice();
    },

// Ajouter ou retirer un type de poulet dans la commande
toggleOrderChicken(type) {
  if (this.selectedOrderChickenIds.includes(type.id)) {
    if (!this.orderForm.chicken_types[type.id]) {
      this.orderForm.chicken_types[type.id] = { quantity: 1 };
    }
  } else {
    delete this.orderForm.chicken_types[type.id];
  }
  this.calculateTotalPrice(); // facultatif si tu veux recalculer à chaque modif
},


    // Sauvegarder ou mettre à jour l'abonnement
    async saveSubscription() {
      try {
        // 🔧 Transformation de l'objet chicken_types en tableau
        const formattedSubscription = {
          ...this.subscriptionForm,
          chicken_types: Object.entries(this.subscriptionForm.chicken_types).map(([id, data]) => ({
            id: parseInt(id),
            quantity: data.quantity
          }))
        };

        // 🛠 Debug : Afficher les données envoyées
        console.log('Payload envoyé à Laravel :', formattedSubscription);

        let response;
        if (this.editingSubscription) {
          response = await axios.put(`/api/subscriptions/${this.subscriptionForm.id}`, formattedSubscription);
        } else {
          response = await axios.post('/api/subscriptions', formattedSubscription);
        }

        // 🔄 Rafraîchir la liste des abonnements
        this.fetchSubscriptions();

        // ✅ Fermer le modal et notifier
        this.closeSubscriptionModal();
        alert('Abonnement sauvegardé avec succès!');
      } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error);
        if (error.response && error.response.status === 422) {
          console.error('Erreurs de validation Laravel :', error.response.data.errors);
        }
        alert('Une erreur s\'est produite lors de la sauvegarde.');
      }
    },

    // Ouvrir les modals pour les clients, types de poulet, etc.
    openClientModal() {
      this.clientForm = { id: '', name: '', email: '', phone: '', address: '' };
      this.editingClient = false;
      this.showClientModal = true;
    },
    closeClientModal() {
      this.showClientModal = false;
    },
    openChickenTypeModal() {
      this.chickenTypeForm = { id: '', name: '', description: '', price: '', type: '', weight: '' };
      this.editingChickenType = false;
      this.showChickenTypeModal = true;
    },
    closeChickenTypeModal() {
      this.showChickenTypeModal = false;
    },
    openOrderModal() {
      this.orderForm = {
  id: null, // null au lieu de chaîne vide, plus logique pour une clé primaire
  client_id: null,
  chicken_types: {}, // Ex: [{ id: 1, quantity: 2 }]
  delivery_address: '',
  order_date: '',
  status: 'pending', // Ajouté pour suivre le statut de la commande
  delivery_type: 'scheduled', // 🆕
  scheduled_time: '',         // 🆕
};

      this.editingOrder = false;
      this.showOrderModal = true;
    },
    closeOrderModal() {
      this.showOrderModal = false;
    },

    // Sauvegarder les clients, types de poulet, et commandes
    async saveClient() {
      try {
        if (this.editingClient) {
          await axios.put(`/api/clients/${this.clientForm.id}`, this.clientForm);
        } else {
          await axios.post('/api/clients', this.clientForm);
        }
        this.fetchClients();
        this.closeClientModal();
      } catch (error) {
        console.error("Erreur lors de l'enregistrement du client", error);
      }
    },

    async saveChickenType() {
      try {
        const validTypes = ['frais', 'roti', 'fume'];
        if (!validTypes.includes(this.chickenTypeForm.type)) return;
        this.calculatePrice();
        if (this.chickenTypeForm.price <= 0 || isNaN(this.chickenTypeForm.price)) return;

        if (this.editingChickenType) {
          await axios.put(`/api/chicken-types/${this.chickenTypeForm.id}`, this.chickenTypeForm);
        } else {
          await axios.post('/api/chicken-types', this.chickenTypeForm);
        }
        this.fetchChickenTypes();
        this.closeChickenTypeModal();
      } catch (error) {
        console.error("Erreur lors de l'enregistrement du type de poulet", error);
      }
    },

    async saveOrder() {
  try {
    console.log('🟡 orderForm initial:', this.orderForm);
    console.log('🟢 Type de chicken_types :', typeof this.orderForm.chicken_types);
    console.log('📦 Contenu de chicken_types :', this.orderForm.chicken_types);

    const formattedOrder = {
      ...this.orderForm,
      chicken_types: Object.entries(this.orderForm.chicken_types).map(([id, data]) => ({
        id: parseInt(id),
        quantity: data.quantity
      }))
    };

    console.log('Payload envoyé à Laravel pour Order:', formattedOrder);

    let response;
    if (this.editingOrder) {
      response = await axios.put(`/api/orders/${this.orderForm.id}`, formattedOrder);
    } else {
      response = await axios.post('/api/orders', formattedOrder);
    }

    // 🔁 Rafraîchir les commandes
    this.fetchOrders();

    // ✅ Ne pas fermer le modal tout de suite
    this.orderSaved = true; // ✅ Débloquer affichage bouton paiement
    alert('Commande sauvegardée avec succès!');

    console.log("🎯 orderForm prêt pour le paiement :", this.orderForm);

    // ❌ Ne pas lancer le paiement automatiquement
    // ✅ L'utilisateur choisira manuellement via le bouton 💳

  } catch (error) {
    console.error('Erreur lors de la sauvegarde de la commande :', error);
    if (error.response && error.response.status === 422) {
      console.error('Erreurs de validation Laravel :', error.response.data.errors);
    }
    alert('Une erreur s\'est produite lors de la sauvegarde de la commande.');
  }
},



 

    // Modifier les éléments existants
    editClient(client) {
      this.clientForm = { ...client };
      this.editingClient = true;
      this.showClientModal = true;
    },
    editChickenType(chickenType) {
      this.chickenTypeForm = { ...chickenType };
      this.editingChickenType = true;
      this.showChickenTypeModal = true;
    },
   
    // Supprimer les éléments
    async deleteClient(clientId) {
      await axios.delete(`/api/clients/${clientId}`);
      this.fetchClients();
    },
    async deleteChickenType(id) {
      await axios.delete(`/api/chicken-types/${id}`);
      this.fetchChickenTypes();
    },
    async deleteOrder(id) {
      await axios.delete(`/api/orders/${id}`);
      this.fetchOrders();
    },
    async deleteSubscription(id) {
      await axios.delete(`/api/subscriptions/${id}`);
      this.fetchSubscriptions();
    }
  },

  watch: {
    'chickenTypeForm.type': 'calculatePrice',
    'chickenTypeForm.weight': 'calculatePrice',
  }
};
</script>
