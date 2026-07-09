<script setup lang="ts">
import { watch } from 'vue';
import { useUIStore } from '@/stores/ui';

type Breadcrumb = {
  title: string;
  disabled: boolean;
  href: string;
};

const props = defineProps({
  title: String,
  breadcrumbs: Array as () => Breadcrumb[],
  icon: String
});

const uiStore = useUIStore();

// Watch and sync props to UI Store globally
watch(
  () => props.breadcrumbs,
  (newVal) => {
    uiStore.breadcrumbs = newVal || [];
  },
  { immediate: true, deep: true }
);

watch(
  () => props.title,
  (newVal) => {
    uiStore.pageTitle = newVal || '';
  },
  { immediate: true }
);
</script>

<template>
  <!-- Empty template: Breadcrumbs are rendered globally in VerticalHeader.vue -->
</template>
