<template>
  <div class="min-h-screen bg-gray-50 font-sans">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
      <div class="bg-black">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="pt-16 pb-20 lg:pt-24 lg:pb-32 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 lg:pr-12">
              <h1 class="text-4xl font-serif font-light tracking-wider text-white sm:text-5xl lg:text-6xl">
                WARDROBE<span class="text-amber-600">.</span>ESSENTIALS
              </h1>
              <p class="mt-8 text-xl text-gray-300 max-w-3xl font-light tracking-wide">
                Curate your collection. Elevate your style. Organize with intention.
              </p>
              <div class="mt-12 flex space-x-6">
                <router-link
                  v-if="!isLoggedIn"
                  :to="{ name: 'register' }"
                  class="inline-flex items-center px-8 py-3 border border-transparent text-base tracking-wide uppercase text-black bg-white hover:bg-gray-100 transition-colors duration-300"
                >
                  Begin
                </router-link>
                <router-link
                  v-if="isLoggedIn"
                  :to="{ name: 'clothing-items' }"
                  class="inline-flex items-center px-8 py-3 border border-transparent text-base tracking-wide uppercase text-black bg-white hover:bg-gray-100 transition-colors duration-300"
                >
                  My Collection
                </router-link>
                <router-link
                  v-if="!isLoggedIn"
                  :to="{ name: 'login' }"
                  class="inline-flex items-center px-8 py-3 border border-white text-base tracking-wide uppercase text-white hover:bg-white hover:text-black transition-colors duration-300"
                >
                  Log In
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base tracking-wider uppercase border-b border-gray-200 pb-3 inline-block">Features</h2>
          <p class="mt-8 text-3xl leading-8 font-serif font-light tracking-wide text-gray-900 sm:text-4xl">
            Thoughtful wardrobe management
          </p>
          <p class="mt-6 max-w-2xl text-lg text-gray-500 lg:mx-auto font-light">
            Everything you need to build an intentional collection of pieces you love.
          </p>
        </div>

        <div class="mt-16">
          <div class="grid grid-cols-1 gap-10 sm:grid-cols-3">
            <div class="border-l border-gray-200 pl-6">
              <div class="h-12 w-12 flex items-center mb-8">
                <svg class="h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
              </div>
              <h3 class="text-lg font-light text-gray-900 mb-3 tracking-wide uppercase">Inventory Management</h3>
              <p class="text-gray-500 font-light">
                Track every piece with meticulous detail—from fabric composition to care instructions.
              </p>
            </div>

            <div class="border-l border-gray-200 pl-6">
              <div class="h-12 w-12 flex items-center mb-8">
                <svg class="h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
              </div>
              <h3 class="text-lg font-light text-gray-900 mb-3 tracking-wide uppercase">Smart Filtering</h3>
              <p class="text-gray-500 font-light">
                Navigate your collection with precision—filter by category, color, season, or occasion.
              </p>
            </div>

            <div class="border-l border-gray-200 pl-6">
              <div class="h-12 w-12 flex items-center mb-8">
                <svg class="h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h3 class="text-lg font-light text-gray-900 mb-3 tracking-wide uppercase">Visual Gallery</h3>
              <p class="text-gray-500 font-light">
                Build a visual archive of your collection—document each piece with pristine imagery.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-gray-50">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base tracking-wider uppercase border-b border-gray-200 pb-3 inline-block">Process</h2>
          <p class="mt-8 text-3xl leading-8 font-serif font-light tracking-wide text-gray-900 sm:text-4xl">
            Three steps to curated style
          </p>
        </div>

        <div class="mt-16">
          <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
            <div class="relative">
              <div class="absolute top-0 left-0 -ml-6 border-t border-gray-300 w-12"></div>
              <div class="text-6xl font-serif text-gray-200 absolute top-[-50px] left-[-30px] z-0">1</div>
              <div class="relative z-10">
                <h3 class="text-lg uppercase tracking-wide font-light text-gray-900">Create account</h3>
                <p class="mt-4 text-gray-500 font-light">
                  Begin with a simple sign-up—the first step toward intentional wardrobe curation.
                </p>
              </div>
            </div>

            <div class="relative">
              <div class="absolute top-0 left-0 -ml-6 border-t border-gray-300 w-12"></div>
              <div class="text-6xl font-serif text-gray-200 absolute top-[-50px] left-[-30px] z-0">2</div>
              <div class="relative z-10">
                <h3 class="text-lg uppercase tracking-wide font-light text-gray-900">Document pieces</h3>
                <p class="mt-4 text-gray-500 font-light">
                  Add each garment with detailed specifications and professional imagery.
                </p>
              </div>
            </div>

            <div class="relative">
              <div class="absolute top-0 left-0 -ml-6 border-t border-gray-300 w-12"></div>
              <div class="text-6xl font-serif text-gray-200 absolute top-[-50px] left-[-30px] z-0">3</div>
              <div class="relative z-10">
                <h3 class="text-lg uppercase tracking-wide font-light text-gray-900">Manage collection</h3>
                <p class="mt-4 text-gray-500 font-light">
                  Organize, analyze, and evolve your collection with intelligent tools.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-black">
      <div class="max-w-2xl mx-auto text-center py-20 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-serif font-light tracking-wide text-white sm:text-4xl">
          <span class="block">Ready to elevate your wardrobe?</span>
        </h2>
        <p class="mt-6 text-lg leading-6 text-gray-300 font-light tracking-wide">
          Bring intention to your collection. Every piece documented. Every decision mindful.
        </p>
        <div class="mt-10 flex justify-center">
          <div class="inline-flex">
            <router-link
              v-if="!isLoggedIn"
              :to="{ name: 'register' }"
              class="inline-flex items-center px-8 py-3 border border-transparent text-base tracking-wide uppercase text-black bg-white hover:bg-gray-100 transition-colors duration-300"
            >
              Begin Now
            </router-link>
            <router-link
              v-if="isLoggedIn"
              :to="{ name: 'clothing-items' }"
              class="inline-flex items-center px-8 py-3 border border-transparent text-base tracking-wide uppercase text-black bg-white hover:bg-gray-100 transition-colors duration-300"
            >
              View Collection
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false
    };
  },
  created() {
    // Check if user is logged in based on token existence
    this.isLoggedIn = !!localStorage.getItem('token');
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

/* Extended letter spacing */
.tracking-wider {
  letter-spacing: 0.1em;
}

.tracking-widest {
  letter-spacing: 0.2em;
}
</style>