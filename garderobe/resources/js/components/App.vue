<template>
    <div class="min-h-screen bg-gray-100">
      <nav v-if="isLoggedIn" class="bg-white border-b border-gray-200">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="flex-shrink-0 flex items-center">
                <h1 class="text-xl font-bold">Garderobe</h1>
              </div>
              <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <router-link 
                to="/" 
                class="border-transparent text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                >
                Home
                </router-link>
                <router-link 
                  to="/dashboard" 
                  class="border-transparent text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                >
                  Dashboard
                </router-link>
                <router-link 
                  to="/clothing-items" 
                  class="border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                >
                  Clothing Items
                </router-link>
                <router-link 
                    :to="{ name: 'clothing-items-gallery' }" 
                    class="border-transparent text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                    title="View Gallery"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </router-link>
              </div>
            </div>
            <div class="flex items-center">
              <LogoutButton />
            </div>
          </div>
        </div>
      </nav>
  
      <main class="py-10">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </template>
  
  <script>
  import LogoutButton from '../components/Auth/Logout.vue';
  export default {
    components: {
        LogoutButton
    },
    data() {
      return {
        isLoggedIn: false,
      };
    },
    created() {
      this.checkLoginStatus();
    },
    methods: {
      checkLoginStatus() {
        this.isLoggedIn = !!localStorage.getItem('token');
      },
      logout() {
        localStorage.removeItem('token');
        this.isLoggedIn = false;
        this.$router.push({ name: 'login' });
      }
    },
    watch: {
      $route() {
        this.checkLoginStatus();
      }
    }
  };
  </script>
