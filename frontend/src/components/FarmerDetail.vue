<template>
    <div class="container">
      <h2>Farmer Detail</h2>
      <div v-if="farmer">
        <p><strong>Name:</strong> {{ farmer.data.name }}</p>
        <p><strong>Address:</strong> {{ farmer.data.address }}</p>
        <p><strong>Phone Number:</strong> {{ farmer.data.phone_number }}</p>
        <p><strong>Status:</strong> {{ farmer.is_active ? 'Active' : 'Inactive' }}</p>
        <p><strong>Attachment:</strong> {{ farmer.attachment_url }}</p>
      </div>
      <div v-else>
        <p>Loading farmer details...</p>
      </div>
      <!-- Tombol Kembali ke Farmer Management -->
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
        farmer: null, // Menyimpan detail farmer
      };
    },
  
    created() {
      this.fetchFarmerDetail();
    },
  
    methods: {
      fetchFarmerDetail() {
        const token = localStorage.getItem('auth_token');
        axios
          .get(`http://127.0.0.1:8000/api/farmers/${this.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            // console.log(response.data);
            // this.farmer = response.data; // Menyimpan data farmer
            this.farmer = response.data.farmer;
            // console.log('farmer detail →', this.farmer);
          })
          .catch((error) => {
            console.error('Error fetching farmer details:', error);
          });
      },
      goBack() {
        this.$router.push({ name: 'farmer-management' }); // Kembali ke halaman Farmer Management
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
  