<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router      = useRouter();
const route       = useRoute(); // ← rute aktif
const isLoggedIn = ref(!!localStorage.getItem('auth_token'));

/* tombol Login hanya muncul bila:
   - belum login
   - TIDAK sedang berada di rute 'login'                      */
   const showLoginBtn = computed(() =>
  !isLoggedIn.value && route.name !== 'login'
);

onMounted(() => {
  // ketika tab lain / login menulis localStorage, perbarui UI
  window.addEventListener('storage', () => {
    isLoggedIn.value = !!localStorage.getItem('auth_token');
  });
});

function goToLogin() {
  router.push({ name: 'login' });
}

function logout() {
  localStorage.clear();
  window.dispatchEvent(new Event('storage'));
  router.push({ name: 'login' });
}

</script>

<template>
  <div class="container">

    <div class="row">
      <nav class="navbar" style="background-color: #FFF8DC;">
        <div class="container-fluid">
          <!-- <a class="navbar-brand" href="#">Navbar</a> -->
          <router-link to="/" class="nav-link text-black">Home</router-link>
          <!-- <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form> -->
          <button v-if="showLoginBtn" @click="goToLogin" class="btn btn-primary">Login</button>
          <button v-else-if="isLoggedIn" @click="logout" class="btn btn-danger">Logout</button>
        </div>
      </nav>
    </div>

    <!-- Sidebar -->
    <div class="row">
      <div v-if="isLoggedIn" id="sidebar" class="col bg-light text-black col-2 p-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link text-black">Dashboard</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/farmer-management" class="nav-link text-black">Farmer Management</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/funder-management" class="nav-link text-black">Funder Management</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/loan-management" class="nav-link text-black">Loan Management</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/activity-history" class="nav-link text-black">Activity History</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/role-management" class="nav-link text-black">Role Management</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/user-management" class="nav-link text-black">User Management</router-link>
            </li>
          </ul>
      </div>

      
      <!-- <div class="col" style="border: 2px solid blue;">
        <router-view></router-view>
      </div> -->

      <!-- konten -->
      <main :class="isLoggedIn ? 'col-10' : 'col-12'">
        <router-view />
      </main>
    </div>


    <!-- <div v-else>
      <router-view></router-view>
    </div> -->

    <!-- Layout -->
    <!-- <div class="row">
      <aside v-if="isLoggedIn" id="sidebar" class="col-2 bg-light p-3">
        <ul class="nav flex-column">
          <li class="nav-item"><router-link to="/dashboard"          class="nav-link">Dashboard</router-link></li>
          <li class="nav-item"><router-link to="/farmer-management"  class="nav-link">Farmer Management</router-link></li>
          <li class="nav-item"><router-link to="/funder-management"  class="nav-link">Funder Management</router-link></li>
          <li class="nav-item"><router-link to="/loan-management"    class="nav-link">Loan Management</router-link></li>
          <li class="nav-item"><router-link to="/activity-history"   class="nav-link">Activity History</router-link></li>
          <li class="nav-item"><router-link to="/role-management"    class="nav-link">Role Management</router-link></li>
          <li class="nav-item"><router-link to="/user-management"    class="nav-link">User Management</router-link></li>
        </ul>
      </aside>  
    </div> -->
    
  </div>
</template>

<style scoped>
#sidebar {
  height: 100vh;
}

.nav-item {
  margin-bottom: 10px;
}

.nav-link {
  color: white;
}

.nav-link:hover {
  text-decoration: underline;
}

#app {
  display: flex;
  min-height: 100vh;
}
</style>
