<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans">
      <div class="w-full max-w-md bg-white shadow p-8 space-y-8">
        <div>
          <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900 border-b border-gray-200 pb-3">Sign In</h2>
          <p class="mt-4 text-sm text-gray-600">
            Or
            <router-link :to="{ name: 'register' }" class="font-medium text-black hover:text-gray-800 transition-colors">
              create a new account
            </router-link>
          </p>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="login">
          <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4" role="alert">
            <p class="text-sm text-red-700">{{ error }}</p>
          </div>
          <div class="space-y-4">
            <div>
              <label for="email-address" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Email address</label>
              <input 
                id="email-address" 
                name="email" 
                type="email" 
                autocomplete="email" 
                required 
                v-model="form.email"
                class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm" 
                placeholder="Your email" 
              />
            </div>
            <div>
              <label for="password" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Password</label>
              <input 
                id="password" 
                name="password" 
                type="password" 
                autocomplete="current-password" 
                required 
                v-model="form.password"
                class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm" 
                placeholder="Your password" 
              />
            </div>
          </div>
  
          <div>
            <button 
              type="submit"
              class="w-full px-6 py-3 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors duration-300"
              :disabled="loading"
            >
              <span v-if="loading">Signing in...</span>
              <span v-else>Sign in</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          email: '',
          password: ''
        },
        error: null,
        loading: false
      };
    },
    created() {
      // Check if user is already logged in
      this.checkAuthStatus();
    },
    methods: {
      checkAuthStatus() {
        const token = localStorage.getItem('token');
        if (token) {
          // User is already logged in, redirect to dashboard
          this.$router.push({ name: 'dashboard' });
        }
      },
      async login() {
        this.loading = true;
        this.error = null;
        
        try {
          const response = await axios.post('/api/login', this.form);
          localStorage.setItem('token', response.data.access_token);
          this.$router.push({ name: 'dashboard' });
        } catch (err) {
          this.error = err.response?.data?.message || 'Failed to login. Please check your credentials.';
        } finally {
          this.loading = false;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Montserrat:wght@200;300;400;500;600;700&display=swap');
  
  .font-serif {
    font-family: 'Cormorant Garamond', serif;
  }
  
  .font-sans {
    font-family: 'Montserrat', sans-serif;
  }
  
  .tracking-wider {
    letter-spacing: 0.1em;
  }
  
  .tracking-widest {
    letter-spacing: 0.2em;
  }
  </style>
