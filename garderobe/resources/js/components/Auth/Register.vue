<template>
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div>
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Create a new account</h2>
          <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <router-link :to="{ name: 'login' }" class="font-medium text-indigo-600 hover:text-indigo-500">
              sign in to your existing account
            </router-link>
          </p>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="register">
          <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ error }}</span>
          </div>
          <div class="rounded-md shadow-sm -space-y-px">
            <div>
              <label for="name" class="sr-only">Name</label>
              <input 
                id="name" 
                name="name" 
                type="text" 
                required 
                v-model="form.name"
                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                placeholder="Full name" 
              />
            </div>
            <div>
              <label for="email-address" class="sr-only">Email address</label>
              <input 
                id="email-address" 
                name="email" 
                type="email" 
                autocomplete="email" 
                required 
                v-model="form.email"
                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                placeholder="Email address" 
              />
            </div>
            <div>
              <label for="password" class="sr-only">Password</label>
              <input 
                id="password" 
                name="password" 
                type="password" 
                autocomplete="new-password" 
                required 
                v-model="form.password"
                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                placeholder="Password (min 8 characters)" 
              />
            </div>
          </div>
  
          <div>
            <button 
              type="submit"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :disabled="loading"
            >
              <span v-if="loading">Creating account...</span>
              <span v-else>Create account</span>
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
          name: '',
          email: '',
          password: ''
        },
        error: null,
        loading: false
      };
    },
    methods: {
      async register() {
        this.loading = true;
        this.error = null;
        
        try {
          const response = await axios.post('/api/register', this.form);
          localStorage.setItem('token', response.data.access_token);
          this.$router.push({ name: 'dashboard' });
        } catch (err) {
          if (err.response?.data?.errors) {
            // Format validation errors
            const errorMessages = Object.values(err.response.data.errors).flat();
            this.error = errorMessages.join('\n');
          } else {
            this.error = err.response?.data?.message || 'Failed to create account.';
          }
        } finally {
          this.loading = false;
        }
      }
    }
  };
  </script>
