<template>
    <div class="max-w-6xl mx-full px-4 sm:px-6 lg:px-8 py-12 font-sans">
      <div class="mb-10">
        <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900 border-b border-gray-200 pb-3 inline-block">New Piece</h2>
        <div class="mt-4">
          <router-link :to="{ name: 'clothing-items' }" class="text-sm tracking-wide uppercase transition-colors duration-300 hover:text-amber-600 inline-block border-b border-transparent hover:border-amber-600 pb-0.5">
            ← Return to Collection
          </router-link>
        </div>
      </div>
  
      <!-- Form -->
      <div class="bg-white shadow">
        <div class="p-8">
          <form @submit.prevent="saveItem" enctype="multipart/form-data">
            <!-- Error display -->
            <div v-if="error" class="mb-8 bg-red-50 border-l-4 border-red-400 p-4" role="alert">
              <p class="text-sm text-red-700">{{ error }}</p>
            </div>
  
            <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
              <!-- Name -->
              <div>
                <label for="name" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Name *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    required
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                    placeholder="Oversized Cotton Shirt"
                  />
                </div>
              </div>
  
              <!-- Category -->
              <div>
                <label for="category" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Category *</label>
                <div class="mt-1">
                  <select
                    id="category"
                    v-model="form.category_id"
                    required
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                  >
                    <option value="" disabled>Select category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
  
              <!-- Color -->
              <div>
                <label for="color" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Color *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="color"
                    v-model="form.color"
                    required
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                    placeholder="Black"
                  />
                </div>
              </div>
  
              <!-- Size -->
              <div>
                <label for="size" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Size *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="size"
                    v-model="form.size"
                    required
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                    placeholder="Medium"
                  />
                </div>
              </div>
  
              <!-- Brand -->
              <div>
                <label for="brand" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Brand</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="brand"
                    v-model="form.brand"
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                    placeholder="COS"
                  />
                </div>
              </div>
  
              <!-- Image Upload -->
              <div>
                <label for="image" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Image</label>
                <div class="mt-1">
                  <input
                    type="file"
                    id="image"
                    ref="imageInput"
                    @change="handleImageUpload"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-xs file:font-light file:bg-black file:text-white hover:file:bg-gray-800"
                  />
                </div>
              </div>
  
              <!-- Description -->
              <div class="sm:col-span-2">
                <label for="description" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Description</label>
                <div class="mt-1">
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="block w-full border-gray-300 focus:ring-0 focus:border-black text-sm"
                    placeholder="Details about material, condition, and other notes"
                  ></textarea>
                </div>
              </div>
            </div>
  
            <div class="mt-10 flex justify-end space-x-4">
              <router-link
                :to="{ name: 'clothing-items' }"
                class="px-6 py-2 border border-gray-300 bg-white text-sm tracking-wide uppercase text-gray-700 hover:bg-gray-50 transition-colors duration-300"
              >
                Cancel
              </router-link>
              <button
                type="submit"
                class="px-6 py-2 border border-transparent bg-black text-sm tracking-wide uppercase text-white hover:bg-gray-800 transition-colors duration-300"
                :disabled="loading"
              >
                <span v-if="loading">Saving...</span>
                <span v-else>Save</span>
              </button>
            </div>
          </form>
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
      'Accept': 'application/json', // Important for Laravel API
      'X-Requested-With': 'XMLHttpRequest' // Identifies as AJAX request
    }
  });
  
  // Add token to requests if available
  if (localStorage.getItem('token')) {
    api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
  }
  
  export default {
    data() {
      return {
        form: {
          name: '',
          category_id: '',
          color: '',
          size: '',
          brand: '',
          description: '',
          image: null
        },
        categories: [],
        loading: false,
        error: null
      };
    },
    created() {
      this.loadCategories();
      
      // If categories API fails, provide default categories
      if (this.categories.length === 0) {
        this.setupDefaultCategories();
      }
    },
    methods: {
      async loadCategories() {
        try {
          const response = await api.get('/api/categories');
          
          if (response.data && Array.isArray(response.data)) {
            this.categories = response.data;
          } else {
            this.setupDefaultCategories();
          }
        } catch (err) {
          console.error('Failed to load categories:', err);
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
      
      handleImageUpload(event) {
        this.form.image = event.target.files[0] || null;
      },
      
      async saveItem() {
        this.loading = true;
        this.error = null;
        
        try {
          // Create FormData for file upload
          const formData = new FormData();
          
          // Add all form fields to FormData
          Object.keys(this.form).forEach(key => {
            if (this.form[key] !== null && this.form[key] !== '') {
              formData.append(key, this.form[key]);
            }
          });
          
          // Make the request with proper headers
          const response = await api.post('/api/clothing-items', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          
          // Redirect to clothing items list on success
          this.$router.push({ name: 'clothing-items' });
        } catch (err) {
          console.error('Error saving item:', err);
          console.error('Response:', err.response);
          
          if (err.response?.data?.errors) {
            // Format validation errors
            const errorMessages = Object.values(err.response.data.errors).flat();
            this.error = errorMessages.join('\n');
          } else if (err.response?.status === 405) {
            this.error = 'POST method not supported. This typically means the API route is not configured correctly.';
          } else {
            this.error = err.response?.data?.message || 'Failed to save item. Please try again.';
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
  </style>
