<template>
    <button 
      @click="logout" 
      class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
    >
      Logout
    </button>
  </template>
  
  <script>
  export default {
    methods: {
      logout() {
        // Clear the authentication token
        localStorage.removeItem('token');
        
        // Redirect to login page
        this.$router.push({ name: 'login' });
      }
    }
  };
  </script>
