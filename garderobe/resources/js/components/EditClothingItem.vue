<template>
  <div>
    <!-- Loading indicator -->
    <div v-if="loading" class="flex justify-center items-center h-96">
      <div class="text-center">
        <div class="inline-block h-12 w-12 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
        <p class="mt-6 text-sm text-black uppercase tracking-wider font-light">Loading Item Details</p>
      </div>
    </div>
  
    <!-- Error message -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-8 rounded-lg shadow-sm transition-all duration-300 animate-fadeIn" role="alert">
      <p class="text-sm text-red-700 mb-4">{{ error }}</p>
      <router-link 
        :to="{ name: 'clothing-items' }" 
        class="inline-block px-6 py-3 bg-black text-white text-sm uppercase tracking-wider hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
      >
        Back to Collection
      </router-link>
    </div>
  
    <!-- Form -->
    <div v-else class="bg-white shadow-lg rounded-lg overflow-hidden animate-fadeIn">
      <div class="px-8 py-6 border-b border-gray-100">
        <h2 class="text-2xl font-serif font-light tracking-wider text-gray-900">Edit Item</h2>
      </div>
      <div class="px-8 py-6">
        <form @submit.prevent="updateItem" enctype="multipart/form-data">
          <!-- Error display -->
          <div v-if="formError" class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 transform transition-all duration-300 ease-in-out" role="alert">
            <p class="text-sm text-red-700 whitespace-pre-line">{{ formError }}</p>
          </div>
  
          <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
            <!-- Name -->
            <div class="sm:col-span-3">
              <label for="name" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Name <span class="text-red-500">*</span></label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                required
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none"
                placeholder="Item name"
              />
            </div>
  
            <!-- Category -->
            <div class="sm:col-span-3">
              <label for="category" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Category <span class="text-red-500">*</span></label>
              <div class="relative">
                <select
                  id="category"
                  v-model="form.category_id"
                  required
                  class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none appearance-none"
                >
                  <option value="" disabled>Select a category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
            </div>
  
            <!-- Color -->
            <div class="sm:col-span-3">
              <label for="color" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Color <span class="text-red-500">*</span></label>
              <div class="flex">
                <input
                  type="text"
                  id="color"
                  v-model="form.color"
                  required
                  class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none"
                  placeholder="e.g. Navy Blue"
                />
                <div class="ml-2 w-10 h-10 border-2 border-gray-200 rounded-md overflow-hidden" :style="{ backgroundColor: form.color }"></div>
              </div>
            </div>
  
            <!-- Size -->
            <div class="sm:col-span-3">
              <label for="size" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Size <span class="text-red-500">*</span></label>
              <input
                type="text"
                id="size"
                v-model="form.size"
                required
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none"
                placeholder="e.g. Medium, 32, UK 10"
              />
            </div>
  
            <!-- Brand -->
            <div class="sm:col-span-3">
              <label for="brand" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Brand</label>
              <input
                type="text"
                id="brand"
                v-model="form.brand"
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none"
                placeholder="Brand name (optional)"
              />
            </div>
  
            <!-- Description -->
            <div class="sm:col-span-6">
              <label for="description" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Description</label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none"
                placeholder="Add notes about condition, fabric, or styling ideas (optional)"
              ></textarea>
            </div>
  
            <!-- Current Image Display -->
            <div v-if="form.image_path" class="sm:col-span-6">
              <label class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Current Image</label>
              <div class="mt-2 relative group">
                <img 
                  :src="getImageUrl(form.image_path)" 
                  class="h-48 w-auto object-cover rounded-md border-2 border-gray-200 transition-all duration-300 group-hover:border-gray-400" 
                  alt="Current item image"
                />
              </div>
            </div>
  
            <!-- Image Upload -->
            <div class="sm:col-span-6">
              <label for="image" class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-2">Update Image</label>
              <div class="mt-2">
                <div class="border-2 border-gray-200 border-dashed rounded-md px-6 py-10 text-center transition-all duration-300 hover:border-gray-400">
                  <div class="space-y-2">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex justify-center text-sm text-gray-600">
                      <label for="image" class="relative cursor-pointer rounded-md font-medium text-black hover:text-gray-700 focus-within:outline-none">
                        <span v-if="form.image">{{ form.image.name }}</span>
                        <span v-else>Click to select a new image</span>
                        <input
                          type="file"
                          id="image"
                          ref="imageInput"
                          @change="handleImageUpload"
                          accept="image/*"
                          class="sr-only"
                        />
                      </label>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <div class="mt-10 flex justify-end gap-4">
            <router-link
              :to="{ name: 'clothing-items' }"
              class="px-6 py-3 border border-black bg-white text-black text-sm tracking-wider uppercase hover:bg-black hover:text-white transition-all duration-300"
            >
              Cancel
            </router-link>
            <button
              type="submit"
              class="px-6 py-3 border border-transparent bg-black text-sm tracking-wider uppercase text-white hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
              :disabled="saving"
            >
              <span v-if="saving" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
              </span>
              <span v-else>Update Item</span>
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
        image_path: null,
        image: null
      },
      categories: [],
      loading: true,
      saving: false,
      error: null,
      formError: null
    };
  },
  created() {
    this.loadCategories();
    this.loadItem();
    
    // Emit event to update breadcrumbs in parent component
    this.$emit('update-breadcrumb', [
      { name: 'Home', path: '/' },
      { name: 'Collection', path: '/clothing-items' },
      { name: 'Edit Item', path: null }
    ]);
  },
  methods: {
    getImageUrl(imagePath) {
      // Handle cases where imagePath might be null, undefined, or empty
      if (!imagePath) {
        return null;
      }

      // If path already includes /storage/, use as is
      if (imagePath.startsWith('/storage/')) {
        return imagePath;
      }

      // Remove any leading path components and extract filename
      const cleanPath = imagePath.split('/').pop();
      
      // Construct the URL - adjust this based on your actual storage configuration
      return `/storage/images/${cleanPath}`;
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
    
    async loadItem() {
      try {
        const id = this.$route.params.id;
        
        // First attempt with GET request
        const response = await api.get(`/api/clothing-items/${id}`);
        
        if (response.data) {
          // Convert category_id to a string to match select input
          if (response.data.category_id) {
            response.data.category_id = response.data.category_id.toString();
          }
          
          // Update breadcrumbs with item name
          this.$emit('update-breadcrumb', [
            { name: 'Home', path: '/' },
            { name: 'Collection', path: '/clothing-items' },
            { name: response.data.name, path: `/clothing-items/${id}` },
            { name: 'Edit', path: null }
          ]);
          
          // Assign all properties to the form
          Object.keys(this.form).forEach(key => {
            if (key !== 'image' && response.data[key] !== undefined) {
              this.form[key] = response.data[key];
            }
          });
        } else {
          throw new Error('Invalid response data structure');
        }
      } catch (err) {
        this.error = 'Failed to load item. It may have been deleted or you do not have permission to edit it.';
      } finally {
        this.loading = false;
      }
    },
    
    handleImageUpload(event) {
      this.form.image = event.target.files[0] || null;
      
      // Preview the image if selected
      if (this.form.image) {
        const reader = new FileReader();
        reader.onload = (e) => {
          // You could set a preview image src here if needed
        };
        reader.readAsDataURL(this.form.image);
      }
    },
    
    async updateItem() {
      this.saving = true;
      this.formError = null;
      
      try {
        const id = this.$route.params.id;
        
        // Create FormData for file upload
        const formData = new FormData();
        
        // Use the PUT method by appending _method field for Laravel
        formData.append('_method', 'PUT');
        
        // Add all form fields to FormData
        formData.append('name', this.form.name);
        formData.append('category_id', this.form.category_id);
        formData.append('color', this.form.color);
        formData.append('size', this.form.size);
        
        // Optional fields - only append if they have values
        if (this.form.brand) {
          formData.append('brand', this.form.brand);
        }
        
        if (this.form.description) {
          formData.append('description', this.form.description);
        }
        
        // Only append the image if a new one is selected
        if (this.form.image) {
          formData.append('image', this.form.image);
        }
        
        // Make the request with API prefix
        await api.post(`/api/clothing-items/${id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        // Redirect to clothing items list on success
        this.$router.push({ 
          name: 'view-clothing-item', 
          params: { 
            id: id,
            notification: 'Item successfully updated.'
          } 
        });
      } catch (err) {
        if (err.response?.data?.errors) {
          // Format validation errors
          const errorMessages = Object.values(err.response.data.errors).flat();
          this.formError = errorMessages.join('\n');
        } else if (err.response?.status === 405) {
          this.formError = 'The update method is not supported. This typically means the API route is not configured correctly.';
        } else {
          this.formError = err.response?.data?.message || 'Failed to update item. Please try again.';
        }
      } finally {
        this.saving = false;
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

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
  animation: fadeIn 0.4s ease-out forwards;
}

/* Input focus animation */
input:focus, select:focus, textarea:focus {
  box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
}

/* Input hover effect */
input, select, textarea {
  transition: border-color 0.3s ease;
}

input:hover:not(:focus), select:hover:not(:focus), textarea:hover:not(:focus) {
  border-color: #9ca3af;
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
</style>
