<template>
  <div class="container">
    <h2>Role Management</h2>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <!-- <th>ID</th> -->
          <th>Name</th>
          <th>Status</th>
          <!-- <th>Created At</th>
          <th>Updated At</th> -->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(role, index) in roles" :key="role.id">
          <td>{{ index + 1 }}</td>
          <!-- <td>{{ role.id }}</td> -->
          <td>{{ role.name }}</td>
          <td>{{ role.is_active ? 'Active' : 'Inactive' }}</td>
          <!-- <td>{{ role.created_at }}</td>
          <td>{{ role.updated_at }}</td> -->
          <td>
            <!-- Tombol Detail yang mengarahkan ke halaman detail -->
            <button @click="viewRoleDetail(role.id)" class="btn btn-info btn-sm">Detail</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
  
<script>
import axios from 'axios';

export default {
  data() {
    return {
      roles: [], // Untuk menyimpan data roles
    };
  },
  created() {
    const token = localStorage.getItem('auth_token');

    // Jika tidak login, arahkan ke halaman login
    if (!token) {
      this.$router.push({ name: 'login' });
    } else {
      // Ambil data roles dari API
      this.fetchRoles();
    }
  },
  methods: {
    fetchRoles() {
      const token = localStorage.getItem('auth_token');
      
      axios
        .get('http://127.0.0.1:8000/api/roles', {
          headers: {
            Authorization: `Bearer ${token}`, // Kirim token untuk otentikasi
          },
        })
        .then((response) => {
          this.roles = response.data.roles; // Simpan data roles
        })
        .catch((error) => {
          console.error('Error fetching roles:', error);
        });
    },
    // / Metode untuk menangani tombol Detail
    viewRoleDetail(roleId) {
      // Arahkan ke halaman detail dengan menggunakan route parameter
      this.$router.push({ name: 'role-detail', params: { id: roleId } });
    },
  },
};
</script>

<style scoped>
table {
  margin-top: 20px;
}
</style>
