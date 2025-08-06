import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/components/Home.vue'; // Home
import Login from '@/components/Login.vue';
import Dashboard from '@/components/Dashboard.vue';
import FarmerManagement from '@/components/FarmerManagement.vue';
import FarmerDetail from '@/components/FarmerDetail.vue';
import FunderManagement from '@/components/FunderManagement.vue';
import FunderDetail from '@/components/FunderDetail.vue';
import LoanManagement from '@/components/LoanManagement.vue';
import LoanDetail from '@/components/LoanDetail.vue';
import ActivityHistory from '@/components/ActivityHistory.vue';
import RoleManagement from '@/components/RoleManagement.vue';
import RoleDetail from '@/components/RoleDetail.vue';
import UserManagement from '@/components/UserManagement.vue';
import UserDetail from '@/components/UserDetail.vue';
import HelloWorld from '@/components/HelloWorld.vue';

const routes = [
  {
    path: '/hello',
    name: 'hello',
    component: HelloWorld,
  },
  {
    path: '/',
    name: 'home',
    component: Home, // Komponen default untuk rute /
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    // beforeEnter: (to, from, next) => {
    //     const token = localStorage.getItem('auth_token');
    //     if (!token) {
    //       next({ name: 'login' }); // Redirect ke login jika token tidak ada
    //     } else {
    //       next(); // Lanjutkan ke dashboard jika token ada
    //     }
    //   },
  },
  {
    path: '/farmer-management',
    name: 'farmer-management',
    component: FarmerManagement,
    meta: { requiresAuth: true },
  },
  {
    path: '/farmer-detail/:id',
    name: 'farmer-detail',
    component: FarmerDetail,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/funder-management',
    name: 'funder-management',
    component: FunderManagement,
    meta: { requiresAuth: true },
  },
  {
    path: '/funder-detail/:id',
    name: 'funder-detail',
    component: FunderDetail,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/loan-management',
    name: 'loan-management',
    component: LoanManagement,
    meta: { requiresAuth: true },
  },
  {
    path: '/loan-detail/:id',
    name: 'loan-detail',
    component: LoanDetail,
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: '/activity-history',
    name: 'activity-history',
    component: ActivityHistory,
    meta: { requiresAuth: true },
  },
  {
    path: '/role-management',
    name: 'role-management',
    component: RoleManagement,
    meta: { requiresAuth: true }, // Semua role bisa mengakses halaman ini
  },
  {
    path: '/role-detail/:id',
    name: 'role-detail',
    component: RoleDetail,
    props: true, // Menambahkan roleId ke komponen RoleDetail
    meta: { requiresAuth: true }, // Semua role bisa mengakses halaman ini
  },
  {
    path: '/user-management',
    name: 'user-management',
    component: UserManagement,
    meta: { requiresAuth: true, requiresAdmin: true }, // Only accessible by admin
  },
  {
    path: '/user-detail/:id',
    name: 'user-detail',
    component: UserDetail,
    props: true, // Menambahkan userId ke komponen UserDetail
    meta: { requiresAuth: true, requiresAdmin: true }, // Semua role bisa mengakses halaman ini
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guestOnly: true }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token  = localStorage.getItem('auth_token');
  const role   = localStorage.getItem('user_role_name');
  // const roleId = localStorage.getItem('user_role_id');
  // const ADMIN_ID = 'baa386dc-8048-4ffa-b354-94008f32d5fd';

  // if (to.meta.requiresAuth && !token) {
  //   next({ name: 'login' });
  // } else if (to.meta.requiresAdmin) {
  //   if (role === 'Administrator' || roleId === ADMIN_ID) next();
  //   else next({ name: 'dashboard' });
  // } else {
  //   next();
  // }

  // if (to.meta.requiresAuth && !token) next({ name:'login' });
  // else if (to.meta.requiresAdmin &&
  //         !(role==='Administrator'||roleId===ADMIN_ID)) next({ name:'dashboard' });
  // else next();

  // 🔒 rute hanya untuk tamu
  // if (to.meta.guestOnly && token)
  //   return next({ name: 'dashboard' });
  
  if (to.meta.requiresAuth && !token)
    return next({ name: 'login' });
  
  // if (to.meta.requiresAdmin && !(role === 'Administrator' || roleId === ADMIN_ID))
  //   return next({ name: 'dashboard' });
  
  if (to.meta.requiresAdmin && role !== 'Administrator')
      return next({ name:'dashboard' });
  
  next();
});

export default router;
