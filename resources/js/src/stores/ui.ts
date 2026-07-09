import { ref } from 'vue';
import { defineStore } from 'pinia';

export const useUIStore = defineStore('ui', () => {
  const isLoading = ref(false);
  const breadcrumbs = ref<any[]>([]);
  const pageTitle = ref('');

  return {
    isLoading,
    breadcrumbs,
    pageTitle
  };
});
