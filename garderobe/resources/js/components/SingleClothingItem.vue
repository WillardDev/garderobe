<template>
    <div class="max-w-4xl mx-full px-4 sm:px-6 lg:px-8 py-12 font-sans">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="text-center">
          <div class="inline-block h-8 w-8 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
          <p class="mt-4 text-sm text-black uppercase tracking-wider">Loading Item Details</p>
        </div>
      </div>
  
      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4" role="alert">
        <p class="text-sm text-red-700">{{ error }}</p>
        <router-link 
          :to="{ name: 'clothing-items' }" 
          class="mt-4 inline-block px-4 py-2 bg-black text-white text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors"
        >
          Back to Collection
        </router-link>
      </div>
  
      <!-- Item Details -->
      <div v-else-if="item" class="grid md:grid-cols-2 gap-10">
        <!-- Image Section -->
        <div>
          <div class="bg-gray-100 rounded-lg overflow-hidden mb-6">
            <img 
              v-if="getImageUrl(item.image_path)" 
              :src="getImageUrl(item.image_path)" 
              :alt="item.name" 
              class="w-full h-96 object-cover"
            />
            <div v-else class="h-96 flex items-center justify-center text-gray-400">
              No Image Available
            </div>
          </div>
        </div>
  
        <!-- Details Section -->
        <div>
          <div class="flex justify-between items-start mb-6">
            <div>
              <h1 class="text-3xl font-light tracking-wide text-gray-900 mb-2">{{ item.name }}</h1>
              <div v-if="item.category" class="inline-block px-3 py-1 bg-gray-100 text-black text-xs uppercase tracking-wider mb-4">
                {{ item.category.name }}
              </div>
            </div>
            
            <div class="flex space-x-2">
              <router-link 
                :to="{ name: 'edit-clothing-item', params: { id: item.id } }" 
                class="p-2 border border-gray-200 text-gray-600 hover:bg-gray-50"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </router-link>
              
              <button 
                @click="deleteItem" 
                class="p-2 border border-transparent bg-black text-white hover:bg-gray-800"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
  
          <div class="space-y-4 text-gray-600">
            <div>
              <span class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Brand</span>
              <p class="text-sm">{{ item.brand || 'Not Specified' }}</p>
            </div>
            
            <div>
              <span class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Size</span>
              <p class="text-sm">{{ item.size }}</p>
            </div>
            
            <div>
              <span class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Color</span>
              <p class="text-sm">{{ item.color }}</p>
            </div>
            
            <div v-if="item.description">
              <span class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Description</span>
              <p class="text-sm">{{ item.description }}</p>
            </div>
          </div>
  
          <div class="mt-8">
            <router-link 
              :to="{ name: 'clothing-items' }" 
              class="inline-block px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors"
            >
              Back to Collection
            </router-link>
          </div>
        </div>
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
    props: {
      id: {
        type: [String, Number],
        required: true
      }
    },
    data() {
      return {
        item: null,
        loading: true,
        error: null
      };
    },
    created() {
      this.fetchItemDetails();
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
      
      async fetchItemDetails() {
        this.loading = true;
        this.error = null;
        
        try {
          const response = await api.get(`/api/clothing-items/${this.id}`);
          this.item = response.data;
        } catch (err) {
          if (err.response && err.response.status === 404) {
            this.error = 'Item not found. It may have been deleted.';
          } else if (err.response && err.response.status === 403) {
            this.error = 'You do not have permission to view this item.';
          } else {
            this.error = 'Failed to load item details. Please try again later.';
          }
          console.error('Error fetching item details:', err);
        } finally {
          this.loading = false;
        }
      },
      
      async deleteItem() {
        if (!confirm('Are you sure you want to remove this piece from your collection?')) {
          return;
        }
        
        try {
          await api.delete(`/api/clothing-items/${this.id}`);
          // Redirect back to clothing items list after successful deletion
          this.$router.push({ name: 'clothing-items' });
        } catch (err) {
          console.error('Failed to delete item:', err);
          
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
  </style>
