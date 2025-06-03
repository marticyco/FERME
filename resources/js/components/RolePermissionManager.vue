<template>
    <div class="flex min-h-screen bg-gray-50">
     <!-- Sidebar -->
<div class="w-48 bg-blue-900 text-white p-6 h-screen">
  <h2 class="text-xl font-semibold mb-8">Admin Panel</h2>

  <ul class="space-y-4">
    <!-- Utilisateurs -->
    <li>
      <button
        @click="selectSection('users')"
        :class="{'bg-blue-700': currentSection === 'users'}"
        class="w-full flex items-center px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none transition-all"
      >
        <i class="fas fa-users text-blue-300 mr-3"></i>
        <span>Utilisateurs</span>
      </button>
    </li>

    <!-- Rôles -->
    <li>
      <button
        @click="selectSection('roles')"
        :class="{'bg-blue-700': currentSection === 'roles'}"
        class="w-full flex items-center px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none transition-all"
      >
        <i class="fas fa-user-shield text-yellow-300 mr-3"></i>
        <span>Rôles</span>
      </button>
    </li>

    <!-- Permissions -->
    <li>
      <button
        @click="selectSection('permissions')"
        :class="{'bg-blue-700': currentSection === 'permissions'}"
        class="w-full flex items-center px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none transition-all"
      >
        <i class="fas fa-key text-red-300 mr-3"></i>
        <span>Permissions</span>
      </button>
    </li>
  </ul>
</div>

      <!-- Main Content -->
      <div class="flex-1 p-8 overflow-auto">
        <h3 class="text-3xl font-semibold text-gray-800 mb-8">{{ sectionTitle }}</h3>
  
        <!-- Utilisateurs -->
        <div v-if="currentSection === 'users'">
          <div class="mb-6">
            <button
              @click="openModal('createUser')"
              class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none"
            >
              <span class="mr-2">+</span> Ajouter un Utilisateur
            </button>
          </div>
          <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
              <tr>
                <th class="px-6 py-4 text-left">Nom de l'Utilisateur</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Rôles</th>
                <th class="px-6 py-4 text-left">Permissions</th>
                <th class="px-6 py-4 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="user in paginatedItems"

                :key="user.id"
                class="border-t"
                :class="getRoleClass(user)"
              >
                <td class="px-6 py-4">{{ user.name }}</td>
                <td class="px-6 py-4">{{ user.email }}</td>
                <td class="px-6 py-4">
                  <ul>
                    <li v-for="role in user.roles" :key="role.id">{{ role.name }}</li>
                  </ul>
                </td>
                <td class="px-6 py-4">
                  <ul class="list-disc pl-4">
                    <li v-for="permission in user.permissions" :key="permission.id">
                      <i class="fas fa-check-circle text-green-500 mr-2"></i>
                      {{ permission.name }}
                    </li>
                  </ul>
                </td>
                <td class="px-6 py-4 flex space-x-4">
                  <button @click="openUserDetails(user)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button @click="openModal('editUser', user)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="deleteUser(user)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
  <!-- Texte de la page -->
  <div class="text-sm text-gray-600 text-center sm:text-left">
    Page <span class="font-semibold text-gray-800">{{ currentPage }}</span> sur <span class="font-semibold text-gray-800">{{ totalPages }}</span>
  </div>

  <!-- Boutons de pagination -->
  <div class="flex items-center justify-center space-x-2">
    <!-- Précédent -->
    <button
      @click="changePage(currentPage - 1)"
      :disabled="currentPage === 1"
      class="flex items-center px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
             bg-gradient-to-r from-blue-500 to-blue-700 text-white hover:from-blue-600 hover:to-blue-800 
             disabled:opacity-40 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
    >
      <i class="fas fa-arrow-left mr-2"></i> Précédent
    </button>

    <!-- Suivant -->
    <button
      @click="changePage(currentPage + 1)"
      :disabled="currentPage === totalPages"
      class="flex items-center px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
             bg-gradient-to-r from-green-500 to-green-700 text-white hover:from-green-600 hover:to-green-800 
             disabled:opacity-40 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
    >
      Suivant <i class="fas fa-arrow-right ml-2"></i>
    </button>
  </div>
