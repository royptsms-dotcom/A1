<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import {
  SyncOutlined,
  EditOutlined,
  StopOutlined,
  CheckCircleOutlined,
  SearchOutlined,
  EyeOutlined,
  EyeInvisibleOutlined,
  LockOutlined,
  WhatsAppOutlined
} from '@ant-design/icons-vue';

// Define Employee Type
interface Karyawan {
  id: string;
  nama: string;
  email: string;
  job: string;
  role: string;
  no_wa: string;
  status: 'Active' | 'Inactive';
  password?: string;
}

// Breadcrumbs
const page = ref({ title: 'Daftar Karyawan' });
const breadcrumbs = ref([
  {
    title: 'User',
    disabled: false,
    href: '#'
  },
  {
    title: 'Daftar Karyawan',
    disabled: true,
    href: '#'
  }
]);

// Component State
const karyawanList = ref<Karyawan[]>([]);
const searchQuery = ref('');
const loading = ref(false);

// Dialog Modal State
const editDialog = ref(false);
const selectedKaryawan = ref<Karyawan | null>(null);
const newPassword = ref('');
const confirmPassword = ref('');
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);
const savingPassword = ref(false);

// Toast Notification State
const snackbar = ref(false);
const snackbarText = ref('');
const snackbarColor = ref('success');

// Form Validation
const formRef = ref<any>(null);
const passwordRules = [
  (v: any) => !!v || 'Password wajib diisi',
  (v: any) => (v && v.length >= 6) || 'Password minimal 6 karakter'
];
const confirmPasswordRules = [
  (v: any) => !!v || 'Konfirmasi password wajib diisi',
  (v: any) => v === newPassword.value || 'Password tidak cocok'
];

// Load data (prefer local storage, otherwise fetch json)
async function loadKaryawan(forceRefresh = false) {
  loading.value = true;
  try {
    const cachedData = localStorage.getItem('karyawan_data');
    if (cachedData && !forceRefresh) {
      karyawanList.value = JSON.parse(cachedData);
      showSnackbar('Data berhasil dimuat dari penyimpanan lokal', 'success');
    } else {
      // Fetch from public/karyawan.json
      const response = await axios.get('/karyawan.json');
      karyawanList.value = response.data;
      saveToLocalStorage();
      showSnackbar(forceRefresh ? 'Data berhasil di-refresh dari karyawan.json' : 'Data berhasil dimuat dari JSON', 'success');
    }
  } catch (error) {
    console.error('Gagal mengambil data karyawan:', error);
    showSnackbar('Gagal mengambil data dari server / karyawan.json', 'error');
  } finally {
    loading.value = false;
  }
}

// Save to LocalStorage
function saveToLocalStorage() {
  localStorage.setItem('karyawan_data', JSON.stringify(karyawanList.value));
}

// Show Toast Alert
function showSnackbar(text: string, color = 'success') {
  snackbarText.value = text;
  snackbarColor.value = color;
  snackbar.value = true;
}

// Filtered Karyawan List
const filteredKaryawan = computed(() => {
  if (!searchQuery.value) return karyawanList.value;
  const query = searchQuery.value.toLowerCase().trim();
  return karyawanList.value.filter((item) => {
    return (
      item.id.toLowerCase().includes(query) ||
      item.nama.toLowerCase().includes(query) ||
      item.email.toLowerCase().includes(query) ||
      item.job.toLowerCase().includes(query) ||
      item.role.toLowerCase().includes(query) ||
      item.no_wa.includes(query)
    );
  });
});

// Edit Button Click
function openEditDialog(karyawan: Karyawan) {
  selectedKaryawan.value = karyawan;
  newPassword.value = '';
  confirmPassword.value = '';
  showNewPassword.value = false;
  showConfirmPassword.value = false;
  editDialog.value = true;
}

// Save New Password
async function savePassword() {
  const { valid } = await formRef.value.validate();
  if (!valid) return;

  if (!selectedKaryawan.value) return;

  savingPassword.value = true;
  try {
    // Update local state
    const index = karyawanList.value.findIndex((k) => k.id === selectedKaryawan.value?.id);
    if (index !== -1) {
      karyawanList.value[index].password = newPassword.value;
      saveToLocalStorage();
      showSnackbar(`Password untuk ${selectedKaryawan.value.nama} berhasil diubah!`, 'success');
    }
    editDialog.value = false;
  } catch (error) {
    showSnackbar('Gagal mengubah password', 'error');
  } finally {
    savingPassword.value = false;
  }
}

