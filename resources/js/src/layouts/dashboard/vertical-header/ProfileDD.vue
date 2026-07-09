<script setup lang="ts">
import { ref, computed } from 'vue';
import {
  LogoutOutlined,
  UserOutlined,
  SettingOutlined,
  QuestionCircleOutlined,
  LockOutlined,
  CommentOutlined,
  UnorderedListOutlined,
  EditOutlined,
  ProfileOutlined,
  WalletOutlined
} from '@ant-design/icons-vue';
import { useAuthStore } from '@/stores/auth';

const tab = ref('111');
const authStore = useAuthStore();

const userName = computed(() => authStore.user?.name || 'JWT User');
const userRole = computed(() => authStore.user?.role || 'Administrator');
const userEmail = computed(() => authStore.user?.email || 'user@royptsms.com');
</script>

<template>
  <div class="profile-dd-container">
    <!-- Header: User Info -->
    <div class="profile-header d-flex align-center pa-5 pb-4">
      <v-avatar size="44" class="mr-3 border-avatar">
        <img src="@/assets/images/users/avatar-1.png" alt="User Avatar" />
      </v-avatar>
      <div class="user-info flex-grow-1 min-width-0">
        <h6 class="text-subtitle-1 font-weight-bold mb-0 text-truncate text-darkText">{{ userName }}</h6>
        <p class="text-caption mb-0 text-truncate text-medium-emphasis">{{ userRole }}</p>
      </div>
      <div class="ml-2">
        <v-btn
          variant="tonal"
          color="error"
          rounded="pill"
          size="small"
          icon
          title="Keluar"
          @click="authStore.logout()"
          class="logout-btn"
        >
          <LogoutOutlined style="font-size: 14px;" />
        </v-btn>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <v-tabs v-model="tab" color="primary" grow density="comfortable" class="profile-tabs px-2">
      <v-tab value="111" class="text-capitalize text-body-2 font-weight-semibold">
        <UserOutlined class="mr-1" style="font-size: 14px;" /> Profil
      </v-tab>
      <v-tab value="222" class="text-capitalize text-body-2 font-weight-semibold">
        <SettingOutlined class="mr-1" style="font-size: 14px;" /> Pengaturan
      </v-tab>
    </v-tabs>

    <!-- Tab Contents -->
    <div class="tab-content-container">
      <v-window v-model="tab" class="mt-2">
        <!-- Tab 1: Profil -->
        <v-window-item value="111">
          <div class="menu-list">
            <div class="menu-item" @click="">
              <EditOutlined class="menu-icon text-primary" />
              <div class="menu-text">
                <span class="menu-title">Edit Profil</span>
                <span class="menu-subtitle">Ubah biodata dan data akun</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <UserOutlined class="menu-icon text-info" />
              <div class="menu-text">
                <span class="menu-title">Lihat Profil</span>
                <span class="menu-subtitle">Tampilan profil publik Anda</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <ProfileOutlined class="menu-icon text-success" />
              <div class="menu-text">
                <span class="menu-title">Profil Sosial</span>
                <span class="menu-subtitle">Hubungkan dengan media sosial</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <WalletOutlined class="menu-icon text-warning" />
              <div class="menu-text">
                <span class="menu-title">Penagihan & Paket</span>
                <span class="menu-subtitle">Detail riwayat pembayaran</span>
              </div>
            </div>

            <v-divider class="my-1 opacity-50" />

            <div class="menu-item menu-item--logout" @click="authStore.logout()">
              <LogoutOutlined class="menu-icon text-error" />
              <div class="menu-text">
                <span class="menu-title text-error font-weight-bold">Keluar Akun</span>
                <span class="menu-subtitle text-error opacity-75">Selesai sesi bekerja Anda</span>
              </div>
            </div>
          </div>
        </v-window-item>

        <!-- Tab 2: Pengaturan -->
        <v-window-item value="222">
          <div class="menu-list">
            <div class="menu-item" @click="">
              <QuestionCircleOutlined class="menu-icon text-primary" />
              <div class="menu-text">
                <span class="menu-title">Pusat Bantuan</span>
                <span class="menu-subtitle">Butuh bantuan? Hubungi kami</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <SettingOutlined class="menu-icon text-secondary" />
              <div class="menu-text">
                <span class="menu-title">Pengaturan Akun</span>
                <span class="menu-subtitle">Ubah setelan & preferensi</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <LockOutlined class="menu-icon text-warning" />
              <div class="menu-text">
                <span class="menu-title">Privasi & Keamanan Center</span>
                <span class="menu-subtitle">Kelola otorisasi data Anda</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <CommentOutlined class="menu-icon text-success" />
              <div class="menu-text">
                <span class="menu-title">Beri Masukan</span>
                <span class="menu-subtitle">Bagikan opini Anda tentang kami</span>
              </div>
            </div>

            <div class="menu-item" @click="">
              <UnorderedListOutlined class="menu-icon text-info" />
              <div class="menu-text">
                <span class="menu-title">Riwayat Aktivitas</span>
                <span class="menu-subtitle">Catatan login dan aksi Anda</span>
              </div>
            </div>
          </div>
        </v-window-item>
      </v-window>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.profile-dd-container {
  background: rgb(var(--v-theme-surface));
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(22, 119, 255, 0.12);
  box-shadow:
    0 8px 24px rgba(0, 0, 0, 0.10),
    0 2px 8px rgba(0, 0, 0, 0.06) !important;
}

.profile-header {
  padding: 18px 28px 16px 28px !important;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  background: linear-gradient(135deg, rgba(22, 119, 255, 0.04) 0%, rgba(22, 119, 255, 0.01) 100%);
}

.border-avatar {
  border: 2px solid rgba(var(--v-theme-primary), 0.15);
}

.logout-btn {
  transition: all 0.2s ease;
  &:hover {
    transform: scale(1.05);
  }
}

.profile-tabs {
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.tab-content-container {
  max-height: 330px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 0, 0, 0.1) transparent;

  &::-webkit-scrollbar {
    width: 4px;
  }
  &::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
  }
}

.menu-list {
  padding: 8px 16px;
}

.menu-item {
  display: flex;
  align-items: center;
  padding: 12px 18px;
  margin-bottom: 6px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.15s ease-in-out;
  gap: 16px;

  &:hover {
    background: rgba(var(--v-theme-primary), 0.04);
  }

  &--logout {
    &:hover {
      background: rgba(var(--v-theme-error), 0.06);
    }
  }
}

.menu-icon {
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
}

.menu-text {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-width: 0;
}

.menu-title {
  font-size: 13px;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.85);
  line-height: 1.2;
  margin-bottom: 2px;
}

.menu-subtitle {
  font-size: 11px;
  color: rgba(0, 0, 0, 0.45);
  line-height: 1.2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
