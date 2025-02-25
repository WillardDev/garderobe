<template>
    <div>
      <!-- Header -->
      <div class="py-6 border-b border-gray-200 mb-10">
        <div class="w-full mx-full px-4">
          <div class="flex justify-between items-center">
            <div class="font-serif text-3xl tracking-widest font-light">
              WARDROBE<span class="text-amber-600">.</span>NYC
            </div>
            <div>
              <!-- User menu could go here -->
            </div>
          </div>
        </div>
      </div>
  
      <div class="w-full mx-full px-4">
        <!-- Dashboard Title -->
        <h1 class="text-4xl font-serif font-light tracking-wider border-b border-gray-200 pb-3 mb-8">
          My Collection
        </h1>
        
        <!-- Loading state -->
        <div v-if="loading" class="py-12 flex justify-center">
          <div class="text-center">
            <div class="text-black">Loading your wardrobe data...</div>
          </div>
        </div>
        
        <!-- Error state -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
          <span class="block sm:inline">{{ error }}</span>
        </div>
        
        <div v-else>
          <!-- Stats overview cards -->
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
            <!-- Total items card -->
            <div class="bg-white overflow-hidden shadow rounded group transition-all duration-300 hover:-translate-y-1 hover:shadow-md relative">
              <div class="absolute bottom-0 left-0 right-0 h-1 bg-black transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
              <div class="px-6 py-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-black text-white w-12 h-12 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                  </div>
                  <div class="ml-5 flex-1">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                      Total Pieces
                    </div>
                    <div class="text-3xl font-serif font-normal">
                      {{ stats.totalItems }}
                    </div>
                  </div>
                </div>
                <div class="mt-4 uppercase text-xs tracking-wide inline-block border-b border-gray-200 pb-0.5 cursor-pointer transition-colors duration-300 hover:text-amber-600 hover:border-amber-600">
                  <router-link :to="{ name: 'clothing-items' }">
                    View Collection
                  </router-link>
                </div>
              </div>
            </div>
            
            <!-- Categories card -->
            <div class="bg-white overflow-hidden shadow rounded group transition-all duration-300 hover:-translate-y-1 hover:shadow-md relative">
              <div class="absolute bottom-0 left-0 right-0 h-1 bg-black transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
              <div class="px-6 py-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-black text-white w-12 h-12 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                  </div>
                  <div class="ml-5 flex-1">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                      Categories
                    </div>
                    <div class="text-3xl font-serif font-normal">
                      {{ stats.categoriesUsed }}
                    </div>
                  </div>
                </div>
                <div class="mt-4 uppercase text-xs tracking-wide inline-block border-b border-gray-200 pb-0.5 cursor-pointer transition-colors duration-300 hover:text-amber-600 hover:border-amber-600">
                  <button @click="showCategoryModal = true">
                    Category Details
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Recent additions card -->
            <div class="bg-white overflow-hidden shadow rounded group transition-all duration-300 hover:-translate-y-1 hover:shadow-md relative">
              <div class="absolute bottom-0 left-0 right-0 h-1 bg-black transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
              <div class="px-6 py-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-black text-white w-12 h-12 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                  </div>
                  <div class="ml-5 flex-1">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                      New This Month
                    </div>
                    <div class="text-3xl font-serif font-normal">
                      {{ stats.recentAdditions }}
                    </div>
                  </div>
                </div>
                <div class="mt-4 uppercase text-xs tracking-wide inline-block border-b border-gray-200 pb-0.5 cursor-pointer transition-colors duration-300 hover:text-amber-600 hover:border-amber-600">
                  <router-link :to="{ name: 'create-clothing-item' }">
                    Add New Item
                  </router-link>
                </div>
              </div>
            </div>
            
            <!-- Colors card -->
            <div class="bg-white overflow-hidden shadow rounded group transition-all duration-300 hover:-translate-y-1 hover:shadow-md relative">
              <div class="absolute bottom-0 left-0 right-0 h-1 bg-black transform scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
              <div class="px-6 py-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-black text-white w-12 h-12 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                  </div>
                  <div class="ml-5 flex-1">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                      Unique Colors
                    </div>
                    <div class="text-3xl font-serif font-normal">
                      {{ stats.uniqueColors }}
                    </div>
                  </div>
                </div>
                <div class="mt-4 uppercase text-xs tracking-wide inline-block border-b border-gray-200 pb-0.5 cursor-pointer transition-colors duration-300 hover:text-amber-600 hover:border-amber-600">
                  <button @click="showColorModal = true">
                    Color Analysis
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Category distribution chart -->
          <div class="bg-white shadow rounded p-6 mb-8">
            <h2 class="text-xl font-serif font-normal tracking-wide mb-6 flex items-center after:content-[''] after:h-px after:flex-1 after:ml-4 after:bg-gray-200">
              Wardrobe Composition
            </h2>
            <div class="flex flex-wrap gap-3">
              <div 
                v-for="category in categoryStats" 
                :key="category.id" 
                class="flex-1 min-w-[180px] bg-gray-50 p-4 rounded border border-gray-200 hover:border-black hover:shadow transition-all cursor-pointer"
                @click="filterByCategory(category.id)"
              >
                <div class="flex items-center justify-between mb-2">
                  <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">{{ category.name }}</h3>
                  <span class="text-lg font-semibold text-black">{{ category.count }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1">
                  <div 
                    class="bg-black h-1 rounded-full" 
                    :style="{ width: `${(category.count / stats.totalItems) * 100}%` }"
                  ></div>
                </div>
                <div class="mt-2 text-xs text-gray-500 uppercase tracking-wide">
                  {{ Math.round((category.count / stats.totalItems) * 100) }}% of your wardrobe
                </div>
              </div>
            </div>
          </div>
          
          <!-- Item breakdown section -->
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
            <!-- Size distribution -->
            <div class="bg-white shadow rounded p-6">
              <h2 class="text-xl font-serif font-normal tracking-wide mb-6 flex items-center after:content-[''] after:h-px after:flex-1 after:ml-4 after:bg-gray-200">
                Size Distribution
              </h2>
              <div class="space-y-5">
                <div v-for="(count, size) in sizeStats" :key="size" class="flex items-center">
                  <div class="w-24 text-sm text-gray-700 uppercase tracking-wide">{{ size }}</div>
                  <div class="flex-1 mx-4">
                    <div class="w-full bg-gray-200 rounded-full h-1">
                      <div 
                        class="bg-black h-1 rounded-full" 
                        :style="{ width: `${(count / stats.totalItems) * 100}%` }"
                      ></div>
                    </div>
                  </div>
                  <div class="w-12 text-right text-sm font-medium text-gray-700">{{ count }}</div>
                </div>
              </div>
            </div>
            
            <!-- Brand distribution -->
            <div class="bg-white shadow rounded p-6">
              <h2 class="text-xl font-serif font-normal tracking-wide mb-6 flex items-center after:content-[''] after:h-px after:flex-1 after:ml-4 after:bg-gray-200">
                Top Brands
              </h2>
              <div class="space-y-5">
                <div v-for="brand in topBrands" :key="brand.name" class="flex items-center">
                  <div class="w-24 truncate text-sm text-gray-700 uppercase tracking-wide">{{ brand.name || 'Unbranded' }}</div>
                  <div class="flex-1 mx-4">
                    <div class="w-full bg-gray-200 rounded-full h-1">
                      <div 
                        class="bg-amber-600 h-1 rounded-full" 
                        :style="{ width: `${(brand.count / stats.totalItems) * 100}%` }"
                      ></div>
                    </div>
                  </div>
                  <div class="w-12 text-right text-sm font-medium text-gray-700">{{ brand.count }}</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Recent items -->
          <div class="bg-white shadow rounded p-6 mb-10">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-serif font-normal tracking-wide flex items-center after:content-[''] after:h-px after:flex-1 after:ml-4 after:bg-gray-200">
                Recent Acquisitions
              </h2>
              <router-link :to="{ name: 'clothing-items' }" class="text-xs uppercase tracking-wide border-b border-gray-200 pb-0.5 transition-colors duration-300 hover:text-amber-600 hover:border-amber-600">
                View All
              </router-link>
            </div>
            <div class="overflow-hidden" v-if="recentItems.length > 0">
              <ul class="divide-y divide-gray-200">
                <li v-for="item in recentItems" :key="item.id" class="py-4 flex">
                  <div class="flex-shrink-0 h-20 w-20 bg-gray-100 flex items-center justify-center overflow-hidden">
                    <img v-if="item.image_path" :src="'/storage/' + item.image_path" :alt="item.name" class="h-full w-full object-cover" />
                    <span v-else class="text-gray-400 text-xs uppercase">No image</span>
                  </div>
                  <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-black tracking-wide truncate">{{ item.name }}</p>
                      <p class="text-xs text-gray-500">{{ formatDate(item.created_at) }}</p>
                    </div>
                    <p class="text-sm text-gray-500 tracking-wide truncate">{{ item.brand || 'Unknown brand' }} | {{ item.size }} | {{ item.color }}</p>
                    <p v-if="item.category" class="text-xs text-gray-400 uppercase tracking-wide mt-1">{{ item.category.name }}</p>
                  </div>
                </li>
              </ul>
            </div>
            <div v-else class="py-8 text-center text-gray-500">
              <p class="uppercase tracking-wide text-sm">No items added yet</p>
              <p class="text-xs text-gray-400 mt-2">Start building your wardrobe inventory</p>
            </div>
          </div>
        </div>
        
        <!-- Category details modal -->
        <div v-if="showCategoryModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
          <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCategoryModal = false"></div>
    
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    
            <div class="inline-block align-bottom bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-serif text-black tracking-wide" id="modal-title">
                      Category Breakdown
                    </h3>
                    <div class="mt-6 space-y-4">
                      <div v-for="category in categoryStats" :key="category.id" class="flex justify-between items-center">
                        <div class="flex items-center">
                          <div class="w-3 h-3 rounded-full mr-3" :style="{ backgroundColor: getCategoryColor(category.id) }"></div>
                          <span class="text-sm text-gray-700 uppercase tracking-wide">{{ category.name }}</span>
                        </div>
                        <div class="flex items-center">
                          <span class="text-sm font-medium text-gray-900 mr-2">{{ category.count }}</span>
                          <span class="text-xs text-gray-500">({{ Math.round((category.count / stats.totalItems) * 100) }}%)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showCategoryModal = false">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Color details modal -->
        <div v-if="showColorModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
          <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showColorModal = false"></div>
    
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    
            <div class="inline-block align-bottom bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-serif text-black tracking-wide" id="modal-title">
                      Color Breakdown
                    </h3>
                    <div class="mt-6 max-h-80 overflow-y-auto">
                      <div class="flex flex-wrap gap-2">
                        <div 
                          v-for="(count, color) in colorStats" 
                          :key="color" 
                          class="flex items-center gap-2 bg-gray-50 p-2 rounded"
                        >
                          <div 
                            class="w-6 h-6 rounded-full flex-shrink-0 border border-gray-300" 
                            :style="{ backgroundColor: normalizeColorName(color) }"
                          ></div>
                          <span class="text-sm uppercase tracking-wide">{{ color }}</span>
                          <span class="text-xs text-gray-500">({{ count }})</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="showColorModal = false">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <router-view></router-view>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  // Create API instance
  const api = axios.create({
    baseURL: '',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  });
  
  // Add auth token if available
  if (localStorage.getItem('token')) {
    api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
  }
  
  export default {
    name: 'Dashboard',
    data() {
      return {
        loading: true,
        error: null,
        showCategoryModal: false,
        showColorModal: false,
        clothingItems: [],
        categories: [],
        stats: {
          totalItems: 0,
          categoriesUsed: 0,
          recentAdditions: 0,
          uniqueColors: 0
        },
        categoryStats: [],
        sizeStats: {},
        colorStats: {},
        brandStats: [],
        recentItems: []
      };
    },
    computed: {
      topBrands() {
        return this.brandStats
          .sort((a, b) => b.count - a.count)
          .slice(0, 5);
      }
    },
    created() {
      this.fetchDashboardData();
    },
    methods: {
      async fetchDashboardData() {
        this.loading = true;
        this.error = null;
        
        try {
          // Fetch all clothing items with increased page size
          await this.fetchAllClothingItems();
          
          // Fetch all categories
          const categoriesResponse = await api.get('/api/categories');
          
          if (categoriesResponse.data) {
            this.categories = categoriesResponse.data;
            this.processCategoryStats();
          }
        } catch (err) {
          console.error('Error fetching dashboard data:', err);
          this.error = 'Failed to load dashboard data. Please try again later.';
        } finally {
          this.loading = false;
        }
      },
  
      async fetchAllClothingItems() {
        try {
          // Request a much larger page size
          const response = await api.get('/api/clothing-items', {
            params: { 
              per_page: 500
            }
          });
          
          if (response.data && response.data.data) {
            this.clothingItems = response.data.data;
            console.log(`Loaded ${this.clothingItems.length} clothing items`);
            this.processStats();
          }
        } catch (error) {
          console.error('Error fetching clothing items:', error);
          this.error = 'Failed to load your clothing items. Please try again later.';
        }
      },
      
      processStats() {
        const items = this.clothingItems;
        
        // Basic stats
        this.stats.totalItems = items.length;
        
        // Recent additions (last 30 days)
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        
        this.stats.recentAdditions = items.filter(item => {
          const createdDate = new Date(item.created_at);
          return createdDate > thirtyDaysAgo;
        }).length;
        
        // Recent items for display
        this.recentItems = items
          .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
          .slice(0, 5);
        
        // Size stats
        this.sizeStats = items.reduce((acc, item) => {
          acc[item.size] = (acc[item.size] || 0) + 1;
          return acc;
        }, {});
        
        // Color stats
        this.colorStats = items.reduce((acc, item) => {
          if (item.color) {
            acc[item.color] = (acc[item.color] || 0) + 1;
          }
          return acc;
        }, {});
        
        this.stats.uniqueColors = Object.keys(this.colorStats).length;
        
        // Brand stats
        const brandCounts = items.reduce((acc, item) => {
          const brand = item.brand || 'Unbranded';
          acc[brand] = (acc[brand] || 0) + 1;
          return acc;
        }, {});
        
        this.brandStats = Object.entries(brandCounts).map(([name, count]) => ({ name, count }));
      },
      
      processCategoryStats() {
        // Map of category IDs used
        const categoryMap = this.clothingItems.reduce((acc, item) => {
          if (item.category_id) {
            acc[item.category_id] = (acc[item.category_id] || 0) + 1;
          }
          return acc;
        }, {});
        
        // Number of categories used
        this.stats.categoriesUsed = Object.keys(categoryMap).length;
        
        // Detailed category stats
        this.categoryStats = this.categories.map(category => ({
          id: category.id,
          name: category.name,
          count: categoryMap[category.id] || 0
        }))
        .sort((a, b) => b.count - a.count);
      },
      
      filterByCategory(categoryId) {
        this.$router.push({
          name: 'clothing-items',
          query: { category: categoryId }
        });
      },
      
      formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      },
      
      getCategoryColor(categoryId) {
        // Generate a consistent color based on category ID
        const colors = [
          '#000000', '#333333', '#777777', '#D4AF37', '#A28A50',
          '#606060', '#1A1A1A', '#555555', '#999999', '#C4C4C4'
        ];
        
        return colors[categoryId % colors.length];
      },
      
      normalizeColorName(colorName) {
        // Convert common color names to actual colors
        const colorMap = {
          'black': '#000000',
          'white': '#FFFFFF',
          'red': '#FF0000',
          'blue': '#0000FF',
          'green': '#008000',
          'yellow': '#FFFF00',
          'purple': '#800080',
          'orange': '#FFA500',
          'pink': '#FFC0CB',
          'brown': '#A52A2A',
          'gray': '#808080',
          'grey': '#808080',
          'navy': '#000080',
          'beige': '#F5F5DC',
          'turquoise': '#40E0D0',
          'gold': '#D4AF37',
          'silver': '#C0C0C0'
        };
        
        const normalized = colorName.toLowerCase();
        return colorMap[normalized] || '#CCCCCC';
      }
    }
  };
  </script>
