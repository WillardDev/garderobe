<template>
    <button 
      @click="logout" 
      class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-black transition-colors duration-300 focus:outline-none group"
      title="Logout"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-black transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      <span class="hidden md:inline">Logout</span>
    </button>
</template>
  
<script>
    export default {
        methods: {
        logout() {
            // Clear the authentication token
            localStorage.removeItem('token');
            
            // Redirect to login page
            this.$router.push({ name: 'home' });
        }
        }
    };
</script>
