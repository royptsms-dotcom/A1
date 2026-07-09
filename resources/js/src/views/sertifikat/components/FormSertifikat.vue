<template>
  <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="800" scrollable transition="dialog-bottom-transition">
    <v-card v-if="modelValue" rounded="lg">
      <v-card-title class="d-flex align-center pa-5 pb-3">
        <FileTextOutlined class="text-primary mr-2" style="font-size: 20px" />
        <span class="text-h5 font-weight-bold">Form Generate Sertifikat Kalibrasi</span>
        <v-spacer />
        <v-btn icon variant="text" size="small" @click="$emit('update:modelValue', false)">
          <span style="font-size: 20px">&times;</span>
        </v-btn>
      </v-card-title>
      <v-divider />
      
      <v-card-text style="max-height: 60vh;" class="pa-5">
        <v-form ref="form" v-model="isValid" @submit.prevent="generateSertifikat">
          <v-row dense>
            <v-col cols="12" md="6" class="pb-2">
              <v-select
                v-model="formData.nama_pt"
                :items="['PT. SARANA MEGAMEDILAB SENTOSA', 'PT. SARANA MAJU']"
                label="Nama PT"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Nama PT harus diisi']"
                required
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.nomor_sertifikat"
                label="Nomor Sertifikat"
                variant="outlined"
                density="comfortable"
                color="primary"
                placeholder="Contoh: 001/SMS-CC/II/2026"
                :rules="[v => !!v || 'Nomor Sertifikat harus diisi']"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" class="pb-2">
              <v-text-field
                v-model="formData.nama_rs"
                label="Nama Rumah Sakit"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Nama RS harus diisi']"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6" class="pb-2">
              <v-combobox
                v-model="formData.nama_alat"
                :items="alatList.map(a => a.nama_alat || a.nama)"
                label="Nama Alat"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Nama Alat harus diisi']"
                required
              ></v-combobox>
            </v-col>
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.nomor_sn"
                label="Serial Number (SN)"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Serial Number harus diisi']"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.tanggal_kalibrasi"
                label="Tanggal Kalibrasi"
                type="date"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Tanggal Kalibrasi harus diisi']"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.valid_until"
                label="Valid Until"
                type="date"
                variant="outlined"
                density="comfortable"
                color="primary"
                :rules="[v => !!v || 'Tanggal Valid harus diisi']"
                required
              ></v-text-field>
            </v-col>

            <!-- Penandatangan 1 -->
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.penandatangan1_nama"
                label="Nama Penandatangan 1 (Kiri)"
                variant="outlined"
                density="comfortable"
                color="primary"
                placeholder="Contoh: Bernad Situmorang"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.penandatangan1_jabatan"
                label="Jabatan Penandatangan 1 (Kiri)"
                variant="outlined"
                density="comfortable"
                color="primary"
                placeholder="Contoh: Technical"
              ></v-text-field>
            </v-col>

            <!-- Penandatangan 2 -->
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.penandatangan2_nama"
                label="Nama Penandatangan 2 (Kanan)"
                variant="outlined"
                density="comfortable"
                color="primary"
                placeholder="Contoh: Nurwahid Wijaya"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6" class="pb-2">
              <v-text-field
                v-model="formData.penandatangan2_jabatan"
                label="Jabatan Penandatangan 2 (Kanan)"
                variant="outlined"
                density="comfortable"
                color="primary"
                placeholder="Contoh: Customer Support Manager"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>

      <v-divider />
      <v-card-actions class="pa-4">
        <v-spacer></v-spacer>
        <v-btn color="secondary" variant="outlined" @click="$emit('update:modelValue', false)">Batal</v-btn>
        <v-btn color="primary" variant="flat" :disabled="!isValid" :loading="isSubmitting" @click="generateSertifikat">
          Generate Sertifikat
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import { FileTextOutlined } from '@ant-design/icons-vue';

const props = defineProps<{
  modelValue: boolean;
  rumahSakit: any;
  alatList: any[];
}>();

const emit = defineEmits(['update:modelValue', 'generated']);

const form = ref();
const isValid = ref(false);
const isSubmitting = ref(false);

const formData = ref({
  nama_pt: 'PT. SARANA MEGAMEDILAB SENTOSA',
  nomor_sertifikat: '',
  nama_rs: '',
  nama_alat: '',
  nomor_sn: '',
  tanggal_kalibrasi: '',
  valid_until: '',
  penandatangan1_nama: 'Bernad Situmorang',
  penandatangan1_jabatan: 'Technical',
  penandatangan2_nama: 'Nurwahid Wijaya',
  penandatangan2_jabatan: 'Customer Support Manager',
});

