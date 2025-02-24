<template>
    <div>
      <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Edit Clothing Item</h2>
        <router-link :to="{ name: 'clothing-items' }" class="text-indigo-600 hover:text-indigo-900">
          &larr; Back to items
        </router-link>
      </div>
  
      <!-- Loading indicator -->
      <div v-if="loading" class="py-12 flex justify-center">
        <div class="text-center">
          <div class="text-indigo-600">Loading...</div>
        </div>
      </div>
  
      <!-- Error message -->
      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ error }}</span>
      </div>
  
      <!-- Form -->
      <div v-else class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <form @submit.prevent="updateItem" enctype="multipart/form-data">
            <!-- Error display -->
            <div v-if="formError" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <span class="block sm:inline">{{ formError }}</span>
            </div>
  
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
              <!-- Name -->
              <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    required
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
              </div>
  
              <!-- Category -->
              <div class="sm:col-span-3">
                <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                <div class="mt-1">
                  <select
                    id="category"
                    v-model="form.category_id"
                    required
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option value="" disabled>Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
  
              <!-- Color -->
              <div class="sm:col-span-3">
                <label for="color" class="block text-sm font-medium text-gray-700">Color *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="color"
                    v-model="form.color"
                    required
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
              </div>
  
              <!-- Size -->
              <div class="sm:col-span-3">
                <label for="size" class="block text-sm font-medium text-gray-700">Size *</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="size"
                    v-model="form.size"
                    required
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
              </div>
  
              <!-- Brand -->
              <div class="sm:col-span-3">
                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <div class="mt-1">
                  <input
                    type="text"
                    id="brand"
                    v-model="form.brand"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
              </div>
  
              <!-- Description -->
              <div class="sm:col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1">
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="3"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  ></textarea>
                </div>
              </div>
  
              <!-- Current Image Display -->
              <div v-if="form.image_path" class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Current Image</label>
                <div class="mt-1">
                  <img :src="'/storage/' + form.image_path" class="h-32 w-auto object-cover rounded-md" />
                </div>
              </div>
  
              <!-- Image Upload -->
              <div class="sm:col-span-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Update Image</label>
                <div class="mt-1 flex items-center">
                  <input
                    type="file"
                    id="image"
                    ref="imageInput"
                    @change="handleImageUpload"
                    accept="image/*"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  />
                </div>
              </div>
            </div>
  
            <div class="mt-6 flex justify-end">
              <router-link
                :to="{ name: 'clothing-items' }"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Cancel
              </router-link>
              <button
                type="submit"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :disabled="saving"
              >
                <span v-if="saving">Saving...</span>
                <span v-else>Update</span>
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
          this.$router.push({ name: 'clothing-items' });
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
  