<script setup>
// icons
import { SearchOutlined } from '@ant-design/icons-vue';
</script>

<template>
  <!-- ---------------------------------------------- -->
  <!-- searchbar -->
  <!-- ---------------------------------------------- -->
  <v-text-field persistent-placeholder placeholder="Ctrl + k" color="primary" variant="solo-filled" flat hide-details density="compact" bg-color="lightsecondary">
    <template v-slot:prepend-inner>
      <SearchOutlined :style="{ fontSize: '12px', color: 'rgb(var(--v-theme-lightText))' }" />
    </template>
  </v-text-field>
</template>
