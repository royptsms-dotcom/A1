import { createRouter, createWebHistory } from 'vue-router';
import MainRoutes from './MainRoutes';
import PublicRoutes from './PublicRoutes';
import { useAuthStore } from '@/stores/auth';
import { useUIStore } from '@/stores/ui';

export const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/:pathMatch(.*)*',
      component: () => import('@/views/pages/maintenance/error/Error404Page.vue')
    },
    MainRoutes,
    PublicRoutes
  ]
});

// Auth guard — redirect unauthenticated users to /login
router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore();
  const authRequired = to.matched.some((record) => record.meta.requiresAuth);

  if (authRequired && !auth.user) {
    // Not logged in — save intended destination and redirect to login
    auth.returnUrl = to.fullPath;
    next('/login');
  } else if (auth.user && to.path === '/login') {
    // Already logged in — redirect to dashboard
    next('/dashboard');
  } else {
    next();
  }
});

// Loading state guards
router.beforeEach(() => {
  const uiStore = useUIStore();
  uiStore.isLoading = true;
});

router.afterEach(() => {
  const uiStore = useUIStore();
  uiStore.isLoading = false;
});
