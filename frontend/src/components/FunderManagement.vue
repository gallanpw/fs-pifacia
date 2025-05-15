<template>
    <div class="container">
      <h2>Funder Management</h2>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Status</th>
            <th>Attachment</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(funder, index) in funders" :key="funder.id">
            <td>{{ index + 1 }}</td>
            <!-- <td>{{ funder.id }}</td> -->
            <td>{{ funder.data.name }}</td>
            <td>{{ funder.data.address }}</td>
            <td>{{ funder.data.phone_number }}</td>
            <!-- Menampilkan status aktif atau tidak -->
            <td>{{ funder.is_active ? 'Active' : 'Inactive' }}</td>
            <td><a :href="funder.attachment_url" target="_blank">View Attachment</a></td>
            <td>
              <!-- Tombol Detail yang mengarahkan ke halaman detail -->
              <button @click="viewFunderDetail(funder.id)" class="btn btn-info btn-sm">Detail</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
  name: 'FunderManagement',
  data() {
    return {
      funders: [], // Untuk menyimpan data funders
    };
  },
  created() {
    this.fetchFunders();
  },
  methods: {
    fetchFunders() {
      const token = localStorage.getItem('auth_token');
      
      axios
        .get('http://127.0.0.1:8000/api/funders', {
          headers: {
            Authorization: `Bearer ${token}`, // Kirim token untuk otentikasi
          },
        })
        .then((response) => {
          // console.log('response', response.data);
          this.funders = response.data.funder; // Simpan data farmers
          // console.log('saved', this.farmers);
        })
        .catch((error) => {
          console.error('Error fetching farmers:', error);
        });
    },
    // / Metode untuk menangani tombol Detail
    viewFunderDetail(funderId) {
      // Arahkan ke halaman detail dengan menggunakan route parameter
      this.$router.push({ name: 'funder-detail', params: { id: funderId } });
    },
  },
};
</script>
  