<script setup lang="ts">
import { shallowRef } from 'vue';

const footerLink = shallowRef([
  {
    title: 'Terms and Conditions'
  },
  {
    title: 'Privacy Policy'
  },
  {
    title: 'CA Privacy Notice'
  }
]);
</script>
<template>
  <div class="px-0 pt-2 z-100 bg-transparent text-center" style="z-index: 201; position: relative; width: 100%;">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; gap: 4px;">
      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;">
        <a
          v-for="(item, i) in footerLink"
          :key="i"
          class="cursor-pointer text-subtitle-2 text-lightText text-decoration-none footer-link-hover"
          target="_blank"
        >
          {{ item.title }}
        </a>
      </div>
      <p class="text-subtitle-2 text-lightText mb-0" style="margin-top: 8px;">
        Powered by
        <a href="#" class="cursor-pointer text-primary text-decoration-none font-weight-bold ml-1"> PT. SARANA MEGAMEDILAB SENTOSA </a>
      </p>
    </div>
  </div>
</template>
