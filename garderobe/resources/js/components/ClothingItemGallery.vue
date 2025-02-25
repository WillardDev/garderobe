<template>
    <div class="w-full mx-full px-4 sm:px-6 lg:px-8 py-12 font-sans">
      <div class="flex justify-between items-center mb-10">
        <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900 border-b border-gray-200 pb-3">
          My Wardrobe Gallery
        </h2>
        <router-link 
          :to="{ name: 'create-clothing-item' }" 
          class="px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors duration-300"
        >
          Add New Piece
        </router-link>
      </div>
  
      <!-- Filtering Options -->
      <div class="mb-10 bg-white shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label for="category" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Category</label>
            <select 
              id="category" 
              v-model="categoryFilter" 
              @change="filterItems"
              class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label for="search" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Search</label>
            <input 
              type="text" 
              id="search" 
              v-model="search" 
              @input="filterItems"
              class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
              placeholder="Search  ..."
            />
          </div>
        </div>
      </div>
  
      <!-- Loading State -->
      <div v-if="loading" class="py-16 flex justify-center">
        <div class="text-center">
          <div class="inline-block h-8 w-8 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
          <p class="mt-4 text-sm text-black uppercase tracking-wider">Loading Gallery</p>
        </div>
      </div>
  
      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4" role="alert">
        <p class="text-sm text-red-700">{{ error }}</p>
      </div>
  
      <!-- No Items State -->
      <div v-else-if="clothingItems.length === 0" class="py-16 flex justify-center">
        <div class="text-center">
          <svg class="mx-full38756 h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-6 text-sm uppercase tracking-wider font-light text-gray-900">No Images Found</h3>
          <p class="mt-2 text-sm text-gray-500 font-light">Begin building your collection by adding your first piece.</p>
          <router-link 
            :to="{ name: 'create-clothing-item' }" 
            class="mt-6 inline-flex px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors"
          >
            Add First Piece
          </router-link>
        </div>
      </div>
  
      <!-- Grid View -->
      <div v-else-if="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
                v-for="item in clothingItems" 
                :key="item.id" 
                class="relative group overflow-hidden"
            >
                <router-link :to="{ name: 'single-clothing-item', params: { id: item.id } }">
                <div class="bg-gray-100 overflow-hidden">
                    <img 
                    v-if="getImageUrl(item.image_path)" 
                    :src="getImageUrl(item.image_path)" 
                    :alt="item.name" 
                    class="w-full object-cover group-hover:scale-110 transition-transform duration-300"
                    />
                    <div v-else class="flex items-center justify-center text-gray-400 h-48">
                    No Image
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <h3 class="text-sm truncate">{{ item.name }}</h3>
                    <p class="text-xs">{{ item.category ? item.category.name : 'Uncategorized' }}</p>
                </div>
                </router-link>
            </div>
        </div>
  
      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="mt-10 flex justify-center">
        <nav class="flex space-x-1" aria-label="Pagination">
          <a 
            href="#" 
            @click.prevent="loadItems(pagination.current_page - 1)" 
            :class="[pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100']"
            class="px-3 py-2 border border-gray-300 bg-white text-sm text-gray-500"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </a>
          
          <a 
            v-for="page in pagination.last_page" 
            :key="page" 
            href="#" 
            @click.prevent="loadItems(page)"
            :class="[page === pagination.current_page ? 'bg-black text-white border-black' : 'bg-white text-gray-500 border-gray-300 hover:bg-gray-100']"
            class="px-4 py-2 border text-sm"
          >
            {{ page }}
          </a>
          
          <a 
            href="#" 
            @click.prevent="loadItems(pagination.current_page + 1)" 
            :class="[pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100']"
            class="px-3 py-2 border border-gray-300 bg-white text-sm text-gray-500"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </a>
        </nav>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  // Create a custom axios instance just for this component
  const api = axios.create({
    baseURL: '', // Empty base URL to use absolute paths
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  });
  
  // Add token to requests if available
  if (localStorage.getItem('token')) {
    api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
  }
  
  export default {
    data() {
      return {
        clothingItems: [],
        categories: [],
        pagination: null,
        loading: false,
        error: null,
        search: '',
        categoryFilter: '',
        viewMode: 'grid', // Default view mode
        searchTimeout: null
      };
    },
    created() {
      this.loadCategories();
      this.loadItems();
    },
    methods: {
        getImageUrl(imagePath) {
            // Handle cases where imagePath might be null, undefined, or empty
            if (!imagePath) {
                return null;
            }

            // Return the full path directly
            return `/storage/${imagePath}`;
        },
      
      async loadCategories() {
        try {
          const response = await api.get('/api/categories');
          if (response.data && Array.isArray(response.data)) {
            this.categories = response.data;
          } else {
            this.setupDefaultCategories();
          }
        } catch (err) {
          this.setupDefaultCategories();
        }
      },
      
      setupDefaultCategories() {
        // Create default categories with auto-incrementing IDs
        this.categories = [
          { id: 1, name: 'Tops' },
          { id: 2, name: 'Bottoms' },
          { id: 3, name: 'Outerwear' },
          { id: 4, name: 'Footwear' },
          { id: 5, name: 'Accessories' },
          { id: 6, name: 'Formal Wear' },
          { id: 7, name: 'Activewear' },
          { id: 8, name: 'Sleepwear' }
        ];
      },
      
      async loadItems(page = 1) {
        this.loading = true;
        this.error = null;
        
        try {
          // Use an object for query parameters to prevent URL formatting issues
          const params = { page };
          
          if (this.search) {
            params.search = this.search;
          }
          
          if (this.categoryFilter) {
            params.category = this.categoryFilter;
          }
          
          // Pass params as separate object
          const response = await api.get('/api/clothing-items', { params });
          
          if (response.data && response.data.data) {
            this.clothingItems = response.data.data;
            
            // Extract pagination info
            this.pagination = {
              current_page: response.data.current_page,
              last_page: response.data.last_page,
              per_page: response.data.per_page,
              total: response.data.total
            };
          } else {
            this.error = 'Received invalid data format from server';
          }
        } catch (err) {
          if (err.response && err.response.status === 401) {
            this.error = 'You need to login to view your clothing items.';
          } else {
            this.error = 'Failed to load clothing items. Please try again later.';
          }
          console.error('Error loading items:', err);
        } finally {
          this.loading = false;
        }
      },
      
      filterItems() {
        // Debounce the search to avoid too many API calls
        if (this.searchTimeout) {
          clearTimeout(this.searchTimeout);
        }
        
        this.searchTimeout = setTimeout(() => {
          this.loadItems(1);
        }, 300);
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

/* Aspect Ratio Utility Classes */
.aspect-w-1 {
  position: relative;
  padding-bottom: 100%;
}

.aspect-h-1 {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.aspect-w-1 > * {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Tailwind-like custom classes for view transitions */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}
</style>
