<template>
  <div class="container">
    <!-- <h2>Farmer Management ({{ farmers.length }})</h2> -->
    <!-- <table class="table" v-if="farmers.length"> -->
    <h2>Farmer Management</h2>
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
        <tr v-for="(farmer, index) in farmers" :key="farmer.id">
          <td>{{ index + 1 }}</td>
          <!-- <td>{{ farmer.id }}</td> -->
          <td>{{ farmer.data.name }}</td>
          <td>{{ farmer.data.address }}</td>
          <td>{{ farmer.data.phone_number }}</td>
          <!-- Menampilkan status aktif atau tidak -->
          <td>{{ farmer.is_active ? 'Active' : 'Inactive' }}</td>
          <td><a :href="farmer.attachment_url" target="_blank">View Attachment</a></td>
          <td>
            <!-- Tombol Detail yang mengarahkan ke halaman detail -->
            <button @click="viewFarmerDetail(farmer.id)" class="btn btn-info btn-sm">Detail</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- <p v-else>Loading or no data…</p> -->
  </div>
</template>
  
<script>
import axios from 'axios';

export default {
  name: 'FarmersManagement',
  data() {
    return {
      farmers: [], // Untuk menyimpan data farmers
    };
  },
  created() {
    // const token = localStorage.getItem('auth_token');

    // // Jika tidak login, arahkan ke halaman login
    // if (!token) {
    //   this.$router.push({ name: 'login' });
    // } else {
    //   // Ambil data farmers dari API
    //   this.fetchFarmers();
    // }
    
    // console.log('component mounted');
    this.fetchFarmers();
  },
  methods: {
    fetchFarmers() {
      const token = localStorage.getItem('auth_token');
      
      axios
        .get('http://127.0.0.1:8000/api/farmers', {
          headers: {
            Authorization: `Bearer ${token}`, // Kirim token untuk otentikasi
          },
        })
        .then((response) => {
          // console.log('response', response.data);
          this.farmers = response.data.farmer; // Simpan data farmers
          // console.log('saved', this.farmers);
        })
        .catch((error) => {
          console.error('Error fetching farmers:', error);
        });
    },
    // / Metode untuk menangani tombol Detail
    viewFarmerDetail(farmerId) {
      // Arahkan ke halaman detail dengan menggunakan route parameter
      this.$router.push({ name: 'farmer-detail', params: { id: farmerId } });
    },
  },
};
</script>

<style scoped>
/* Styling untuk tabel */
table {
  margin-top: 20px;
}

th, td {
  text-align: center;
}

th {
  background-color: #f8f9fa;
}
</style>
  