</div>

  
        <!-- Rôles -->
        <div v-if="currentSection === 'roles'">
          <div class="mb-6">
            <button
              @click="openModal('createRole')"
              class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none"
            >
              <span class="mr-2">+</span> Ajouter un Rôle
            </button>
          </div>
          <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
              <tr>
                <th class="px-6 py-4 text-left">Nom du Rôle</th>
                <th class="px-6 py-4 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="role in paginatedItems" :key="role.id" class="border-t">
                <td class="px-6 py-4">{{ role.name }}</td>
                <td class="px-6 py-4 flex space-x-4">
                  <button @click="openModal('editRole', role)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="deleteRole(role)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <!-- Permissions -->
        <div v-if="currentSection === 'permissions'">
          <div class="mb-6">
            <button
              @click="openModal('createPermission')"
              class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none"
            >
              <span class="mr-2">+</span> Ajouter une Permission
            </button>
          </div>
          <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
              <tr>
                <th class="px-6 py-4 text-left">Nom de la Permission</th>
                <th class="px-6 py-4 text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="permission in paginatedItems" :key="permission.id" class="border-t">
                <td class="px-6 py-4">{{ permission.name }}</td>
                <td class="px-6 py-4 flex space-x-4">
                  <button @click="openModal('editPermission', permission)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="deletePermission(permission)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <!-- Modal -->
        <div v-if="modalVisible" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-xl font-semibold mb-4">{{ modalTitle }}</h2>
  
            <!-- Formulaire -->
<form @submit.prevent="handleFormSubmit" class="max-w-full">
  <!-- Conteneur scrollable et responsive -->
  <div class="overflow-y-auto max-h-[70vh] sm:max-h-[80vh] pr-2 sm:pr-4">
    <div v-if="modalType === 'createUser' || modalType === 'editUser'">
      <label for="userName" class="block text-gray-700">Nom de l'Utilisateur</label>
      <input v-model="formData.name" id="userName" type="text" class="w-full p-2 border border-gray-300 rounded mt-2" />

      <label for="userEmail" class="block text-gray-700 mt-4">Email de l'Utilisateur</label>
      <input v-model="formData.email" id="userEmail" type="email" class="w-full p-2 border border-gray-300 rounded mt-2" />

      <label for="userPassword" class="block text-gray-700 mt-4">Mot de passe</label>
      <input v-model="formData.password" id="userPassword" type="password" class="w-full p-2 border border-gray-300 rounded mt-2" />

      <label for="userPasswordConfirmation" class="block text-gray-700 mt-4">Confirmation du mot de passe</label>
      <input v-model="formData.password_confirmation" id="userPasswordConfirmation" type="password" class="w-full p-2 border border-gray-300 rounded mt-2" />

      <label for="userRoles" class="block text-gray-700 mt-4">Rôles</label>
      <div class="flex flex-wrap mt-2">
        <div v-for="role in roles" :key="role.id" class="mr-4 mb-2">
          <input
            type="checkbox"
            :id="`role-${role.id}`"
            v-model="formData.roles"
            :value="role.id"
            class="mr-2"
          />
          <label :for="`role-${role.id}`">{{ role.name }}</label>
        </div>
      </div>

      <label for="userPermissions" class="block text-gray-700 mt-4">Permissions</label>
      <div class="flex flex-wrap mt-2">
        <div v-for="permission in permissions" :key="permission.id" class="mr-4 mb-2">
          <input
            type="checkbox"
            :id="`permission-${permission.id}`"
            v-model="formData.permissions"
            :value="permission.id"
            class="mr-2"
          />
          <label :for="`permission-${permission.id}`">{{ permission.name }}</label>
        </div>
      </div>
    </div>

    <div v-if="modalType === 'createRole' || modalType === 'editRole'">
      <label for="roleName" class="block text-gray-700">Nom du Rôle</label>
      <input v-model="formData.name" id="roleName" type="text" class="w-full p-2 border border-gray-300 rounded mt-2" />
    </div>

    <div v-if="modalType === 'createPermission' || modalType === 'editPermission'">
      <label for="permissionName" class="block text-gray-700">Nom de la Permission</label>
      <input v-model="formData.name" id="permissionName" type="text" class="w-full p-2 border border-gray-300 rounded mt-2" />
    </div>
  </div>

  <!-- Boutons fixes en bas -->
  <div class="flex flex-col sm:flex-row justify-end mt-4 space-y-2 sm:space-y-0 sm:space-x-2">
    <button @click="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded-md">
      Annuler
    </button>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none">
      Enregistrer
    </button>
  </div>
