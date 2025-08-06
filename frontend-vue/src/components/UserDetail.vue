<template>
    <div class="container">
      <h2>User Detail</h2>
      <div v-if="user">
        <p><strong>Name:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p><strong>Role:</strong> {{ user.role_name }}</p>
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
        user: null, // Menyimpan detail user
      };
    },
  
    created() {
      this.fetchUserDetail();
    },
  
    methods: {
      fetchUserDetail() {
        const token = localStorage.getItem('auth_token');
        axios
          .get(`http://127.0.0.1:8000/api/users/${this.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            // console.log(response.data);
            // this.role = response.data; // Menyimpan data user
            this.user = response.data.user;
            // console.log('user detail →', this.user);
          })
          .catch((error) => {
            console.error('Error fetching user details:', error);
          });
      },
      goBack() {
        this.$router.push({ name: 'user-management' }); // Kembali ke halaman User Management
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
  