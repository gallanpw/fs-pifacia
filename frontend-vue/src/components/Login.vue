<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const email = ref('');
const password = ref('');
const errorMsg = ref('');
const router = useRouter();

async function handleLogin() {
  // errorMsg.value = '';
  try {
    const res = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      password: password.value
    });

    // if (!res.data.success) throw new Error('invalid');

    const { token, user } = res.data;
    if (!token) throw new Error('no token');
    localStorage.setItem('auth_token',     token);
    // localStorage.setItem('user_role_id',   user.role_id);
    localStorage.setItem('user_role_name', user.role_name);

    // await fetchAndSaveRoleName(user.role_id);   // simpan user_role
    // await axios.get('http://127.0.0.1:8000/api/roles', {
    //   headers:{ Authorization:`Bearer ${token}` }
    // })
    // .then(r => {
    //   const match = r.data.roles.find(role => role.id === user.role_id);
    //   if (match) localStorage.setItem('user_role', match.name);
    // });

    // Beri tahu App.vue bahwa status login berubah
    window.dispatchEvent(new Event('storage'));
    router.push({ name: 'dashboard' });
  } catch (err) {
    console.error(err);
    errorMsg.value = 'Login failed. Please try again later.';
  }
}
</script>


<template>
    <div class="container">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" v-model="email" class="form-control" id="email" placeholder="Enter email" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" v-model="password" class="form-control" id="password" placeholder="Password" required />
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
      <p v-if="errorMsg" class="text-danger">{{ errorMsg }}</p>
    </div>
</template>
  