function getRomanNumeral(monthNum: number): string {
  const roman = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  return roman[monthNum - 1] || "";
}

async function updateNomorSertifikat() {
  if (!formData.value.nama_rs || !formData.value.nama_alat) {
    return;
  }
  
  try {
    const res = await axios.get('/api/sertifikat/count', {
      params: {
        nama_rs: formData.value.nama_rs,
        nama_alat: formData.value.nama_alat
      }
    });
    
    const count = res.data.count || 0;
    const nextCount = count + 1;
    const paddedCount = String(nextCount).padStart(3, '0');
    
    let ptAbbr = 'SMS-CC';
    if (formData.value.nama_pt === 'PT. SARANA MAJU') {
      ptAbbr = 'SM-CC';
    }
    
    let romanMonth = 'VII';
    let year = new Date().getFullYear();
    
    if (formData.value.tanggal_kalibrasi) {
      const dateParts = formData.value.tanggal_kalibrasi.split('-');
      if (dateParts.length === 3) {
        const m = parseInt(dateParts[1], 10);
        romanMonth = getRomanNumeral(m);
        year = parseInt(dateParts[0], 10);
      }
    }
    
    formData.value.nomor_sertifikat = `${paddedCount}/${ptAbbr}/${romanMonth}/${year}`;
  } catch (err) {
    console.error('Error fetching count:', err);
  }
}

// Prefill form when opened
watch(() => props.modelValue, (val) => {
  if (val && props.rumahSakit) {
    formData.value.nama_rs = props.rumahSakit.nama || '';
    
    // Pre-select first equipment if available
    if (props.alatList?.length) {
      formData.value.nama_alat = props.alatList[0]?.nama_alat || props.alatList[0]?.nama || '';
    }
    
    // Auto fill date
    const today = new Date();
    const nextYear = new Date();
    nextYear.setFullYear(today.getFullYear() + 1);
    
    formData.value.tanggal_kalibrasi = today.toISOString().split('T')[0];
    formData.value.valid_until = nextYear.toISOString().split('T')[0];
    
    // Trigger calculation
    updateNomorSertifikat();
  }
});

// Watch inputs to auto-update certificate number
watch([
  () => formData.value.nama_pt,
  () => formData.value.nama_rs,
  () => formData.value.nama_alat,
  () => formData.value.tanggal_kalibrasi
], () => {
  updateNomorSertifikat();
});

// Auto-update valid_until to exactly 1 year after tanggal_kalibrasi when changed
watch(() => formData.value.tanggal_kalibrasi, (newDate) => {
  if (newDate) {
    const date = new Date(newDate);
    if (!isNaN(date.getTime())) {
      date.setFullYear(date.getFullYear() + 1);
      formData.value.valid_until = date.toISOString().split('T')[0];
    }
  }
});

async function generateSertifikat() {
  const { valid } = await form.value.validate();
  if (!valid) return;

  isSubmitting.value = true;
  try {
    const res = await axios.post('/api/sertifikat', formData.value);
    emit('generated', res.data); // API returns the saved model directly
  } catch (err) {
    console.error('Error saving sertifikat:', err);
    alert('Terjadi kesalahan saat menyimpan data');
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<style scoped>
/* Reset any external overrides on Vuetify outlines */
:deep(.v-field__outline) {
  --v-field-border-opacity: 0.15 !important;
  --v-field-border-width: 1px !important;
  color: rgb(var(--v-theme-inputBorder)) !important;
}

:deep(.v-field--focused .v-field__outline) {
  --v-field-border-opacity: 1 !important;
  --v-field-border-width: 1.5px !important;
  color: rgb(var(--v-theme-primary)) !important;
}

/* Ensure the native input has no borders, outlines or box-shadows */
:deep(.v-field__input),
:deep(.v-field__input input),
:deep(input.v-field__input),
:deep(input) {
  border: none !important;
  outline: none !important;
  box-shadow: none !important;
}

/* Fix label overlapping with outline border notch */
:deep(.v-field--active .v-field__outline__notch::before),
:deep(.v-field--focused .v-field__outline__notch::before) {
  opacity: 0 !important;
}
</style>
