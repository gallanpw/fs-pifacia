<template>
    <div class="container">
      <h2>Loan Management</h2>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Loan&nbsp;Name</th>
            <th>Farmer</th>
            <th>Funder</th>
            <th>Description</th>
            <th>Status</th>
            <th>Attachment</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(loan, index) in loans" :key="loan.id">
            <td>{{ index + 1 }}</td>
            <!-- <td>{{ loan.id }}</td> -->
            <td>{{ loan.data.name }}</td>
            <td>{{ loan.farmer_name }}</td>
            <td>{{ loan.funder_name }}</td>
            <td>{{ loan.data.description }}</td>
            <!-- Menampilkan status aktif atau tidak -->
            <td>{{ loan.is_active ? 'Active' : 'Inactive' }}</td>
            <td><a :href="loan.attachment_url" target="_blank">View Attachment</a></td>
            <td>
              <!-- Tombol Detail yang mengarahkan ke halaman detail -->
              <button @click="viewLoanDetail(loan.id)" class="btn btn-info btn-sm">Detail</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>
  
<script>
import axios from 'axios';

  export default {
    name: 'LoanManagement',
    data() {
      return {
        loans: [], // Untuk menyimpan data loans
      };
    },
    created() {
      this.fetchLoans();
    },
    methods: {
      fetchLoans() {
        const token = localStorage.getItem('auth_token');
        
        axios
          .get('http://127.0.0.1:8000/api/loans', {
            headers: {
              Authorization: `Bearer ${token}`, // Kirim token untuk otentikasi
            },
          })
          .then((response) => {
            // console.log('response', response.data);
            this.loans = response.data.loan; // Simpan data loans
            // console.log('saved', this.loans);
          })
          .catch((error) => {
            console.error('Error fetching loans:', error);
          });
      },
      // / Metode untuk menangani tombol Detail
      viewLoanDetail(loanId) {
        // Arahkan ke halaman detail dengan menggunakan route parameter
        this.$router.push({ name: 'loan-detail', params: { id: loanId } });
      },
    },
  };
</script>
  