<template>
    <div class="container">
      <h2>Role Detail</h2>
      <div v-if="role">
        <p><strong>Name:</strong> {{ role.name }}</p>
        <p><strong>Status:</strong> {{ role.is_active ? 'Active' : 'Inactive' }}</p>
        <p><strong>Created At:</strong> {{ role.created_at }}</p>
        <p><strong>Updated At:</strong> {{ role.updated_at }}</p>
      </div>
      <div v-else>
        <p>Loading role details...</p>
      </div>
      <!-- Tombol Kembali ke Role Management -->
      <div class="mt-3">
        <button @click="goBack" class="btn btn-secondary">Kembali</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: ['id'], // Menerima parameter id dari route
  
    data() {
      return {
        role: null, // Menyimpan detail role
      };
    },
  
    created() {
      this.fetchRoleDetail();
    },
  
    methods: {
      fetchRoleDetail() {
        const token = localStorage.getItem('auth_token');
        axios
          .get(`http://127.0.0.1:8000/api/roles/${this.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            // console.log(response.data);
            // this.role = response.data; // Menyimpan data role
            this.role = response.data.role;
            // console.log('role detail →', this.role);
          })
          .catch((error) => {
            console.error('Error fetching role details:', error);
          });
      },
      goBack() {
        this.$router.push({ name: 'role-management' }); // Kembali ke halaman Role Management
      },
    },
  };
  </script>
  
  <style scoped>
  /* Styling untuk halaman detail */
  .container {
    margin-top: 20px;
  }
  </style>
  