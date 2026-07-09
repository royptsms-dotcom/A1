const MainRoutes = {
  path: '/main',
  meta: {
    requiresAuth: true
  },
  redirect: '/main',
  component: () => import('@/layouts/dashboard/DashboardLayout.vue'),
  children: [
    {
      name: 'LandingPage',
      path: '/',
      component: () => import('@/views/dashboard/DefaultDashboard.vue'),
      meta: { title: 'Dashboard' }
    },
    {
      name: 'Dashboard',
      path: '/dashboard',
      component: () => import('@/views/dashboard/DefaultDashboard.vue'),
      meta: { title: 'Dashboard' }
    },
    {
      name: 'Typography',
      path: '/typography',
      component: () => import('@/views/typography/TypographyPage.vue'),
      meta: { title: 'Tipografi' }
    },
    {
      name: 'Colors',
      path: '/colors',
      component: () => import('@/views/colors/ColorPage.vue'),
      meta: { title: 'Warna' }
    },
    {
      name: 'Shadow',
      path: '/shadow',
      component: () => import('@/views/shadows/ShadowPage.vue'),
      meta: { title: 'Bayangan' }
    },
    {
      name: 'Color',
      path: '/icon/ant',
      component: () => import('@/views/icons/AntDesignIcons.vue'),
      meta: { title: 'Ikon Ant Design' }
    },
    {
      name: 'other',
      path: '/sample-page',
      component: () => import('@/views/StarterPage.vue'),
      meta: { title: 'Halaman Contoh' }
    },
    {
      name: 'GenerateSertifikat',
      path: '/generate-sertifikat',
      component: () => import('@/views/sertifikat/GenerateSertifikatPage.vue'),
      meta: { title: 'Generate Sertifikat' }
    },
    {
      name: 'UserList',
      path: '/user',
      component: () => import('@/views/user/UserListPage.vue'),
      meta: { title: 'Daftar Karyawan' }
    }
  ]
};

export default MainRoutes;
