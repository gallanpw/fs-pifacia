<template>
    <div class="container">
      <h2>Loan Detail</h2>
      <div v-if="loan">
        <p><strong>Loan Name:</strong> {{ loan.data.name }}</p>
        <p><strong>Farmer:</strong> {{ loan.farmer_name }}</p>
        <p><strong>Funder:</strong> {{ loan.funder_name }}</p>
        <p><strong>Description:</strong> {{ loan.data.description }}</p>
        <p><strong>Status:</strong> {{ loan.is_active ? 'Active' : 'Inactive' }}</p>
        <p><strong>Attachment:</strong> {{ loan.attachment_url }}</p>
      </div>
      <div v-else>
        <p>Loading loan details...</p>
      </div>
      <!-- Tombol Kembali ke Loan Management -->
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
        loan: null, // Menyimpan detail loan
      };
    },
  
    created() {
      this.fetchLoanDetail();
    },
  
    methods: {
      fetchLoanDetail() {
        const token = localStorage.getItem('auth_token');
        axios
          .get(`http://127.0.0.1:8000/api/loans/${this.id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          })
          .then((response) => {
            // console.log(response.data);
            // this.loan = response.data; // Menyimpan data loan
            this.loan = response.data.loan;
            // console.log('loan detail →', this.loan);
          })
          .catch((error) => {
            console.error('Error fetching loan details:', error);
          });
      },
      goBack() {
        this.$router.push({ name: 'loan-management' }); // Kembali ke halaman Loan Management
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
  