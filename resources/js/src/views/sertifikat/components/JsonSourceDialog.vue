<template>
  <v-dialog v-model="model" max-width="540" persistent>
    <v-card rounded="lg">
      <v-card-title class="d-flex align-center pa-5 pb-3">
        <v-icon color="primary" class="mr-2">mdi-database-cog-outline</v-icon>
        Kelola Sumber Data JSON
        <v-spacer />
        <v-btn icon="mdi-close" variant="text" @click="model = false" />
      </v-card-title>
      <v-divider />

      <v-card-text class="pa-5">
        <p class="text-body-2 text-medium-emphasis mb-4">
          Tambahkan URL file JSON dari server PT Anda. Sistem akan mengambil data
          (daftar RS, alat, penandatangan) dari URL tersebut.
        </p>

        <!-- Form tambah sumber -->
        <v-form ref="formRef" @submit.prevent="addSource">
          <v-row dense>
            <v-col cols="12">
              <v-text-field
                v-model="form.name"
                label="Nama Sumber"
                placeholder="Contoh: Data RS Cabang Jakarta"
                variant="outlined"
                density="compact"
                :rules="[required]"
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="form.url"
                label="URL JSON"
                placeholder="https://server-pt.com/api/data.json"
                variant="outlined"
                density="compact"
                :rules="[required]"
                prepend-inner-icon="mdi-link"
              />
            </v-col>
            <v-col cols="12">
              <v-select
                v-model="form.type"
                label="Tipe Data"
                :items="typeOptions"
                variant="outlined"
                density="compact"
                :rules="[required]"
              />
            </v-col>
            <v-col cols="12">
              <v-btn
                type="submit"
                color="primary"
                variant="flat"
                block
                :loading="saving"
                prepend-icon="mdi-plus"
              >
                Tambah Sumber
              </v-btn>
            </v-col>
          </v-row>
        </v-form>

        <v-divider class="my-4" />

        <!-- Daftar sumber yang sudah ada -->
        <div class="text-subtitle-2 font-weight-bold mb-3">Sumber Aktif</div>
        <div v-if="loading" class="d-flex justify-center py-4">
          <v-progress-circular indeterminate color="primary" size="28" />
        </div>
        <div v-else-if="!sources.length" class="text-body-2 text-medium-emphasis text-center py-4">
          Belum ada sumber data.
        </div>
        <v-list v-else density="compact" lines="two">
          <v-list-item
            v-for="src in sources"
            :key="src.id"
            :subtitle="src.url"
            :title="src.name"
            rounded="lg"
            class="mb-1 border"
          >
            <template #prepend>
              <v-icon :color="typeColor(src.type)">{{ typeIcon(src.type) }}</v-icon>
            </template>
            <template #append>
              <v-btn
                icon="mdi-delete-outline"
                size="x-small"
                color="error"
                variant="text"
                :loading="deletingId === src.id"
                @click="deleteSource(src)"
              />
            </template>
          </v-list-item>
        </v-list>
      </v-card-text>

      <v-divider />
      <v-card-actions class="pa-4">
        <v-spacer />
        <v-btn color="primary" variant="tonal" @click="reloadAndClose">
          Selesai & Muat Data
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';

const model = defineModel<boolean>();
const emit = defineEmits(['updated']);

const formRef = ref();
const loading = ref(false);
const saving = ref(false);
const deletingId = ref<number | null>(null);
const sources = ref<any[]>([]);

const form = ref({ name: '', url: '', type: '' });

const typeOptions = [
  { title: 'Daftar Rumah Sakit', value: 'rumah_sakit' },
  { title: 'Daftar Alat', value: 'alat' },
  { title: 'Data Penandatangan', value: 'penandatangan' },
];

const required = (v: any) => !!v || 'Wajib diisi';

function typeIcon(type: string) {
  const map: Record<string, string> = {
    rumah_sakit: 'mdi-hospital-building',
    alat: 'mdi-medical-bag',
    penandatangan: 'mdi-account-edit-outline',
  };
  return map[type] ?? 'mdi-file-json';
}
function typeColor(type: string) {
  const map: Record<string, string> = {
    rumah_sakit: 'blue',
    alat: 'green',
    penandatangan: 'orange',
  };
  return map[type] ?? 'grey';
}

async function fetchSources() {
  loading.value = true;
  const res = await axios.get('/api/json-sources');
  sources.value = res.data;
  loading.value = false;
}

async function addSource() {
  const { valid } = await formRef.value.validate();
  if (!valid) return;
  saving.value = true;
  try {
    await axios.post('/api/json-sources', form.value);
    form.value = { name: '', url: '', type: '' };
    formRef.value.resetValidation();
    await fetchSources();
  } finally {
    saving.value = false;
  }
}

async function deleteSource(src: any) {
  deletingId.value = src.id;
  await axios.delete(`/api/json-sources/${src.id}`);
  await fetchSources();
  deletingId.value = null;
}

function reloadAndClose() {
  emit('updated');
  model.value = false;
}

watch(model, (v) => { if (v) fetchSources(); });
</script>
