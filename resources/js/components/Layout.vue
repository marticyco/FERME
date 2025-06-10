<template>
  <div class="min-h-screen flex bg-gray-100 font-sans relative">

    <!-- Sidebar -->
    <aside
      v-if="!isAuthPage"
      :class="[
        'w-64 bg-gray-900 text-white fixed inset-y-0 left-0 z-40 transform lg:relative lg:translate-x-0 lg:flex flex-col transition-transform duration-200 ease-in-out',
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <div class="flex items-center justify-center h-16 border-b border-gray-700">
        <span class="text-2xl font-bold">🐓 Ma Ferme</span>
      </div>
      <nav class="flex-1 overflow-y-auto p-4 space-y-2">
        <SidebarItem icon="tachometer-alt" color="text-blue-400" label="Tableau de bord" to="/dashboard" />
        <SidebarItem icon="egg" color="text-yellow-400" label="Gestion Incubation" to="/incubation-manager" />
        <SidebarItem icon="layer-group" color="text-green-400" label="Gestion Lots" to="/lot-manager" />
        <SidebarItem icon="egg" color="text-indigo-400" label="Gestion Ponte" to="/ponte-manager" />
        <SidebarItem icon="boxes-stacked" color="text-teal-400" label="Gestion Stock" to="/aliment-manager" />
        <SidebarItem icon="handshake" color="text-red-400" label="Gestion Commerciale" to="/client-manager" />
        <SidebarItem icon="kiwi-bird" color="text-pink-400" label="Gestion Bandes" to="/band-manager" />
        <SidebarItem icon="notes-medical" color="text-purple-400" label="Gestion Sanitaire" to="/traitement-manager" />
        <SidebarItem icon="chart-line" color="text-orange-400" label="Cash Flow" to="/cash-manager" />
      </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div
      v-if="mobileSidebarOpen && !isAuthPage"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
      @click="mobileSidebarOpen = false"
    ></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">

      <!-- Navbar -->
      <nav v-if="!isAuthPage" class="bg-gray-50 h-[42px] px-4 border-b border-gray-200 shadow-sm flex items-center justify-between z-[1030]">
        <!-- Left side (burger + links) -->
        <div class="flex items-center space-x-4 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300">
          <!-- Burger Menu for mobile -->
          <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-gray-800">
            <i class="fas fa-bars text-xl"></i>
          </button>

          <NavbarLink icon="home" text="Accueil" to="/dashboard" />
          <NavbarLink icon="chart-bar" text="Comptabilité" to="/account" color="purple" />
          <NavbarLink icon="chart-pie" text="Statistiques" to="/statistiques" color="indigo" />
          <NavbarLink icon="tasks" text="Tâches" to="/taches" color="green" />
          <NavbarLink icon="cogs" text="Paramètres" to="/parametres" color="yellow" />
          <router-link
            to="/permission-manager"
            class="bg-blue-600 text-white rounded px-3 py-1 hover:bg-blue-700 transition flex items-center text-sm font-medium"
          >
            <i class="fas fa-users-cog text-yellow-300 mr-2"></i>
            Rôles & Permissions
          </router-link>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
          <NavbarLink icon="user-circle" text="Profil" color="green" />
          <NavbarLink icon="bell" text="Notifications" color="orange" />
        </div>
      </nav>

      <!-- Router content -->
      <main class="p-6 flex-1">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Layout',
  data() {
    return {
      mobileSidebarOpen: false,
    };
  },
  computed: {
    isAuthPage() {
      return this.$route.path === '/auth';
    }
  },
  components: {
    SidebarItem: {
      props: ['icon', 'label', 'to', 'color'],
      template: `
        <router-link
          :to="to"
          class="flex items-center p-2 rounded hover:bg-gray-700 transition text-sm font-medium"
        >
          <i :class="'fas fa-' + icon + ' mr-3 text-lg ' + color"></i>
          <span>{{ label }}</span>
        </router-link>
      `
    },
    NavbarLink: {
      props: ['icon', 'text', 'to', 'color'],
      template: `
        <component
          :is="to ? 'router-link' : 'a'"
          :to="to || '#'"
          class="flex items-center text-gray-800 text-sm font-medium px-2 py-1 rounded transition hover:text-blue-600 hover:bg-gray-100"
        >
          <i :class="'fas fa-' + icon + ' mr-2 text-' + (color || 'gray') + '-500'"></i>
          {{ text }}
        </component>
      `
    }
  }
}
</script>

<style scoped>
/* Scrollbar légère */
.scrollbar-thin::-webkit-scrollbar {
  height: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: #d1d5db; /* gray-300 */
  border-radius: 10px;
}
</style>
