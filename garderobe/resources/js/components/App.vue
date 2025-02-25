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
                :class="{ 'border-black': $route.path === '/' }"
              >
                Home
              </router-link>
              <router-link 
                to="/dashboard" 
                class="border-transparent text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-black': $route.path === '/dashboard' }"
              >
                Dashboard
              </router-link>
              <router-link 
                to="/clothing-items" 
                class="border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-black text-gray-900': $route.path.includes('/clothing-items') }"
              >
                Clothing Items
              </router-link>
              <router-link 
                to="/outfit-matcher" 
                class="border-transparent text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-black text-gray-900': $route.path.includes('/outfit-matcher') }"
              >
                Outfit Matcher
              </router-link>
              <router-link 
                :to="{ name: 'clothing-items-gallery' }" 
                class="border-transparent text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                :class="{ 'border-black': $route.name === 'clothing-items-gallery' }"
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

    <!-- Breadcrumbs navigation -->
    <div v-if="isLoggedIn && breadcrumbs.length > 1" class="bg-white border-b border-gray-200 py-2 shadow-sm">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
            <li v-for="(crumb, index) in breadcrumbs" :key="index" class="inline-flex items-center">
              <div v-if="index > 0" class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 9 4-4-4-4"/>
                </svg>
              </div>
              <router-link 
                v-if="crumb.path && index !== breadcrumbs.length - 1" 
                :to="crumb.path" 
                class="text-gray-500 hover:text-black transition-colors"
              >
                {{ crumb.name }}
              </router-link>
              <span 
                v-else 
                class="text-gray-700 font-medium"
                :class="{ 'truncate max-w-xs': index === breadcrumbs.length - 1 }"
              >
                {{ crumb.name }}
              </span>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <main class="py-10">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <router-view @update-breadcrumb="updateCustomBreadcrumb"></router-view>
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
      customBreadcrumb: null
    };
  },
  computed: {
    breadcrumbs() {
      // Start with Home breadcrumb
      let crumbs = [{ name: 'Home', path: '/' }];
      
      // Get current route path and name
      const path = this.$route.path;
      const name = this.$route.name;
      
      // If custom breadcrumb data is provided by a child component
      if (this.customBreadcrumb) {
        return this.customBreadcrumb;
      }
      
      // Otherwise, generate breadcrumbs based on route
      if (path.includes('/dashboard')) {
        crumbs.push({ name: 'Dashboard', path: '/dashboard' });
      }
      
      if (path.includes('/clothing-items')) {
        crumbs.push({ name: 'Collection', path: '/clothing-items' });
        
        // Specific clothing item pages
        if (path.includes('/clothing-items/') && this.$route.params.id) {
          if (path.includes('/edit')) {
            crumbs.push({ 
              name: `Item #${this.$route.params.id}`, 
              path: `/clothing-items/${this.$route.params.id}` 
            });
            crumbs.push({ name: 'Edit', path: null });
          } else {
            // For item detail page
            crumbs.push({ 
              name: `Item #${this.$route.params.id}`, 
              path: null 
            });
          }
        }
        
        // Add item page
        if (path.includes('/add-clothing-item')) {
          crumbs.push({ name: 'Add New Item', path: null });
        }
        
        // Gallery view
        if (name === 'clothing-items-gallery') {
          crumbs = [
            { name: 'Home', path: '/' },
            { name: 'Collection', path: '/clothing-items' },
            { name: 'Gallery View', path: null }
          ];
        }
      }
      
      return crumbs;
    }
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
    },
    updateCustomBreadcrumb(breadcrumbData) {
      // Allow child components to update breadcrumb data
      this.customBreadcrumb = breadcrumbData;
    }
  },
  watch: {
    $route() {
      this.checkLoginStatus();
      this.customBreadcrumb = null; // Reset custom breadcrumb when route changes
    }
  }
};
</script>

<style scoped>
/* Add a smooth transition for the active border effect */
a {
  transition: all 0.2s ease-in-out;
}

.border-black {
  border-color: #000;
  border-bottom-width: 2px;
}

/* Make breadcrumb area a bit more elegant */
[aria-label="Breadcrumb"] {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Improve truncation for long names */
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: inline-block;
}
</style>
