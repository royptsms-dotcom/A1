<script setup lang="ts">
import { useCustomizerStore } from '../../../stores/customizer';
import { useUIStore } from '@/stores/ui';
import { useAuthStore } from '@/stores/auth';
// icons
import { MenuFoldOutlined, SearchOutlined, GithubOutlined } from '@ant-design/icons-vue';

// dropdown imports
import NotificationDD from './NotificationDD.vue';
import Searchbar from './SearchBarPanel.vue';
import ProfileDD from './ProfileDD.vue';
import { useDisplay } from 'vuetify';
import { useRoute } from 'vue-router';

const customizer = useCustomizerStore();
const uiStore = useUIStore();
const authStore = useAuthStore();
const { lgAndUp, mdAndDown } = useDisplay();
const route = useRoute();
</script>

<template>
  <v-app-bar elevation="0" height="60">
    <!-- Desktop Hamburger -->
    <v-btn
      v-if="lgAndUp"
      class="text-secondary mr-3"
      color="darkText"
      icon
      rounded="sm"
      variant="text"
      @click.stop="customizer.SET_MINI_SIDEBAR(!customizer.mini_sidebar)"
      size="small"
    >
      <MenuFoldOutlined :style="{ fontSize: '16px' }" />
    </v-btn>
    <!-- Mobile Hamburger -->
    <v-btn
      v-if="mdAndDown"
      class="text-secondary ms-3"
      color="darkText"
      icon
      rounded="sm"
      variant="text"
      @click.stop="customizer.SET_SIDEBAR_DRAWER"
      size="small"
    >
      <MenuFoldOutlined :style="{ fontSize: '16px' }" />
    </v-btn>

    <!-- Dynamic Active Page Title next to hamburger menu -->
    <div class="ml-2 d-flex align-self-center" style="height: 60px; line-height: 60px;">
      <span class="text-subtitle-1 font-weight-bold text-darkText" style="font-size: 16px !important;">
        {{ route.meta.title || route.name }}
      </span>
    </div>

    <!-- search mobile removed -->
    <!-- <v-menu :close-on-content-click="false" v-if="mdAndDown" offset="10, 0">
      <template v-slot:activator="{ props }">
        <v-btn
          class="text-secondary ml-1"
          color="lightsecondary"
          icon
          rounded="sm"
          variant="flat"
          size="small"
          v-bind="props"
        >
          <SearchOutlined :style="{ fontSize: '17px' }" />
        </v-btn>
      </template>
      <v-sheet class="search-sheet v-col-12 pa-0" width="320">
        <v-text-field persistent-placeholder placeholder="Search here.." color="primary" variant="solo" hide-details>
          <template v-slot:prepend-inner>
            <SearchOutlined :style="{ fontSize: '17px' }" />
          </template>
        </v-text-field>
      </v-sheet>
    </v-menu> -->

    <!-- Search part desktop removed -->
    <!-- <v-sheet v-if="lgAndUp" width="250">
      <Searchbar />
    </v-sheet> -->

    <!---/Search part -->

    <v-spacer />
    <!-- ---------------------------------------------- -->
    <!---right part -->
    <!-- ---------------------------------------------- -->

    <!-- ---------------------------------------------- -->
    <!-- Github -->
    <!-- ---------------------------------------------- -->
    <v-btn
      icon
      class="text-secondary hidden-sm-and-down d-flex"
      color="darkText"
      rounded="sm"
      variant="text"
      href="https://github.com/codedthemes/mantis-free-vuetify-vuejs-admin-template"
      target="_blank"
    >
      <GithubOutlined :style="{ fontSize: '16px' }" />
    </v-btn>

    <!-- ---------------------------------------------- -->
    <!-- Notification -->
    <!-- ---------------------------------------------- -->
    <NotificationDD />

    <!-- ---------------------------------------------- -->
    <!-- User Profile -->
    <!-- ---------------------------------------------- -->
    <v-menu :close-on-content-click="false" offset="10, 0" min-width="360" max-width="360">
      <template v-slot:activator="{ props }">
        <v-btn class="profileBtn" variant="text" rounded="sm" v-bind="props">
          <div class="d-flex align-center">
            <v-avatar class="mr-sm-2 mr-0 py-2">
              <img src="@/assets/images/users/avatar-1.png" alt="Julia" />
            </v-avatar>
            <h6 class="text-subtitle-1 mb-0 d-sm-block d-none">{{ authStore.user?.name || 'JWT User' }}</h6>
          </div>
        </v-btn>
      </template>
      <ProfileDD />
    </v-menu>
  </v-app-bar>
</template>

<style scoped>
/* Remove Vuetify's dark hover/focus overlay on header icon buttons */
:deep(.v-btn) > .v-btn__overlay {
  opacity: 0 !important;
  background: transparent !important;
}
</style>
