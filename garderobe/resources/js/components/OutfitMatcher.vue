<template>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 font-sans">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-96">
        <div class="text-center">
          <div class="inline-block h-12 w-12 animate-spin rounded-full border-2 border-solid border-current border-r-transparent text-black align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
          <p class="mt-6 text-sm text-black uppercase tracking-wider font-light">Loading Your Collection</p>
        </div>
      </div>
  
      <!-- Outfit Matcher -->
      <div v-else class="animate-fadeIn">
        <div class="flex flex-col md:flex-row gap-8 mb-12">
          <div class="md:w-1/2">
            <h1 class="text-3xl font-serif font-light tracking-wide text-gray-900 mb-4">Outfit Color Matcher</h1>
            <p class="text-gray-600 mb-6">Select a base item from your collection, and we'll suggest complementary pieces based on color theory principles.</p>
          
            <!-- Color Theory Guide -->
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-6 mb-8">
              <h3 class="text-lg font-medium mb-4">Color Matching Guide</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-start space-x-3">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 mt-1"></div>
                  <div>
                    <p class="font-medium text-sm">Analogous</p>
                    <p class="text-xs text-gray-500">Colors beside each other on the color wheel</p>
                  </div>
                </div>
                <div class="flex items-start space-x-3">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-r from-red-500 to-green-500 mt-1"></div>
                  <div>
                    <p class="font-medium text-sm">Complementary</p>
                    <p class="text-xs text-gray-500">Colors opposite each other on the color wheel</p>
                  </div>
                </div>
                <div class="flex items-start space-x-3">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-r from-gray-300 to-gray-500 mt-1"></div>
                  <div>
                    <p class="font-medium text-sm">Monochromatic</p>
                    <p class="text-xs text-gray-500">Different shades of the same color</p>
                  </div>
                </div>
                <div class="flex items-start space-x-3">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-r from-yellow-300 to-blue-500 to-red-500 mt-1"></div>
                  <div>
                    <p class="font-medium text-sm">Triadic</p>
                    <p class="text-xs text-gray-500">Three colors evenly spaced on the color wheel</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Base Item Selection -->
            <div class="mb-8">
              <label class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-3">Select Your Base Item</label>
              <div class="relative">
                <select 
                  v-model="selectedBaseItemId" 
                  @change="matchOutfit"
                  class="block w-full border-2 border-gray-200 py-3 px-4 focus:ring-0 focus:border-black text-sm rounded-md transition-all duration-300 outline-none appearance-none"
                >
                  <option value="">Choose an item</option>
                  <optgroup 
                    v-for="category in categorizedItems" 
                    :key="category.id" 
                    :label="category.name"
                  >
                    <option 
                      v-for="item in category.items" 
                      :key="item.id" 
                      :value="item.id"
                    >
                      {{ item.name }} ({{ item.color }})
                    </option>
                  </optgroup>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
            </div>
            
            <!-- Color Harmony Preference -->
            <div class="mb-8">
              <label class="block text-xs font-light tracking-wider uppercase text-gray-500 mb-3">Match Style</label>
              <div class="grid grid-cols-2 gap-3">
                <button 
                  @click="setMatchType('complementary')"
                  class="px-4 py-3 border-2 transition-all duration-300 rounded-md text-sm text-center"
                  :class="matchType === 'complementary' ? 'border-black bg-black text-white' : 'border-gray-200 hover:border-gray-300'"
                >
                  Complementary
                </button>
                <button 
                  @click="setMatchType('analogous')"
                  class="px-4 py-3 border-2 transition-all duration-300 rounded-md text-sm text-center"
                  :class="matchType === 'analogous' ? 'border-black bg-black text-white' : 'border-gray-200 hover:border-gray-300'"
                >
                  Analogous
                </button>
                <button 
                  @click="setMatchType('monochromatic')"
                  class="px-4 py-3 border-2 transition-all duration-300 rounded-md text-sm text-center"
                  :class="matchType === 'monochromatic' ? 'border-black bg-black text-white' : 'border-gray-200 hover:border-gray-300'"
                >
                  Monochromatic
                </button>
                <button 
                  @click="setMatchType('triadic')"
                  class="px-4 py-3 border-2 transition-all duration-300 rounded-md text-sm text-center"
                  :class="matchType === 'triadic' ? 'border-black bg-black text-white' : 'border-gray-200 hover:border-gray-300'"
                >
                  Triadic
                </button>
              </div>
            </div>
            
            <!-- Selected Base Item -->
            <div v-if="baseItem" class="bg-white border-2 border-gray-200 rounded-lg p-5 mb-8">
              <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                  <img 
                    v-if="getImageUrl(baseItem.image_path)" 
                    :src="getImageUrl(baseItem.image_path)" 
                    :alt="baseItem.name" 
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <div class="w-8 h-8 rounded-full" :style="{ backgroundColor: baseItem.color }"></div>
                  </div>
                </div>
                <div>
                  <h3 class="font-medium">{{ baseItem.name }}</h3>
                  <div class="flex items-center mt-1">
                    <div class="w-4 h-4 rounded-full mr-2" :style="{ backgroundColor: baseItem.color }"></div>
                    <p class="text-sm text-gray-600">{{ baseItem.color }}</p>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">{{ getCategoryName(baseItem.category_id) }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Matched Items Display -->
          <div class="md:w-1/2">
            <div v-if="baseItem" class="bg-white border-2 border-gray-100 rounded-lg p-6 h-full">
              <h2 class="text-xl font-light mb-6">Suggested Matches</h2>
              
              <div v-if="matchedItems.length > 0">
                <div v-for="category in sortedMatchedCategories" :key="category" class="mb-8">
                  <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-3">{{ category }}</h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div 
                      v-for="item in getMatchedItemsByCategory(category)" 
                      :key="item.id" 
                      class="border border-gray-200 rounded-md p-3 hover:border-gray-300 transition-all duration-300"
                    >
                      <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                          <img 
                            v-if="getImageUrl(item.image_path)" 
                            :src="getImageUrl(item.image_path)" 
                            :alt="item.name" 
                            class="w-full h-full object-cover"
                          />
                          <div v-else class="w-full h-full flex items-center justify-center">
                            <div class="w-6 h-6 rounded-full" :style="{ backgroundColor: item.color }"></div>
                          </div>
                        </div>
                        <div>
                          <p class="text-sm font-medium truncate">{{ item.name }}</p>
                          <div class="flex items-center mt-1">
                            <div class="w-3 h-3 rounded-full mr-1" :style="{ backgroundColor: item.color }"></div>
                            <p class="text-xs text-gray-600 truncate">{{ item.color }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="mt-2 text-right">
                        <router-link 
                          :to="{ name: 'single-clothing-item', params: { id: item.id } }" 
                          class="text-xs text-black hover:underline"
                        >
                          View Details
                        </router-link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-else-if="baseItem" class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <p class="text-gray-600">No matching items found for {{ matchType }} colors. Try selecting a different match style or add more varied clothing items to your collection.</p>
              </div>
            </div>
            
            <div v-else class="bg-gray-50 border border-gray-100 rounded-lg p-8 h-full flex items-center justify-center">
              <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600">Select a base item to start matching your outfits.</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Color Theory Information -->
        <div class="mt-12 bg-white rounded-lg shadow-sm overflow-hidden">
          <div class="p-6 sm:p-8">
            <h2 class="text-2xl font-serif font-light tracking-wide text-gray-900 mb-6">Understanding Color Coordination</h2>
            <div class="grid md:grid-cols-2 gap-8">
              <div>
                <h3 class="text-lg font-medium mb-3">Why Color Matters</h3>
                <p class="text-gray-600 mb-4">Color coordination is one of the most important aspects of creating cohesive outfits. Well-matched colors can elevate your style, while mismatched combinations can make even expensive clothing look less impressive.</p>
                <p class="text-gray-600">Our outfit matcher uses color theory principles to suggest items that will coordinate well together based on your selected preferences.</p>
              </div>
              <div>
                <h3 class="text-lg font-medium mb-3">Tips for Color Matching</h3>
                <ul class="text-gray-600 space-y-2">
                  <li class="flex items-start">
                    <span class="text-black mr-2">•</span>
                    <span>When in doubt, neutral colors (black, white, gray, navy, beige) match with almost everything</span>
                  </li>
                  <li class="flex items-start">
                    <span class="text-black mr-2">•</span>
                    <span>For beginners, try the 60-30-10 rule: 60% main color, 30% secondary color, 10% accent color</span>
                  </li>
                  <li class="flex items-start">
                    <span class="text-black mr-2">•</span>
                    <span>Consider the season - warmer colors for fall/winter, cooler colors for spring/summer</span>
                  </li>
                  <li class="flex items-start">
                    <span class="text-black mr-2">•</span>
                    <span>Pay attention to your skin tone - some colors will complement your natural coloring better than others</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          
          <!-- Color Wheel Visualization -->
          <div class="border-t border-gray-100 p-6 sm:p-8">
            <h3 class="text-lg font-medium mb-6">Color Harmony Reference</h3>
            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">
              <div class="p-4 border border-gray-100 rounded-lg">
                <div class="w-full h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md mb-3"></div>
                <h4 class="font-medium text-sm mb-1">Analogous</h4>
                <p class="text-xs text-gray-600">Colors that are next to each other on the color wheel (e.g., blue and purple). These create harmonious and serene combinations.</p>
              </div>
              <div class="p-4 border border-gray-100 rounded-lg">
                <div class="w-full h-12 bg-gradient-to-r from-blue-500 to-orange-500 rounded-md mb-3"></div>
                <h4 class="font-medium text-sm mb-1">Complementary</h4>
                <p class="text-xs text-gray-600">Colors opposite each other on the color wheel (e.g., blue and orange). These create vibrant, high-contrast combinations.</p>
              </div>
              <div class="p-4 border border-gray-100 rounded-lg">
                <div class="w-full h-12 bg-gradient-to-r from-blue-300 via-blue-500 to-blue-700 rounded-md mb-3"></div>
                <h4 class="font-medium text-sm mb-1">Monochromatic</h4>
                <p class="text-xs text-gray-600">Different shades, tints, and tones of the same color. These create sophisticated, cohesive looks.</p>
              </div>
              <div class="p-4 border border-gray-100 rounded-lg">
                <div class="w-full h-12 bg-gradient-to-r from-red-500 via-yellow-500 to-blue-500 rounded-md mb-3"></div>
                <h4 class="font-medium text-sm mb-1">Triadic</h4>
                <p class="text-xs text-gray-600">Three colors evenly spaced on the color wheel (e.g., red, yellow, blue). These create bold, balanced combinations.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>
import axios from 'axios';
import _ from 'lodash';

const api = axios.create({
  baseURL: '',
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

// Color mapping and utilities
const colorMap = {
  // Basic colors
  "red": "#FF0000",
  "orange": "#FFA500",
  "yellow": "#FFFF00",
  "green": "#008000",
  "blue": "#0000FF",
  "purple": "#800080",
  "pink": "#FFC0CB",
  "brown": "#A52A2A",
  "black": "#000000",
  "white": "#FFFFFF",
  "gray": "#808080",
  "grey": "#808080",
  
  // Extended colors
  "navy": "#000080",
  "teal": "#008080",
  "olive": "#808000",
  "maroon": "#800000",
  "lime": "#00FF00",
  "aqua": "#00FFFF",
  "fuchsia": "#FF00FF",
  "silver": "#C0C0C0",
  "gold": "#FFD700",
  "beige": "#F5F5DC",
  "tan": "#D2B48C",
  "coral": "#FF7F50",
  "turquoise": "#40E0D0",
  "lavender": "#E6E6FA",
  "indigo": "#4B0082",
  "violet": "#EE82EE",
  "khaki": "#F0E68C",
  "salmon": "#FA8072",
  "crimson": "#DC143C",
  "ivory": "#FFFFF0",
  "charcoal": "#36454F",
  "burgundy": "#800020",
  "mustard": "#FFDB58",
  "mint": "#98FB98",
  "navy blue": "#000080",
  "royal blue": "#4169E1",
  "sky blue": "#87CEEB",
  "dark blue": "#00008B",
  "light blue": "#ADD8E6",
  "forest green": "#228B22",
  "dark green": "#006400",
  "light green": "#90EE90",
  "dark red": "#8B0000",
  "light red": "#FF6347",
  "dark purple": "#4B0082",
  "light purple": "#DA70D6",
  "dark grey": "#A9A9A9",
  "dark gray": "#A9A9A9",
  "light grey": "#D3D3D3",
  "light gray": "#D3D3D3",
  "off white": "#FAFAFA",
  "cream": "#FFFDD0"
};

export default {
  data() {
    return {
      items: [],
      categories: [],
      loading: true,
      selectedBaseItemId: '',
      matchType: 'complementary',
      matchedItems: []
    };
  },
  computed: {
    baseItem() {
      if (!this.selectedBaseItemId) return null;
      return this.items.find(item => item.id.toString() === this.selectedBaseItemId.toString());
    },
    categorizedItems() {
      return this.categories.map(category => {
        return {
          id: category.id,
          name: category.name,
          items: this.items.filter(item => item.category_id.toString() === category.id.toString())
        };
      }).filter(category => category.items.length > 0);
    },
    sortedMatchedCategories() {
      return _.uniq(this.matchedItems.map(item => this.getCategoryName(item.category_id))).sort();
    }
  },
  created() {
    this.fetchData();
    
    // Emit event to update breadcrumbs in parent component
    this.$emit('update-breadcrumb', [
      { name: 'Home', path: '/' },
      { name: 'Collection', path: '/clothing-items' },
      { name: 'Outfit Matcher', path: null }
    ]);
  },
  methods: {
    async fetchData() {
      this.loading = true;
      
      try {
        // Fetch categories
        const categoriesResponse = await api.get('/api/categories');
        if (Array.isArray(categoriesResponse.data)) {
          this.categories = categoriesResponse.data;
        } else {
          this.setupDefaultCategories();
        }
        
        // Fetch clothing items
        const itemsResponse = await api.get('/api/clothing-items', {
          params: { limit: 100 } // Fetch more items if API supports pagination
        });
        
        if (itemsResponse.data.data) {
          // Pagination response
          this.items = itemsResponse.data.data;
        } else if (Array.isArray(itemsResponse.data)) {
          // Direct array response
          this.items = itemsResponse.data;
        } else {
          throw new Error('Invalid data structure');
        }
      } catch (error) {
        console.error('Error fetching data:', error);
        // Create some dummy data for demo purposes
        this.setupDefaultCategories();
        this.setupDummyItems();
      } finally {
        this.loading = false;
      }
    },
    
    setupDefaultCategories() {
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
    
    setupDummyItems() {
      // Create sample items for demonstration if API fails
      this.items = [
        { id: 1, name: 'Blue T-Shirt', color: 'Blue', category_id: 1, image_path: null },
        { id: 2, name: 'Black Jeans', color: 'Black', category_id: 2, image_path: null },
        { id: 3, name: 'Red Sweater', color: 'Red', category_id: 1, image_path: null },
        { id: 4, name: 'Brown Boots', color: 'Brown', category_id: 4, image_path: null },
        { id: 5, name: 'Navy Blazer', color: 'Navy', category_id: 3, image_path: null },
        { id: 6, name: 'White Dress Shirt', color: 'White', category_id: 1, image_path: null },
        { id: 7, name: 'Gray Wool Coat', color: 'Gray', category_id: 3, image_path: null },
        { id: 8, name: 'Khaki Chinos', color: 'Beige', category_id: 2, image_path: null }
      ];
    },
    
    getCategoryName(categoryId) {
      if (!categoryId) return 'Uncategorized';
      const category = this.categories.find(c => c.id.toString() === categoryId.toString());
      return category ? category.name : 'Uncategorized';
    },
    
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
    
    setMatchType(type) {
      this.matchType = type;
      this.matchOutfit();
    },
    
    getMatchedItemsByCategory(categoryName) {
      return this.matchedItems.filter(item => {
        const itemCategory = this.getCategoryName(item.category_id);
        return itemCategory === categoryName;
      });
    },
    
    hexToRgb(hex) {
      // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
      const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
      hex = hex.replace(shorthandRegex, (m, r, g, b) => r + r + g + g + b + b);

      const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
      } : null;
    },
    
    rgbToHsl(r, g, b) {
      r /= 255;
      g /= 255;
      b /= 255;
      
      const max = Math.max(r, g, b);
      const min = Math.min(r, g, b);
      let h, s, l = (max + min) / 2;

      if (max === min) {
        h = s = 0; // achromatic
      } else {
        const d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        
        switch (max) {
          case r: h = (g - b) / d + (g < b ? 6 : 0); break;
          case g: h = (b - r) / d + 2; break;
          case b: h = (r - g) / d + 4; break;
        }
        
        h /= 6;
      }

      return { h, s, l };
    },
    
    hslToRgb(h, s, l) {
      let r, g, b;

      if (s === 0) {
        r = g = b = l; // achromatic
      } else {
        const hue2rgb = (p, q, t) => {
          if (t < 0) t += 1;
          if (t > 1) t -= 1;
          if (t < 1/6) return p + (q - p) * 6 * t;
          if (t < 1/2) return q;
          if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
          return p;
        };

        const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        const p = 2 * l - q;
        
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
      }

      return {
        r: Math.round(r * 255),
        g: Math.round(g * 255),
        b: Math.round(b * 255)
      };
    },
    
    rgbToHex(r, g, b) {
      return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
    },
    
    getColorHex(colorName) {
      if (!colorName) return '#000000';
      
      // If it's already a hex code
      if (colorName.startsWith('#')) {
        return colorName;
      }
      
      // Try to find the color in our color map
      const normalizedColor = colorName.toLowerCase().trim();
      const mappedColor = colorMap[normalizedColor];
      
      if (mappedColor) {
        return mappedColor;
      }
      
      // Check for compound color names
      for (const [key, value] of Object.entries(colorMap)) {
        if (normalizedColor.includes(key)) {
          return value;
        }
      }
      
      // Default fallback color
      return '#000000';
    },
    
    getComplementaryColor(hexColor) {
      const rgb = this.hexToRgb(hexColor);
      
      // Simple complementary: invert RGB
      return this.rgbToHex(
        255 - rgb.r,
        255 - rgb.g,
        255 - rgb.b
      );
    },
    
    getAnalogousColors(hexColor) {
      const rgb = this.hexToRgb(hexColor);
      const hsl = this.rgbToHsl(rgb.r, rgb.g, rgb.b);
      
      // Analogous colors (hue +/- 30 degrees)
      const hsl1 = { ...hsl, h: (hsl.h + 1/12) % 1 };
      const hsl2 = { ...hsl, h: (hsl.h - 1/12 + 1) % 1 };
      
      const rgb1 = this.hslToRgb(hsl1.h, hsl1.s, hsl1.l);
      const rgb2 = this.hslToRgb(hsl2.h, hsl2.s, hsl2.l);
      
      return [
        this.rgbToHex(rgb1.r, rgb1.g, rgb1.b),
        this.rgbToHex(rgb2.r, rgb2.g, rgb2.b)
      ];
    },
    
    getMonochromaticColors(hexColor) {
      const rgb = this.hexToRgb(hexColor);
      const hsl = this.rgbToHsl(rgb.r, rgb.g, rgb.b);
      
      // Create lighter and darker versions
      const hsl1 = { ...hsl, l: Math.min(hsl.l + 0.2, 0.9) };
      const hsl2 = { ...hsl, l: Math.max(hsl.l - 0.2, 0.1) };
      
      const rgb1 = this.hslToRgb(hsl1.h, hsl1.s, hsl1.l);
      const rgb2 = this.hslToRgb(hsl2.h, hsl2.s, hsl2.l);
      
      return [
        this.rgbToHex(rgb1.r, rgb1.g, rgb1.b),
        this.rgbToHex(rgb2.r, rgb2.g, rgb2.b)
      ];
    },
    
    getTriadicColors(hexColor) {
      const rgb = this.hexToRgb(hexColor);
      const hsl = this.rgbToHsl(rgb.r, rgb.g, rgb.b);
      
      // Triadic colors (hue +/- 120 degrees)
      const hsl1 = { ...hsl, h: (hsl.h + 1/3) % 1 };
      const hsl2 = { ...hsl, h: (hsl.h + 2/3) % 1 };
      
      const rgb1 = this.hslToRgb(hsl1.h, hsl1.s, hsl1.l);
      const rgb2 = this.hslToRgb(hsl2.h, hsl2.s, hsl2.l);
      
      return [
        this.rgbToHex(rgb1.r, rgb1.g, rgb1.b),
        this.rgbToHex(rgb2.r, rgb2.g, rgb2.b)
      ];
    },
    
    areColorsMatching(hexColor1, hexColor2, matchType) {
      const rgb1 = this.hexToRgb(hexColor1);
      const rgb2 = this.hexToRgb(hexColor2);
      
      // Convert to HSL for better color comparisons
      const hsl1 = this.rgbToHsl(rgb1.r, rgb1.g, rgb1.b);
      const hsl2 = this.rgbToHsl(rgb2.r, rgb2.g, rgb2.b);
      
      // If either color is a neutral (black, white, gray)
      if ((hsl1.s < 0.15 || hsl1.l > 0.9 || hsl1.l < 0.1) || 
          (hsl2.s < 0.15 || hsl2.l > 0.9 || hsl2.l < 0.1)) {
        return true; // Neutrals match with everything
      }
      
      switch (matchType) {
        case 'complementary':
          // Complementary: colors are roughly opposite on the color wheel
          return Math.abs(hsl1.h - hsl2.h) > 0.4 && Math.abs(hsl1.h - hsl2.h) < 0.6;
          
        case 'analogous':
          // Analogous: colors are close on the color wheel
          const hueDiff = Math.abs(hsl1.h - hsl2.h);
          return hueDiff < 0.12 || hueDiff > 0.88;
          
        case 'monochromatic':
          // Monochromatic: same hue, different brightness/saturation
          return Math.abs(hsl1.h - hsl2.h) < 0.08;
          
        case 'triadic':
          // Triadic: colors are evenly spaced on the color wheel
          const diff1 = Math.abs(hsl1.h - hsl2.h);
          const diff2 = 1 - diff1;
          return (diff1 > 0.3 && diff1 < 0.37) || (diff2 > 0.3 && diff2 < 0.37);
          
        default:
          return true;
      }
    },
    
    matchOutfit() {
      if (!this.baseItem) {
        this.matchedItems = [];
        return;
      }
      
      const baseColor = this.getColorHex(this.baseItem.color);
      
      // Filter items in other categories that match the selected color harmony
      this.matchedItems = this.items.filter(item => {
        // Don't include the base item itself
        if (item.id === this.baseItem.id) {
          return false;
        }
        
        // Don't include items from the same category for a complete outfit
        if (item.category_id === this.baseItem.category_id) {
          return false;
        }
        
        const itemColor = this.getColorHex(item.color);
        return this.areColorsMatching(baseColor, itemColor, this.matchType);
      });
    }
  }
};
</script>