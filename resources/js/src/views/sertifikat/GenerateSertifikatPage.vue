<template>
  <v-container fluid class="sertifikat-page pa-6">
    <!-- Header -->
    <div class="d-flex align-center justify-space-between mb-6">
      <div>
        <h1 class="text-h5 font-weight-bold text-primary">Generate Sertifikat Kalibrasi</h1>
        <p class="text-body-2 text-medium-emphasis mt-1">Kelola dan generate sertifikat kalibrasi alat kesehatan</p>
      </div>
      <v-btn color="primary" prepend-icon="mdi-cog" variant="tonal" @click="openSettingDialog">
        Kelola Sumber Data JSON
      </v-btn>
    </div>

    <!-- Loading data -->
    <v-alert v-if="!dataLoaded && !loadingData" type="warning" variant="tonal" class="mb-4">
      <template #prepend><v-icon>mdi-cloud-off-outline</v-icon></template>
      <strong>Data belum tersedia.</strong> Tambahkan URL sumber data JSON terlebih dahulu melalui tombol
      <strong>"Kelola Sumber Data JSON"</strong> di atas.
    </v-alert>

    <v-progress-linear v-if="loadingData" indeterminate color="primary" class="mb-4" />

    <!-- Daftar RS -->
    <v-card v-if="dataLoaded" elevation="0" border rounded="lg">
      <v-card-title class="pa-4 pb-2">
        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          label="Cari Rumah Sakit..."
          hide-details
          density="compact"
          variant="outlined"
          style="max-width: 360px"
        />
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="filteredRSList"
        :loading="loadingData"
        hover
        class="elevation-0"
        items-per-page="10"
      >
        <template #item.no="{ index }">{{ index + 1 }}</template>

        <template #item.actions="{ item }">
          <div class="d-flex gap-2">
            <v-btn
              size="small"
              color="primary"
              variant="flat"
              prepend-icon="mdi-certificate-outline"
              @click="openGenerateDialog(item)"
            >
              Buat Sertifikat
            </v-btn>
            <v-btn
              size="small"
              color="secondary"
              variant="tonal"
              icon="mdi-history"
              @click="openHistoryDialog(item)"
            />
          </div>
        </template>
      </v-data-table>
    </v-card>

    <!-- Dialog Kelola Sumber JSON -->
    <JsonSourceDialog v-model="settingDialog" @updated="loadData" />

    <!-- Dialog Form Generate Sertifikat -->
    <FormSertifikat
      v-model="generateDialog"
      :rumah-sakit="selectedRS"
      :alat-list="alatList"
      @generated="onSertifikatGenerated"
    />

    <!-- Dialog Preview Sertifikat -->
    <CertificatePreview
      v-model="previewDialog"
      :sertifikat="generatedSertifikat"
    />

    <!-- Dialog Riwayat Sertifikat -->
    <RiwayatSertifikat
      v-model="historyDialog"
      :rumah-sakit="selectedRS"
      @preview="previewExisting"
    />
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import JsonSourceDialog from './components/JsonSourceDialog.vue';
import FormSertifikat from './components/FormSertifikat.vue';
import CertificatePreview from './components/CertificatePreview.vue';
import RiwayatSertifikat from './components/RiwayatSertifikat.vue';

const settingDialog = ref(false);
const generateDialog = ref(false);
const previewDialog = ref(false);
const historyDialog = ref(false);

const search = ref('');
const loadingData = ref(false);
const dataLoaded = ref(false);

const rsList = ref<any[]>([]);
const alatList = ref<any[]>([]);
const selectedRS = ref<any>(null);
const generatedSertifikat = ref<any>(null);

const headers = [
  { title: 'No', key: 'no', width: '60px', sortable: false },
  { title: 'Nama Rumah Sakit', key: 'nama', sortable: true },
  { title: 'Kota / Kab', key: 'kota', sortable: true },
  { title: 'Jumlah Alat', key: 'jumlah_alat', sortable: false, align: 'center' as const },
  { title: 'Aksi', key: 'actions', sortable: false, align: 'start' as const },
];

const filteredRSList = computed(() => {
  if (!search.value) return rsList.value;
  return rsList.value.filter((rs) =>
    rs.nama?.toLowerCase().includes(search.value.toLowerCase()) ||
    rs.kota?.toLowerCase().includes(search.value.toLowerCase())
  );
});

async function loadData() {
  loadingData.value = true;
  try {
    const res = await axios.get('/api/json-sources/fetch-all');
    const data = res.data;

    if (data.rumah_sakit?.length) {
      const allRS: any[] = [];
      data.rumah_sakit.forEach((src: any) => {
        if (Array.isArray(src.data)) allRS.push(...src.data);
      });
      rsList.value = allRS;
      dataLoaded.value = true;
    }

    if (data.alat?.length) {
      const allAlat: any[] = [];
      data.alat.forEach((src: any) => {
        if (Array.isArray(src.data)) allAlat.push(...src.data);
      });
      alatList.value = allAlat;
    }
  } catch {
    // API error
  } finally {
    if (!dataLoaded.value) {
      // Inject dummy data for testing if no JSON sources configured
      rsList.value = [
        { id: 1, nama: 'RS. BINTANG AMIN', kota: 'Bandar Lampung', jumlah_alat: 12 },
        { id: 2, nama: 'RS. PERTAMINA BINTANG AMIN', kota: 'Bandar Lampung', jumlah_alat: 45 },
        { id: 3, nama: 'RS. ADVENT BANDAR LAMPUNG', kota: 'Bandar Lampung', jumlah_alat: 30 }
      ];
      alatList.value = [
        { id: 1, nama_alat: 'UA-600T URINE ANALYZER' },
        { id: 2, nama_alat: 'BC-3200 HEMATOLOGY ANALYZER' },
        { id: 3, nama_alat: 'RAYTO RT-1904C CHEMISTRY ANALYZER' }
      ];
      dataLoaded.value = true;
    }
    loadingData.value = false;
  }
}

function openSettingDialog() {
  settingDialog.value = true;
}

function openGenerateDialog(rs: any) {
  selectedRS.value = rs;
  generateDialog.value = true;
}

function openHistoryDialog(rs: any) {
  selectedRS.value = rs;
  historyDialog.value = true;
}

function onSertifikatGenerated(sertifikat: any) {
  generatedSertifikat.value = sertifikat;
  generateDialog.value = false;
  previewDialog.value = true;
}

function previewExisting(sertifikat: any) {
  generatedSertifikat.value = sertifikat;
  historyDialog.value = false;
  previewDialog.value = true;
}

onMounted(async () => {
  try {
    await axios.get('/api/json-sources');
  } catch (e) {}
  await loadData();
});
</script>

<style scoped>
.sertifikat-page {
  min-height: 100vh;
  background: #f8fafc;
}
</style>