// Toggle Status (Active / Inactive)
function toggleStatus(karyawan: Karyawan) {
  const index = karyawanList.value.findIndex((k) => k.id === karyawan.id);
  if (index !== -1) {
    const newStatus = karyawan.status === 'Active' ? 'Inactive' : 'Active';
    karyawanList.value[index].status = newStatus;
    saveToLocalStorage();
    showSnackbar(
      `Karyawan ${karyawan.nama} berhasil ${newStatus === 'Active' ? 'diaktifkan' : 'dinonaktifkan'}!`,
      newStatus === 'Active' ? 'success' : 'warning'
    );
  }
}

// Get Role Chip Color
function getRoleColor(role: string) {
  switch (role.toLowerCase()) {
    case 'admin':
      return 'primary';
    case 'manager':
      return 'success';
    default:
      return 'secondary';
  }
}

// Format WhatsApp Link
function getWaLink(noWa: string) {
  // Clean characters, format to international code (e.g. 0812 -> 62812)
  let cleaned = noWa.replace(/[^0-9]/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return `https://wa.me/${cleaned}`;
}

onMounted(() => {
  loadKaryawan();
});
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>

  <v-row>
    <v-col cols="12">
      <UiParentCard>
        <template #action>
          <!-- Search field placed in the top right corner of the card -->
          <v-text-field
            v-model="searchQuery"
            placeholder="Cari ID, Nama, Email, Jabatan..."
            variant="outlined"
            density="compact"
            hide-details
            class="search-input"
          >
            <template #append-inner>
              <SearchOutlined class="text-lightText" />
            </template>
          </v-text-field>
        </template>

        <!-- Loading progress indicator -->
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

        <!-- Table Karyawan -->
        <v-table class="bordered-table mt-4" hover density="comfortable">
          <thead class="bg-containerBg">
            <tr>
              <th class="text-center text-caption font-weight-bold text-uppercase" style="white-space: nowrap;">ID</th>
              <th class="text-left text-caption font-weight-bold text-uppercase">Nama</th>
              <th class="text-left text-caption font-weight-bold text-uppercase">Email</th>
              <th class="text-left text-caption font-weight-bold text-uppercase">Jabatan (Job)</th>
              <th class="text-center text-caption font-weight-bold text-uppercase">Role</th>
              <th class="text-center text-caption font-weight-bold text-uppercase" style="white-space: nowrap;">No. WA</th>
              <th class="text-center text-caption font-weight-bold text-uppercase" style="width: 120px">Status</th>
              <th class="text-center text-caption font-weight-bold text-uppercase" style="width: 150px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredKaryawan.length === 0">
              <td colspan="8" class="text-center py-4 text-medium-emphasis">
                Karyawan tidak ditemukan.
              </td>
            </tr>
            <tr v-for="item in filteredKaryawan" :key="item.id" :class="item.status === 'Inactive' ? 'text-disabled-row' : ''">
              <td class="py-3 text-center font-weight-bold" style="white-space: nowrap;">{{ item.id }}</td>
              <td class="py-3">
                <span class="font-weight-medium text-darkText">{{ item.nama }}</span>
              </td>
              <td class="py-3">
                <a :href="`mailto:${item.email}`" class="text-decoration-none text-primary link-hover">{{ item.email }}</a>
              </td>
              <td class="py-3">{{ item.job }}</td>
              <td class="py-3 text-center">
                <v-chip :color="getRoleColor(item.role)" variant="flat" size="small" rounded="md">
                  {{ item.role }}
                </v-chip>
              </td>
              <td class="py-3" style="white-space: nowrap;">
                <div class="d-flex align-center justify-center" style="flex-wrap: nowrap;">
                  <span class="mr-1" style="white-space: nowrap;">{{ item.no_wa }}</span>
                  <v-btn
                    :href="getWaLink(item.no_wa)"
                    target="_blank"
                    icon
                    variant="text"
                    size="x-small"
                    color="success"
                    title="Hubungi via WhatsApp"
                    style="flex-shrink: 0;"
                  >
                    <WhatsAppOutlined style="font-size: 16px" />
                  </v-btn>
                </div>
              </td>
              <td class="py-3 text-center">
                <v-chip
                  :color="item.status === 'Active' ? 'success' : 'error'"
                  variant="tonal"
                  size="small"
                  rounded="md"
                >
                  {{ item.status }}
                </v-chip>
              </td>
              <td class="py-3 text-center">
                <div class="d-flex justify-center gap-2">
                  <!-- Edit Password -->
                  <v-btn
                    icon
                    variant="outlined"
                    size="small"
                    color="primary"
                    title="Ubah Password"
                    @click="openEditDialog(item)"
                  >
                    <EditOutlined style="font-size: 14px" />
                  </v-btn>

                  <!-- Toggle Active/Inactive Status -->
                  <v-btn
                    icon
                    variant="outlined"
                    size="small"
                    :color="item.status === 'Active' ? 'error' : 'success'"
                    :title="item.status === 'Active' ? 'Nonaktifkan Karyawan' : 'Aktifkan Karyawan'"
                    @click="toggleStatus(item)"
                  >
                    <component
                      :is="item.status === 'Active' ? StopOutlined : CheckCircleOutlined"
                      style="font-size: 14px"
                    />
                  </v-btn>
                </div>
              </td>
            </tr>
          </tbody>
        </v-table>
      </UiParentCard>
    </v-col>
  </v-row>

  <!-- Dialog Ubah Password -->
  <v-dialog v-model="editDialog" max-width="480px" persistent>
    <v-card rounded="lg">
      <v-card-title class="d-flex align-center pa-5 pb-3">
        <LockOutlined class="text-primary mr-2" />
        <span class="text-h5 font-weight-bold">Ubah Password Karyawan</span>
        <v-spacer />
        <v-btn icon variant="text" size="small" @click="editDialog = false">
          <span style="font-size: 20px">&times;</span>
        </v-btn>
      </v-card-title>
      <v-divider />

      <v-card-text class="pa-5">
        <v-form ref="formRef" @submit.prevent="savePassword">
          <v-row dense>
            <v-col cols="12" class="mb-3">
              <div class="text-caption text-medium-emphasis">Karyawan</div>
              <div class="text-subtitle-1 font-weight-bold text-darkText">
                {{ selectedKaryawan?.nama }} ({{ selectedKaryawan?.id }})
              </div>
              <div class="text-caption text-lightText">{{ selectedKaryawan?.email }}</div>
            </v-col>

            <!-- Password Baru -->
            <v-col cols="12">
              <v-text-field
                v-model="newPassword"
                label="Password Baru"
                variant="outlined"
                density="comfortable"
                :type="showNewPassword ? 'text' : 'password'"
                :rules="passwordRules"
                required
              >
                <template #append-inner>
                  <component
                    :is="showNewPassword ? EyeInvisibleOutlined : EyeOutlined"
                    class="cursor-pointer"
                    @click="showNewPassword = !showNewPassword"
                  />
                </template>
              </v-text-field>
            </v-col>

            <!-- Konfirmasi Password -->
            <v-col cols="12">
              <v-text-field
                v-model="confirmPassword"
                label="Konfirmasi Password Baru"
                variant="outlined"
                density="comfortable"
                :type="showConfirmPassword ? 'text' : 'password'"
                :rules="confirmPasswordRules"
                required
              >
                <template #append-inner>
                  <component
                    :is="showConfirmPassword ? EyeInvisibleOutlined : EyeOutlined"
                    class="cursor-pointer"
                    @click="showConfirmPassword = !showConfirmPassword"
                  />
                </template>
              </v-text-field>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-divider />

      <v-card-actions class="pa-4">
        <v-spacer />
        <v-btn color="secondary" variant="outlined" @click="editDialog = false">Batal</v-btn>
        <v-btn
          color="primary"
          variant="flat"
          :loading="savingPassword"
          @click="savePassword"
        >
          Simpan Password
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- Global Snackbar Alert -->
  <v-snackbar v-model="snackbar" :color="snackbarColor" :timeout="3000" location="top right">
    {{ snackbarText }}
  </v-snackbar>
</template>

<style scoped lang="scss">
.text-disabled-row {
  opacity: 0.6;
  td {
    text-decoration: line-through;
    text-decoration-color: rgba(0, 0, 0, 0.15);
  }
}
.cursor-pointer {
  cursor: pointer;
}
.gap-2 {
  gap: 8px;
}
.gap-3 {
  gap: 12px;
}

// Styling to make the search field border uniform and matches other borders
.search-input {
  max-width: 320px;
  width: 100%;
  
  :deep(.v-field__outline) {
    --v-field-border-opacity: 0.8 !important;
    --v-field-border-width: 1px !important;
    color: rgb(var(--v-theme-borderLight)) !important;
  }
  
  :deep(.v-field--focused .v-field__outline) {
    --v-field-border-width: 1px !important;
    color: rgb(var(--v-theme-primary)) !important;
  }
}
</style>
