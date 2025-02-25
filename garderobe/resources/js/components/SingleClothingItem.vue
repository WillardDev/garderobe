<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 font-sans">
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center h-96">
      <div class="text-center">
        <div class="inline-block h-12 w-12 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
        <p class="mt-6 text-sm text-black uppercase tracking-wider font-light">Loading Item Details</p>
      </div>
    </div>
  
    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-8 rounded-lg shadow-sm transition-all duration-300 animate-fadeIn" role="alert">
      <p class="text-sm text-red-700 mb-4">{{ error }}</p>
      <router-link 
        :to="{ name: 'clothing-items' }" 
        class="inline-block px-6 py-3 bg-black text-white text-sm uppercase tracking-wider hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
      >
        Back to Collection
      </router-link>
    </div>
  
    <!-- Item Details -->
    <div v-else-if="item" class="animate-fadeIn">

      <div class="grid lg:grid-cols-2 gap-12">
        <!-- Image Section -->
        <div class="relative">
          <div class="bg-gray-50 rounded-lg overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl mb-6 aspect-[3/4] flex items-center justify-center">
            <img 
              v-if="getImageUrl(item.image_path)" 
              :src="getImageUrl(item.image_path)" 
              :alt="item.name" 
              class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
            />
            <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
              <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p>No Image Available</p>
              </div>
            </div>
          </div>
          
          <div v-if="item.category" class="absolute top-4 left-4 inline-block px-4 py-2 bg-white/90 backdrop-blur-sm shadow-sm text-black text-xs uppercase tracking-wider">
            {{ item.category.name }}
          </div>
        </div>
  
        <!-- Details Section -->
        <div class="flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-start mb-8">
              <div>
                <h1 class="text-4xl font-serif font-light tracking-wide text-gray-900 mb-4">{{ item.name }}</h1>
                <p v-if="item.brand" class="text-xl text-gray-600 mb-6">{{ item.brand }}</p>
              </div>
              
              <div class="flex space-x-3">
                <router-link 
                  :to="{ name: 'edit-clothing-item', params: { id: item.id } }" 
                  class="p-3 border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors duration-300 rounded-full group"
                  title="Edit Item"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                
                <button 
                  @click="confirmDelete" 
                  class="p-3 border border-transparent bg-black text-white hover:bg-gray-800 transition-all duration-300 rounded-full group"
                  title="Delete Item"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
  
            <div class="grid sm:grid-cols-2 gap-y-8 gap-x-10 mt-6 border-t border-b border-gray-100 py-8">
              <div class="transition-all duration-300 hover:translate-x-1">
                <span class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Size</span>
                <p class="text-lg font-light">{{ item.size }}</p>
              </div>
              
              <div class="transition-all duration-300 hover:translate-x-1">
                <span class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Color</span>
                <div class="flex items-center space-x-3">
                  <span class="inline-block w-6 h-6 rounded-full border border-gray-200" :style="{ backgroundColor: item.color }"></span>
                  <p class="text-lg font-light">{{ item.color }}</p>
                </div>
              </div>
            </div>
            
            <div v-if="item.description" class="py-8">
              <span class="block text-xs uppercase tracking-wider text-gray-500 mb-3">Description</span>
              <p class="text-gray-600 leading-relaxed">{{ item.description }}</p>
            </div>
          </div>
  
          <div class="mt-12 flex flex-wrap gap-4">
            <router-link 
              :to="{ name: 'clothing-items' }" 
              class="inline-block px-8 py-3 border border-black bg-white text-black text-sm tracking-wider uppercase hover:bg-black hover:text-white transition-all duration-300"
            >
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Collection
              </span>
            </router-link>
          </div>
        </div>
      </div>
      
      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all duration-300 animate-fadeIn">
          <h3 class="text-xl font-light mb-4">Remove from Collection</h3>
          <p class="text-gray-600 mb-6">Are you sure you want to remove <span class="font-medium">{{ item.name }}</span> from your collection? This action cannot be undone.</p>
          <div class="flex justify-end space-x-4">
            <button 
              @click="showDeleteModal = false" 
              class="px-4 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button 
              @click="deleteItem" 
              class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition-colors"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
      
      <!-- Share Modal -->
      <div v-if="showShareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all duration-300 animate-fadeIn">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-light">Share This Item</h3>
            <button @click="showShareModal = false" class="text-gray-400 hover:text-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="space-y-4 mb-6">
            <button class="w-full flex items-center justify-center p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
              <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
              </svg>
              Share on Facebook
            </button>
            <button class="w-full flex items-center justify-center p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
              <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
              </svg>
              Share on Twitter
            </button>
            <div>
              <label class="block text-xs uppercase tracking-wider text-gray-500 mb-2">Copy Link</label>
              <div class="flex">
                <input 
                  type="text" 
                  readonly 
                  :value="shareLink"
                  class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm rounded-l" 
                />
                <button 
                  @click="copyToClipboard" 
                  class="px-4 py-2 bg-black text-white text-sm rounded-r"
                >
                  Copy
                </button>
              </div>
              <p v-if="copied" class="text-green-600 text-sm mt-2">Copied to clipboard!</p>
            </div>
          </div>
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
      error: null,
      showDeleteModal: false,
      showShareModal: false,
      copied: false,
      shareLink: ''
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
        // Generate share link based on current URL
        this.shareLink = window.location.href;
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
    
    confirmDelete() {
      this.showDeleteModal = true;
    },
    
    async deleteItem() {
      try {
        await api.delete(`/api/clothing-items/${this.id}`);
        // Redirect back to clothing items list after successful deletion
        this.$router.push({ 
          name: 'clothing-items', 
          params: { 
            notification: 'Item successfully removed from your collection.' 
          } 
        });
      } catch (err) {
        console.error('Failed to delete item:', err);
        
        this.showDeleteModal = false;
        
        if (err.response && err.response.status === 405) {
          alert('Delete operation not supported by the server. API route may be misconfigured.');
        } else if (err.response && err.response.status === 403) {
          alert('You do not have permission to delete this item.');
        } else {
          alert('Failed to delete the item. Please try again.');
        }
      }
    },
    
    shareItem() {
      this.showShareModal = true;
    },
    
    copyToClipboard() {
      navigator.clipboard.writeText(this.shareLink)
        .then(() => {
          this.copied = true;
          setTimeout(() => {
            this.copied = false;
          }, 3000);
        })
        .catch(err => {
          console.error('Failed to copy text: ', err);
        });
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

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
  animation: fadeIn 0.4s ease-out forwards;
}
</style>
