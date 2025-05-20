<template>
    <Layout>
      <template v-slot:default>
        <router-view></router-view>
      </template>
    </Layout>
  </template>
  
  <script>
  import Layout from './components/Layout.vue';
  
  export default {
    name: 'App',
    components: {
      Layout
    }
  };
  </script>
  