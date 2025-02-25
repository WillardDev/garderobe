<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 font-sans">
      <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8 space-y-8 transform transition duration-300 hover:shadow-xl">
        <div>
          <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900 border-b border-gray-200 pb-3">Create Account</h2>
          <p class="mt-4 text-sm text-gray-600">
            Or
            <router-link :to="{ name: 'login' }" class="font-medium text-black hover:text-gray-800 transition-colors relative group">
              sign in to your existing account
              <span class="absolute left-0 right-0 bottom-0 h-0.5 bg-black transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            </router-link>
          </p>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="register">
          <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 transform transition-all duration-300 ease-in-out" role="alert">
            <p class="text-sm text-red-700 whitespace-pre-line">{{ error }}</p>
          </div>
          <div class="space-y-4">
            <div class="group">
              <label for="name" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2 transition duration-300 group-focus-within:text-black">Full name</label>
              <input 
                id="name" 
                name="name" 
                type="text" 
                required 
                v-model="form.name"
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none" 
                placeholder="Your full name" 
              />
            </div>
            <div class="group">
              <label for="email-address" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2 transition duration-300 group-focus-within:text-black">Email address</label>
              <input 
                id="email-address" 
                name="email" 
                type="email" 
                autocomplete="email" 
                required 
                v-model="form.email"
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none" 
                placeholder="Your email" 
              />
            </div>
            <div class="group">
              <label for="password" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2 transition duration-300 group-focus-within:text-black">Password</label>
              <input 
                id="password" 
                name="password" 
                type="password" 
                autocomplete="new-password" 
                required 
                v-model="form.password"
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none" 
                placeholder="Minimum 8 characters" 
              />
              <div class="mt-1 h-1 bg-gray-200 rounded overflow-hidden">
                <div 
                  class="h-full transition-all duration-300 ease-in-out" 
                  :class="passwordStrengthClass"
                  :style="{ width: passwordStrength + '%' }"
                ></div>
              </div>
            </div>
          </div>
  
          <div>
            <button 
              type="submit"
              class="w-full px-6 py-4 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-all duration-300 rounded-md transform hover:-translate-y-1 hover:shadow-md"
              :disabled="loading"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating account...
              </span>
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
    computed: {
      passwordStrength() {
        const password = this.form.password;
        if (!password) return 0;
        
        let strength = 0;
        
        // Length contribution
        strength += Math.min(password.length * 5, 30);
        
        // Complexity contribution
        if (/[A-Z]/.test(password)) strength += 15;
        if (/[a-z]/.test(password)) strength += 10;
        if (/[0-9]/.test(password)) strength += 15;
        if (/[^A-Za-z0-9]/.test(password)) strength += 20;
        
        return Math.min(strength, 100);
      },
      passwordStrengthClass() {
        const strength = this.passwordStrength;
        if (strength < 30) return 'bg-red-500';
        if (strength < 60) return 'bg-yellow-500';
        return 'bg-green-500';
      }
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
  
  /* Input focus animation */
  input:focus {
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
  }
  
  /* Input hover effect */
  input {
    transition: border-color 0.3s ease;
  }
  
  input:hover:not(:focus) {
    border-color: #9ca3af;
  }
  
  /* Label transitions */
  .group:focus-within label {
    color: black;
    font-weight: 500;
  }
  
  /* Error message animation */
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
  }
  
  div[role="alert"] {
    animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both;
  }
  
  /* Button hover animation */
  button:not(:disabled):hover {
    letter-spacing: 0.12em;
  }
  </style>
