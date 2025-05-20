<template>
    <div class="max-w-6xl mx-auto p-6 space-y-10">
      <!-- Liste des journaux -->
      <div>
        <h2 class="text-xl font-semibold mb-2">Journaux disponibles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="journal in journals" :key="journal.id" class="p-4 border rounded bg-gray-50">
            <p class="font-bold">{{ journal.name }}</p>
            <p class="text-sm text-gray-500">{{ journal.description }}</p>
          </div>
        </div>
      </div>
  
      <!-- Création d'une écriture -->
      <div>
        <h2 class="text-xl font-semibold mb-2">Nouvelle écriture</h2>
        <form @submit.prevent="submitEntry" class="bg-white p-6 rounded shadow space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium">Journal</label>
              <select v-model="entry.journal_id" required class="w-full mt-1 border px-3 py-2 rounded">
                <option v-for="journal in journals" :value="journal.id" :key="journal.id">{{ journal.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium">Date</label>
              <input type="date" v-model="entry.date" required class="w-full mt-1 border px-3 py-2 rounded">
            </div>
          </div>
  
          <!-- Lignes d’écriture -->
          <div>
            <h3 class="font-semibold text-gray-700 mb-2">Lignes</h3>
            <div v-for="(line, index) in entry.lines" :key="index" class="grid grid-cols-4 gap-4 mb-2">
              <select v-model="line.account_id" class="border px-3 py-2 rounded">
                <option value="" disabled>Sélectionner un compte</option>
                <option v-for="account in accounts" :value="account.id" :key="account.id">{{ account.name }}</option>
              </select>
              <input type="number" v-model.number="line.debit" placeholder="Débit" @input="recalculateTotals" class="border px-3 py-2 rounded">
              <input type="number" v-model.number="line.credit" placeholder="Crédit" @input="recalculateTotals" class="border px-3 py-2 rounded">
              <button type="button" @click="removeLine(index)" class="text-red-500 hover:text-red-700">Supprimer</button>
            </div>
  
            <button type="button" @click="addLine" class="text-blue-600 hover:underline">+ Ajouter une ligne</button>
          </div>
  
          <!-- Totaux -->
          <div class="flex justify-between mt-4">
            <p>Débit total : <strong>{{ totalDebit }}</strong></p>
            <p>Crédit total : <strong>{{ totalCredit }}</strong></p>
          </div>
          <div v-if="!isBalanced" class="text-red-500">Les montants ne sont pas équilibrés !</div>
  
          <!-- Submit -->
          <button :disabled="!isBalanced" class="mt-4 px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50">
            Enregistrer l’écriture
          </button>
        </form>
      </div>
  
      <!-- Liste des écritures -->
      <div>
        <h2 class="text-xl font-semibold mb-2">Journal des écritures</h2>
        <div class="flex space-x-4 mb-4">
          <input type="date" v-model="filters.start_date" class="border px-3 py-2 rounded" placeholder="Date début">
          <input type="date" v-model="filters.end_date" class="border px-3 py-2 rounded" placeholder="Date fin">
          <button @click="fetchEntries" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filtrer</button>
        </div>
  
        <table class="w-full text-sm text-left table-auto border">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-2">Date</th>
              <th class="p-2">Journal</th>
              <th class="p-2">Lignes</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="entry in entries" :key="entry.id" class="border-t">
              <td class="p-2">{{ entry.date }}</td>
              <td class="p-2">{{ entry.journal.name }}</td>
              <td class="p-2">
                <ul>
                  <li v-for="line in entry.journal_lines" :key="line.id">
                    {{ line.account.name }} — D: {{ line.debit }} / C: {{ line.credit }}
                  </li>
                </ul>
              </td>
              <td class="p-2">
                <button @click="deleteEntry(entry.id)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        journals: [],
        accounts: [],
        entries: [],
        filters: {
          start_date: '',
          end_date: '',
        },
        entry: {
          journal_id: '',
          date: '',
          lines: [
            { account_id: '', debit: 0, credit: 0 },
          ],
        },
        totalDebit: 0,
        totalCredit: 0,
      };
    },
    computed: {
      isBalanced() {
        return this.totalDebit === this.totalCredit;
      }
    },
    mounted() {
      this.fetchJournals();
      this.fetchAccounts();
      this.fetchEntries();
    },
    methods: {
      async fetchJournals() {
        const res = await axios.get('/api/journals');
        this.journals = res.data;
      },
      async fetchAccounts() {
        const res = await axios.get('/api/accounts');
        this.accounts = res.data;
      },
      async fetchEntries() {
        const res = await axios.get('/api/journal-entries', {
          params: this.filters,
        });
        this.entries = res.data;
      },
      addLine() {
        this.entry.lines.push({ account_id: '', debit: 0, credit: 0 });
      },
      removeLine(index) {
        this.entry.lines.splice(index, 1);
        this.recalculateTotals();
      },
      recalculateTotals() {
        this.totalDebit = this.entry.lines.reduce((t, l) => t + (parseFloat(l.debit) || 0), 0);
        this.totalCredit = this.entry.lines.reduce((t, l) => t + (parseFloat(l.credit) || 0), 0);
      },
      async submitEntry() {
        await axios.post('/api/journal-entries', this.entry);
        this.entry = { journal_id: '', date: '', lines: [{ account_id: '', debit: 0, credit: 0 }] };
        this.recalculateTotals();
        this.fetchEntries();
      },
      async deleteEntry(id) {
        await axios.delete(`/api/journal-entries/${id}`);
        this.fetchEntries();
      }
    }
  };
  </script>
  