<template>
    <div class="container">
    <h2>{{ message }}</h2>
    <p>Welcome to your dashboard, {{ user.name }}</p>
    <!-- Tombol Logout -->
    <!-- <button @click="logout" class="btn btn-danger">Logout</button> -->

    <!-- Row of cards -->
    <div class="row g-4">
      <!-- Card 1 : Placeholder Pie-Chart -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-primary text-white">
            Distribusi Pendanaan
          </div>
          <div class="card-body d-flex justify-content-center align-items-center">
            <div class="pie-chart">
              <span class="center-text">65%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 : Placeholder Bar-Chart -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-success text-white">
            Loan&nbsp;Status&nbsp;Summary
          </div>
          <div class="card-body">
            <div class="bar-chart">
              <div class="bar bar-1" style="--value: 70%"></div>
              <div class="bar bar-2" style="--value: 40%"></div>
              <div class="bar bar-3" style="--value: 90%"></div>
              <div class="bar bar-4" style="--value: 55%"></div>
            </div>
            <div class="d-flex justify-content-between small mt-2 px-1">
              <span>Active</span><span>Pending</span><span>Paid</span><span>Late</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
  import axios from 'axios';

  export default {
    data() {
      return {
        message: 'Welcome to Dashboard',
        user: {},
      };
    },
    created() {
      const token = localStorage.getItem('auth_token');
      if (!token) {
        this.$router.push({ name: 'login' });  // Redirect ke login jika token tidak ada
      } else {
          // Cek ke backend untuk memverifikasi token dan mendapatkan data pengguna
        axios
          .get('http://127.0.0.1:8000/api/dashboard', {
            headers: { Authorization: `Bearer ${token}` },
          })
          .then((response) => {
            if (response.data.success) {
              this.user = response.data.user;
            }
          })
          .catch((error) => {
            console.error('Authentication failed:', error);
            this.$router.push({ name: 'login' }); // Redirect ke login jika gagal autentikasi
          });
      }
    },
    // methods: {
    //   logout() {
    //     // Menghapus token dan data pengguna dari localStorage
    //     localStorage.removeItem('auth_token');
    //     localStorage.removeItem('user');
    //     this.$router.push({ name: 'login' });  // Redirect ke halaman login setelah logout
    //   }
    // }
  };
</script>
  
<style scoped>
/* ===== Pie-chart placeholder ===== */
.pie-chart {
  --size: 180px;
  width: var(--size);
  height: var(--size);
  background:
    conic-gradient(#4e73df 0 235deg, #1cc88a 0 360deg);
  border-radius: 50%;
  position: relative;
}
.pie-chart::after {
  content: '';
  position: absolute;
  inset: 15%;
  background: #fff;
  border-radius: 50%;
}
.center-text {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.25rem;
}

/* ===== Bar-chart placeholder ===== */
.bar-chart {
  display: flex;
  align-items: flex-end;
  height: 160px;
  gap: 14px;
  padding-inline: 14px;
}
.bar {
  flex: 1;
  background: linear-gradient(180deg, #1cc88a 0%, #198754 100%);
  border-radius: 6px 6px 0 0;
  height: var(--value);
  transition: height .4s;
}
.bar-2 { background: linear-gradient(180deg,#f6c23e 0%,#dda20a 100%); }
.bar-3 { background: linear-gradient(180deg,#4e73df 0%,#224abe 100%); }
.bar-4 { background: linear-gradient(180deg,#e74a3b 0%,#be261a 100%); }
</style>