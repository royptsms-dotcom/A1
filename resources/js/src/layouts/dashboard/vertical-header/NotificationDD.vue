<script setup lang="ts">
import { ref } from 'vue';
import { CheckOutlined, GiftOutlined, MessageOutlined, SettingOutlined, BellOutlined, TeamOutlined } from '@ant-design/icons-vue';

const unreadCount = ref(3);

const notifications = ref([
  {
    id: 1,
    icon: GiftOutlined,
    iconColor: '#52c41a',
    iconBg: '#f6ffed',
    title: 'Ulang tahun Cristina Danny',
    message: 'Hari ini adalah hari ulang tahun Cristina Danny.',
    time: '3:00 AM',
    isRead: false,
  },
  {
    id: 2,
    icon: MessageOutlined,
    iconColor: '#1677ff',
    iconBg: '#e6f4ff',
    title: 'Aida Burg berkomentar',
    message: 'Mengomentari posting Anda.',
    time: '6:00 PM · 5 Agt',
    isRead: false,
  },
  {
    id: 3,
    icon: SettingOutlined,
    iconColor: '#ff4d4f',
    iconBg: '#fff1f0',
    title: 'Profil belum lengkap',
    message: 'Profil Anda baru 60% lengkap. Lengkapi sekarang!',
    time: '2:45 PM · 7j lalu',
    isRead: false,
  },
  {
    id: 4,
    icon: TeamOutlined,
    iconColor: '#1677ff',
    iconBg: '#e6f4ff',
    title: 'Undangan Meeting',
    message: 'Cristina Danny mengundang ke Daily Scrum Meeting.',
    time: '9:10 PM',
    isRead: true,
  },
]);

function markAllRead() {
  notifications.value.forEach((n) => (n.isRead = true));
  unreadCount.value = 0;
}

function markRead(id: number) {
  const n = notifications.value.find((n) => n.id === id);
  if (n && !n.isRead) {
    n.isRead = true;
    unreadCount.value = Math.max(0, unreadCount.value - 1);
  }
}
</script>

<template>
  <v-menu :close-on-content-click="false" offset="10, 0" min-width="400" max-width="400">
    <template v-slot:activator="{ props }">
      <v-btn icon class="text-secondary ml-sm-2 ml-1" color="darkText" rounded="sm" size="small" v-bind="props">
        <v-badge
          :content="unreadCount > 0 ? unreadCount.toString() : ''"
          :model-value="unreadCount > 0"
          color="error"
          offset-x="-4"
          offset-y="-5"
        >
          <BellOutlined :style="{ fontSize: '16px' }" />
        </v-badge>
      </v-btn>
    </template>

    <v-card rounded="xl" elevation="12" class="notif-card">
      <!-- Header -->
      <div class="notif-header">
        <div class="d-flex align-center justify-space-between">
          <div class="d-flex align-center" style="gap: 8px;">
            <BellOutlined style="font-size: 16px; color: #1677ff;" />
            <span class="text-subtitle-2 font-weight-bold">Notifikasi</span>
            <v-chip v-if="unreadCount > 0" size="x-small" color="error" variant="flat">
              {{ unreadCount }} baru
            </v-chip>
          </div>
          <v-btn
            v-if="unreadCount > 0"
            variant="tonal"
            color="primary"
            size="x-small"
            rounded="pill"
            @click="markAllRead"
          >
            <CheckOutlined style="font-size: 11px; margin-right: 4px;" />
            Tandai dibaca
          </v-btn>
        </div>
      </div>

      <v-divider />

      <!-- Notification List -->
      <div class="notif-list">
        <div
          v-for="item in notifications"
          :key="item.id"
          class="notif-item"
          :class="{ 'notif-item--unread': !item.isRead }"
          @click="markRead(item.id)"
        >
          <!-- Icon -->
          <div class="notif-icon" :style="{ background: item.iconBg, color: item.iconColor }">
            <component :is="item.icon" style="font-size: 18px;" />
          </div>

          <!-- Text Content -->
          <div class="notif-text">
            <div class="notif-title-row">
              <span class="notif-title">{{ item.title }}</span>
              <span class="notif-time">{{ item.time }}</span>
            </div>
            <p class="notif-message">{{ item.message }}</p>
          </div>

          <!-- Unread indicator -->
          <div v-if="!item.isRead" class="notif-dot" />
        </div>
      </div>

      <v-divider />

      <!-- Footer -->
      <div class="notif-footer">
        <v-btn variant="text" color="primary" size="small" block>
          Lihat Semua Notifikasi →
        </v-btn>
      </div>
    </v-card>
  </v-menu>
</template>

<style lang="scss" scoped>
.notif-card {
  overflow: hidden;
  border: 1px solid rgba(22, 119, 255, 0.12);
  box-shadow:
    0 8px 24px rgba(0, 0, 0, 0.10),
    0 2px 8px rgba(0, 0, 0, 0.06) !important;
}

/* Header */
.notif-header {
  padding: 18px 28px 16px 28px;
  background: linear-gradient(135deg, rgba(22, 119, 255, 0.04) 0%, rgba(22, 119, 255, 0.01) 100%);
}

/* List Container */
.notif-list {
  max-height: 320px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 0, 0, 0.15) transparent;

  &::-webkit-scrollbar { width: 4px; }
  &::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.15);
    border-radius: 4px;
  }
}

/* Individual Item */
.notif-item {
  display: flex;
  align-items: flex-start;
  padding: 16px 28px;
  gap: 16px;
  cursor: pointer;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  transition: background 0.15s ease;

  &:last-child { border-bottom: none; }

  &:hover { background: rgba(0, 0, 0, 0.025); }

  &--unread {
    background: rgba(22, 119, 255, 0.035);
    &:hover { background: rgba(22, 119, 255, 0.07); }
  }
}

/* Icon Circle */
.notif-icon {
  width: 42px;
  height: 42px;
  min-width: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Text Block */
.notif-text {
  flex: 1;
  min-width: 0;
}

.notif-title-row {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 3px;
}

.notif-title {
  font-size: 13px;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.85);
  white-space: normal;
  overflow: hidden;
  text-overflow: ellipsis;
  flex: 1;
  min-width: 0;
}

.notif-time {
  font-size: 11px;
  color: rgba(0, 0, 0, 0.4);
  white-space: nowrap;
  flex-shrink: 0;
}

.notif-message {
  font-size: 12px;
  color: rgba(0, 0, 0, 0.55);
  margin: 0;
  line-height: 1.45;
}

/* Unread Dot */
.notif-dot {
  width: 8px;
  height: 8px;
  min-width: 8px;
  border-radius: 50%;
  background: #1677ff;
  margin-top: 6px;
}

/* Footer */
.notif-footer {
  padding: 10px 28px 12px 28px;
  background: rgba(0, 0, 0, 0.01);
}
</style>
