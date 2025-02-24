<template>
    <div class="w-full mx-full px-4 sm:px-6 lg:px-8 py-12 font-sans">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900 border-b border-gray-200 pb-3">The Collection</h2>
            <div class="flex items-center space-x-4">
                <router-link 
                    :to="{ name: 'clothing-items-gallery' }" 
                    class="p-2 text-gray-600 hover:text-black transition-colors"
                    title="View Gallery"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                </router-link>
                <router-link 
                    :to="{ name: 'create-clothing-item' }" 
                    class="px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors duration-300"
                >
                    Add New Piece
                </router-link>
            </div>
        </div>
  
      <!-- Search and Filter -->
      <div class="mb-10 bg-white shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="md:col-span-3">
            <label for="search" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Search</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
              </div>
              <input 
                type="text" 
                id="search" 
                v-model="search" 
                @input="filterItems"
                class="pl-10 block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                placeholder="Search by name, brand, color, or description"
              />
            </div>
          </div>
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
        </div>
      </div>
  
      <!-- Loading indicator -->
      <div v-if="loading" class="py-16 flex justify-center">
        <div class="text-center">
          <div class="inline-block h-8 w-8 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
          <p class="mt-4 text-sm text-black uppercase tracking-wider">Loading</p>
        </div>
      </div>
  
      <!-- Error message -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 mb-6" role="alert">
        <p class="text-sm text-red-700">{{ error }}</p>
      </div>
  
      <!-- No items found -->
      <div v-else-if="clothingItems.length === 0" class="py-16 flex justify-center">
        <div class="text-center">
          <svg class="mx-full h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
          </svg>
          <h3 class="mt-6 text-sm uppercase tracking-wider font-light text-gray-900">No pieces found</h3>
          <p class="mt-2 text-sm text-gray-500 font-light">Begin building your collection by adding your first piece.</p>
          <router-link :to="{ name: 'create-clothing-item' }" class="mt-6 inline-flex px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors duration-300">
            Add First Piece
          </router-link>
        </div>
      </div>
  
      <!-- Items grid layout -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
  <div v-for="item in clothingItems" :key="item.id" class="bg-white shadow overflow-hidden group">
    <!-- Make the entire item card clickable -->
    <router-link :to="{ name: 'single-clothing-item', params: { id: item.id } }" class="block p-6">
      <div class="flex items-center justify-between mb-6">
        <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
          <img 
            v-if="getImageUrl(item.image_path)" 
            :src="getImageUrl(item.image_path)" 
            :alt="item.name" 
            class="h-full w-full object-cover"
          />
          <span v-else class="text-xs uppercase tracking-wider text-gray-400">No image</span>
        </div>
        <div v-if="item.category" class="px-3 py-1 bg-gray-100 text-black text-xs uppercase tracking-wider">
          {{ item.category.name }}
        </div>
      </div>
      
      <h3 class="text-lg font-light tracking-wide text-gray-900 truncate mb-1">{{ item.name }}</h3>
      <p class="text-sm text-gray-500 truncate mb-1">{{ item.brand || 'Unlabeled' }}</p>
      <p class="text-sm text-gray-500 mb-6">{{ item.size }} | {{ item.color }}</p>
    </router-link>
    
    <!-- Keep the action buttons outside the router-link to prevent event propagation issues -->
    <div class="px-6 pb-6">
      <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <router-link :to="{ name: 'edit-clothing-item', params: { id: item.id } }" class="p-2 border border-gray-200 text-gray-600 hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </router-link>
        <button @click="deleteItem(item.id)" class="p-2 border border-transparent bg-black text-white hover:bg-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
  
      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="mt-10 flex justify-center">
        <nav class="flex space-x-1" aria-label="Pagination">
          <a 
            href="#" 
            @click.prevent="loadPage(pagination.current_page - 1)" 
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
            @click.prevent="loadPage(page)"
            :class="[page === pagination.current_page ? 'bg-black text-white border-black' : 'bg-white text-gray-500 border-gray-300 hover:bg-gray-100']"
            class="px-4 py-2 border text-sm"
          >
            {{ page }}
          </a>
          
          <a 
            href="#" 
            @click.prevent="loadPage(pagination.current_page + 1)" 
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
      'Accept': 'application/json', // Very important for Laravel API!
      'X-Requested-With': 'XMLHttpRequest' // This identifies the request as AJAX to Laravel
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
        search: '',
        categoryFilter: '',
        loading: false,
        error: null,
        searchTimeout: null
      };
    },
    created() {
      this.testApiConnection();
      this.loadItems();
      this.loadCategories();
    },
    methods: {
        getImageUrl(imagePath) {
        // Handle cases where imagePath might be null, undefined, or empty
        if (!imagePath) {
            return null;
        }

        // Remove any leading path components and extract filename
        const cleanPath = imagePath.split('/').pop();
        
        // Construct the URL - adjust this based on your actual storage configuration
        return `/storage/images/${cleanPath}`;
        },

        // Example usage in rendering
        renderItemImage(item) {
            const imageUrl = this.getImageUrl(item.image_path);
            return imageUrl 
                ? `<img src="${imageUrl}" alt="${item.name}" />`
                : '<span>No Image</span>';
        },

      async testApiConnection() {
        try {
          const response = await api.get('/api/test');
        } catch (err) {
          console.error('API test failed:', err);
        }
      },
      
      async loadItems(page = 1) {        
        this.loading = true;
        this.error = null;
        
        try {
          // Use absolute path with /api prefix
          let url = '/api/clothing-items';
          
          // Use an object for query parameters to prevent URL formatting issues
          const params = { page };
          
          if (this.search) {
            params.search = this.search;
          }
          
          if (this.categoryFilter) {
            params.category = this.categoryFilter;
          }
          
          // Pass params as separate object
          const response = await api.get(url, { params });
          
          if (response.data && response.data.data) {
            this.clothingItems = response.data.data;

            // Log the fetched data
            console.log('Fetched clothing items:', response.data.data);
            
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
            // Optionally redirect to login
            // this.$router.push({ name: 'login' });
          } else {
            this.error = 'Failed to load clothing items. Please try again later.';
          }
        } finally {
          this.loading = false;
        }
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
      
      filterItems() {
        // Debounce the search to avoid too many API calls
        if (this.searchTimeout) {
          clearTimeout(this.searchTimeout);
        }
        
        this.searchTimeout = setTimeout(() => {
          this.loadItems(1);
        }, 300);
      },
      
      loadPage(page) {
        if (page < 1 || (this.pagination && page > this.pagination.last_page)) {
          return;
        }
        this.loadItems(page);
      },
      
      async deleteItem(id) {
        if (!confirm('Are you sure you want to remove this piece from your collection?')) {
          return;
        }
        
        try {
          // Use _method=DELETE for compatibility with some servers
          const response = await api.delete(`/api/clothing-items/${id}`);
          
          // Refresh the list
          const currentPage = this.pagination?.current_page || 1;
          this.loadItems(currentPage);
        } catch (err) {
          console.error('Failed to delete item:', err);
          console.error('Error details:', err.response ? err.response.data : 'No response data');
          
          if (err.response && err.response.status === 405) {
            alert('Delete operation not supported by the server. API route may be misconfigured.');
          } else if (err.response && err.response.status === 403) {
            alert('You do not have permission to delete this item.');
          } else {
            alert('Failed to delete the item. Please try again.');
          }
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
