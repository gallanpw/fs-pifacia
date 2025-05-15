import axios from 'axios'

export default axios.create({
  baseURL: 'http://localhost:8000/api',  // sesuaikan port Laravel
  withCredentials: true,                 // untuk Sanctum SPA auth nanti
})