</form>

          </div>
        </div>
      </div>
    </div>
  </template>
  
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        users: [],
        roles: [],
        permissions: [],
        sidebarCollapsed: false,
        currentSection: 'users', // Section active : 'users', 'roles', 'permissions'
        currentPage: 1,
         perPage: 3,
        formData: {
          name: '',
          email: '',
          password: '',
          password_confirmation: '',
          roles: [],
          permissions: [],
        },
        modalVisible: false,
        modalType: '', // 'createUser', 'editUser', 'createRole', 'editRole', 'createPermission', 'editPermission'
        modalTitle: '',
      };
    },
  
    computed: {
  sectionTitle() {
    switch (this.currentSection) {
      case 'users':
        return 'Gestion des Utilisateurs';
      case 'roles':
        return 'Gestion des Rôles';
      case 'permissions':
        return 'Gestion des Permissions';
      default:
        return '';
    }
  },
  totalPages() {
    switch (this.currentSection) {
      case 'users':
        return Math.ceil(this.users.length / this.perPage);
      case 'roles':
        return Math.ceil(this.roles.length / this.perPage);
      case 'permissions':
        return Math.ceil(this.permissions.length / this.perPage);
      default:
        return 1;
    }
  },
  paginatedItems() {
    const start = (this.currentPage - 1) * this.perPage;
    const end = start + this.perPage;

    switch (this.currentSection) {
      case 'users':
        return this.users.slice(start, end);
      case 'roles':
        return this.roles.slice(start, end);
      case 'permissions':
        return this.permissions.slice(start, end);
      default:
        return [];
    }
  },
},
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get('/api/users');
          this.users = response.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des utilisateurs:', error);
        }
      },

      changePage(page) {
  if (page >= 1 && page <= this.totalPages) {
    this.currentPage = page;
  }
},
      getRoleClass(user) {
      // Fonction pour attribuer une couleur en fonction du rôle
      if (user.roles.some(role => role.name === 'admin')) {
        return 'bg-red-100'; // Admin -> Rouge clair
      }
      if (user.roles.some(role => role.name === 'manager')) {
        return 'bg-yellow-100'; // Manager -> Jaune clair
      }
      if (user.roles.some(role => role.name === 'technicien')) {
        return 'bg-blue-100'; // Superviseur -> Bleu clair
      }
      return 'bg-gray-100'; // Utilisateur par défaut -> Gris clair
    },

  
      async fetchRoles() {
        try {
          const response = await axios.get('/api/roles');
          this.roles = response.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des rôles:', error);
        }
      },
  
      async fetchPermissions() {
        try {
          const response = await axios.get('/api/permissions');
          this.permissions = response.data;
        } catch (error) {
          console.error('Erreur lors de la récupération des permissions:', error);
        }
      },
  
      openModal(type, data = {}) {
        this.modalVisible = true;
        this.modalType = type;
        this.formData = { 
          id: data.id || null, 
          name: data.name || '', 
          email: data.email || '',
          password: '',
          password_confirmation: '',
          roles: data.roles ? data.roles.map(role => role.id) : [],
          permissions: data.permissions ? data.permissions.map(permission => permission.id) : []
        };
        this.modalTitle = this.getModalTitle(type);
      },
  
      getModalTitle(type) {
        const titles = {
          createUser: 'Créer un Utilisateur',
          editUser: 'Modifier un Utilisateur',
          createRole: 'Créer un Rôle',
          editRole: 'Modifier un Rôle',
          createPermission: 'Créer une Permission',
          editPermission: 'Modifier une Permission',
        };
        return titles[type] || '';
      },
  
      closeModal() {
        this.modalVisible = false;
      },
  
      async handleFormSubmit() {
        try {
          if (this.modalType === 'createUser') {
            await this.createUser();
          } else if (this.modalType === 'editUser') {
            await this.editUser();
          } else if (this.modalType === 'createRole') {
            await this.createRole();
          } else if (this.modalType === 'editRole') {
            await this.editRole();
          } else if (this.modalType === 'createPermission') {
            await this.createPermission();
          } else if (this.modalType === 'editPermission') {
            await this.editPermission();
          }
        } catch (error) {
          console.error('Erreur lors de l\'enregistrement des données:', error);
          alert('Une erreur est survenue, veuillez réessayer.');
        } finally {
          this.closeModal();
        }
      },
  
      async createUser() {
        try {
          const response = await axios.post('/api/users', {
            name: this.formData.name,
            email: this.formData.email,
            password: this.formData.password,
            password_confirmation: this.formData.password_confirmation,
            roles: this.formData.roles,
            permissions: this.formData.permissions,
          });
          this.fetchUsers();
        } catch (error) {
          alert('Erreur lors de la création de l\'utilisateur: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async editUser() {
        try {
          const response = await axios.put(`/api/users/${this.formData.id}`, {
            name: this.formData.name,
            email: this.formData.email,
            roles: this.formData.roles,
            permissions: this.formData.permissions,
          });
          this.fetchUsers();
        } catch (error) {
          alert('Erreur lors de la mise à jour de l\'utilisateur: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async createRole() {
        try {
          const response = await axios.post('/api/roles', {
            name: this.formData.name,
          });
          this.fetchRoles();
        } catch (error) {
          alert('Erreur lors de la création du rôle: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async editRole() {
        try {
          const response = await axios.put(`/api/roles/${this.formData.id}`, {
            name: this.formData.name,
          });
          this.fetchRoles();
        } catch (error) {
          alert('Erreur lors de la mise à jour du rôle: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async createPermission() {
        try {
          const response = await axios.post('/api/permissions', {
            name: this.formData.name,
          });
          this.fetchPermissions();
        } catch (error) {
          alert('Erreur lors de la création de la permission: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async editPermission() {
        try {
          const response = await axios.put(`/api/permissions/${this.formData.id}`, {
            name: this.formData.name,
          });
          this.fetchPermissions();
        } catch (error) {
          alert('Erreur lors de la mise à jour de la permission: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async deleteUser(user) {
        try {
          await axios.delete(`/api/users/${user.id}`);
          this.fetchUsers();
        } catch (error) {
          alert('Erreur lors de la suppression de l\'utilisateur: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async deleteRole(role) {
        try {
          await axios.delete(`/api/roles/${role.id}`);
          this.fetchRoles();
        } catch (error) {
          alert('Erreur lors de la suppression du rôle: ' + error.response?.data?.errors || error.message);
        }
      },
  
      async deletePermission(permission) {
        try {
          await axios.delete(`/api/permissions/${permission.id}`);
          this.fetchPermissions();
        } catch (error) {
          alert('Erreur lors de la suppression de la permission: ' + error.response?.data?.errors || error.message);
        }
      },
  
      selectSection(section) {
        this.currentSection = section;
        this.fetchDataForSection();
      },
  
      fetchDataForSection() {
        if (this.currentSection === 'users') {
          this.fetchUsers();
        } else if (this.currentSection === 'roles') {
          this.fetchRoles();
        } else if (this.currentSection === 'permissions') {
          this.fetchPermissions();
        }
      },
  
      openUserDetails(user) {
        // Logique pour afficher les détails de l'utilisateur
        alert('Afficher les détails de l\'utilisateur: ' + user.name);
      },
    },
  
    mounted() {
      this.fetchDataForSection();
    },
  };
  </script>
  
  <style scoped>
  /* Vous pouvez personnaliser vos styles ici */
  </style>
  