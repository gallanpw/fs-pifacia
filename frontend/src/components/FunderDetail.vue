<template>
    <div class="container">
      <h2>Funder Detail</h2>
      <div v-if="funder">
        <p><strong>Name:</strong> {{ funder.data.name }}</p>
        <p><strong>Address:</strong> {{ funder.data.address }}</p>
        <p><strong>Phone Number:</strong> {{ funder.data.phone_number }}</p>
        <p><strong>Status:</strong> {{ funder.is_active ? 'Active' : 'Inactive' }}</p>
        <p><strong>Attachment:</strong> {{ funder.attachment_url }}</p>
      </div>
      <div v-else>
        <p>Loading funder details...</p>
      </div>
      <!-- Tombol Kembali ke Funder Management -->
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
        funder: null, // Menyimpan detail funder
      };
    },
  
    created() {
      this.fetchFunderDetail();
    },
  
    methods: {
      fetchFunderDetail() {
        const token = localStorage.getItem('auth_token');
        axios
          .get(`http://127.0.0.1:8000/api/funders/${this.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            // console.log(response.data);
            // this.funder = response.data; // Menyimpan data funder
            this.funder = response.data.funder;
            // console.log('funder detail →', this.funder);
          })
          .catch((error) => {
            console.error('Error fetching funder details:', error);
          });
      },
      goBack() {
        this.$router.push({ name: 'funder-management' }); // Kembali ke halaman Funder Management
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
  