<template>
  <div class="container">
    <h2>User Management</h2>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <!-- <th>ID</th> -->
          <th>Name</th>
          <th>Email</th>
          <th>Jenis Role</th>
          <!-- <th>Created At</th> -->
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, index) in users" :key="user.id">
          <td>{{ index + 1 }}</td>
          <!-- <td>{{ user.id }}</td> -->
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.role_name }}</td>
          <td>
            <!-- Tombol Detail yang mengarahkan ke halaman detail -->
            <button @click="viewUserDetail(user.id)" class="btn btn-info btn-sm">Detail</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
  
<script setup>
// import axios from 'axios';

// export default {
//   data() {
//     return {
//       users: [], // Untuk menyimpan data pengguna
//     };
//   },
//   created() {
//     // const token = localStorage.getItem('auth_token');
//     // const userRole = localStorage.getItem('user_role'); // Ambil role yang disimpan di localStorage

//     // // Jika tidak login atau role bukan administrator, arahkan ke dashboard
//     // if (!token || userRole !== 'Administrator') {
//     //   this.$router.push({ name: 'dashboard' });
//     // // } else if (userRole !== 'Administrator') {
//     // //   this.$router.push({ name: 'dashboard' });
//     // } else {
//     //   // Ambil data pengguna dari API jika role adalah administrator
//     //   this.fetchUsers();
//     // }

//     if (!localStorage.getItem('auth_token')) this.$router.push({ name:'login' });
//     else this.fetchUsers();
//   },
//   // methods: {
//   //   fetchUsers() {
//   //     const token = localStorage.getItem('auth_token');
//   //     axios
//   //       .get('http://127.0.0.1:8000/api/users', {
//   //         headers: {
//   //           Authorization: `Bearer ${token}`,
//   //         },
//   //       })
//   //       .then((response) => {
//   //         this.users = response.data.data; // Menyimpan data pengguna
//   //       })
//   //       .catch((error) => {
//   //         console.error('Error fetching users:', error);
//   //       });
//   //   },
//   // },
// };

import { onMounted, ref } from 'vue';
import { useRouter }       from 'vue-router';
import axios               from 'axios';

const users  = ref([]);
const router = useRouter();

onMounted(fetchUsers);

function fetchUsers() {
  const token = localStorage.getItem('auth_token');
  if (!token) return router.push({ name:'login' });

  axios.get('http://127.0.0.1:8000/api/users', {
        headers:{ Authorization:`Bearer ${token}` }
      })
      .then(res => users.value = res.data.users)
      .catch(err => {
        console.error(err);
        router.push({ name:'dashboard' });
      });
}

function viewUserDetail(userId) {
  router.push({ name: 'user-detail', params: { id: userId } });
}
</script>

<style scoped>
table {
  margin-top: 20px;
}
</style>
  