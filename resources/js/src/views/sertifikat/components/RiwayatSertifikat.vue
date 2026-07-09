<template>
  <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="800" transition="dialog-bottom-transition">
    <v-card>
      <v-card-title class="pa-4 bg-secondary text-white d-flex justify-space-between align-center">
        <span>Riwayat Sertifikat: {{ rumahSakit?.nama }}</span>
        <v-btn icon="mdi-close" variant="text" @click="$emit('update:modelValue', false)" density="compact"></v-btn>
      </v-card-title>
      
      <v-card-text class="pa-4">
        <v-alert type="info" variant="tonal" class="mb-4">
          Fitur riwayat masih dalam pengembangan.
        </v-alert>
      </v-card-text>

      <v-card-actions class="pa-4 pt-0">
        <v-spacer></v-spacer>
        <v-btn color="secondary" variant="flat" @click="$emit('update:modelValue', false)">Tutup</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

const props = defineProps<{
  modelValue: boolean;
  rumahSakit: any;
}>();

const emit = defineEmits(['update:modelValue', 'preview']);
</script>
