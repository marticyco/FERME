<template>
  <div class="space-y-8">
    <!-- 👥 Utilisateurs -->
    <SettingSection title="Gestion du profil utilisateur">
      <InputSetting label="Nom complet" v-model="settings.user.fullName" />
      <InputSetting label="Adresse e-mail" v-model="settings.user.email" />
      <router-link to="/permission-manager" class="text-blue-600 hover:underline">
        Gérer les rôles et permissions
      </router-link>
    </SettingSection>

    <!-- 🐓 Réglages de l’élevage -->
    <SettingSection title="Réglages de l’élevage">
      <SelectSetting
        label="Type de bande par défaut"
        v-model="settings.elevage.defaultBandType"
        :options="[
          { value: 'pondeuse', text: 'Pondeuse' },
          { value: 'chair', text: 'Chair' }
        ]"
      />
      <InputSetting label="Durée d'incubation (jours)" v-model="settings.elevage.incubationDays" />
      <InputSetting label="Température incubateur (°C)" v-model="settings.elevage.incubationTemp" />
    </SettingSection>

    <!-- 📦 Stock & produits -->
    <SettingSection title="Stock & Produits">
      <InputSetting label="Seuil d’alerte de stock (kg)" v-model="settings.stock.lowStockThreshold" />
      <InputSetting label="Unité de mesure" v-model="settings.stock.unit" />
      <ToggleSetting label="Tarification automatique" v-model="settings.stock.autoPricing" />
    </SettingSection>

    <!-- 📢 Notifications -->
    <SettingSection title="Notifications">
      <SelectSetting
        label="Fréquence des rappels"
        v-model="settings.notifications.frequency"
        :options="[
          { value: 'daily', text: 'Quotidien' },
          { value: 'weekly', text: 'Hebdomadaire' },
          { value: 'monthly', text: 'Mensuel' }
        ]"
      />
      <ToggleSetting label="Recevoir les alertes e-mail" v-model="settings.notifications.email" />
      <ToggleSetting label="Notifications internes (in-app)" v-model="settings.notifications.inApp" />
    </SettingSection>

    <!-- 🌐 Système -->
    <SettingSection title="Paramètres système">
      <SelectSetting
        label="Langue"
        v-model="settings.system.language"
        :options="[
          { value: 'fr', text: 'Français' },
          { value: 'en', text: 'Anglais' }
        ]"
      />
      <InputSetting label="Fuseau horaire" v-model="settings.system.timezone" placeholder="ex: UTC+1" />
      <ToggleSetting label="Activer sauvegarde automatique" v-model="settings.system.autoBackup" />
      <InputSetting label="Clé API Google Sheets (optionnel)" v-model="settings.system.apiKey" />
    </SettingSection>

    <div class="flex justify-end pt-4">
      <button
        @click="saveSettings"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded"
      >
        Enregistrer les paramètres
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Parametres',
  components: {
    SettingSection: {
      props: ['title'],
      template: `
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
          <div class="space-y-4">
            <slot />
          </div>
        </div>
      `
    },
    ToggleSetting: {
      props: ['label', 'modelValue'],
      emits: ['update:modelValue'],
      template: `
        <div class="flex items-center justify-between">
          <span class="text-gray-700">{{ label }}</span>
          <label class="inline-flex relative items-center cursor-pointer">
            <input
              type="checkbox"
              class="sr-only peer"
              :checked="modelValue"
              @change="$emit('update:modelValue', $event.target.checked)"
            />
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-500 transition"></div>
            <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-full"></div>
          </label>
        </div>
      `
    },
    InputSetting: {
      props: ['label', 'placeholder', 'modelValue'],
      emits: ['update:modelValue'],
      template: `
        <div>
          <label class="block text-gray-700 font-medium mb-1">{{ label }}</label>
          <input
            type="text"
            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
            :placeholder="placeholder"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
          />
        </div>
      `
    },
    SelectSetting: {
      props: ['label', 'options', 'modelValue'],
      emits: ['update:modelValue'],
      template: `
        <div>
          <label class="block text-gray-700 font-medium mb-1">{{ label }}</label>
          <select
            class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
          >
            <option v-for="option in options" :key="option.value" :value="option.value">
              {{ option.text }}
            </option>
          </select>
        </div>
      `
    }
  },
  data() {
    return {
      settings: {
        user: {
          fullName: 'Jean Dupont',
          email: 'jean@ferme.com'
        },
        elevage: {
          defaultBandType: 'pondeuse',
          incubationDays: 21,
          incubationTemp: 37.5
        },
        stock: {
          lowStockThreshold: 10,
          unit: 'kg',
          autoPricing: true
        },
        notifications: {
          frequency: 'daily',
          email: true,
          inApp: false
        },
        system: {
          language: 'fr',
          timezone: 'UTC+1',
          autoBackup: false,
          apiKey: ''
        }
      }
    }
  },
  methods: {
    saveSettings() {
      console.log('Paramètres enregistrés :', this.settings)
      alert('Paramètres enregistrés !')
    }
  }
}
</script>
