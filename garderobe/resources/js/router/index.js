import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Auth/Login.vue';
import Home from '../components/Home.vue';
import Register from '../components/Auth/Register.vue';
import Dashboard from '../components/Dashboard.vue';
import ClothingItems from '../components/ClothingItems.vue';
import CreateClothingItem from '../components/CreateClothingItem.vue';
import EditClothingItem from '../components/EditClothingItem.vue';
import SingleClothingItem from '../components/SingleClothingItem.vue';
import ClothingItemGallery from '../components/ClothingItemGallery.vue';
import OutfitMatcher from '../components/OutfitMatcher.vue';
import notFound from '../components/NotFound.vue';

// Define routes
const routes = [
    { path: '/', component: Home, name: 'home' },
    { 
        path: '/login', 
        component: Login, 
        name: 'login',
        meta: { redirectIfAuth: true }
    },
    { 
        path: '/register', 
        component: Register, 
        name: 'register',
        meta: { redirectIfAuth: true }
    },
    { 
        path: '/dashboard', 
        component: Dashboard, 
        name: 'dashboard',
        meta: { requiresAuth: true },
        children: [
            { path: 'clothing-items', component: ClothingItems, name: 'clothing-items' },
            { path: 'clothing-items/create', component: CreateClothingItem, name: 'create-clothing-item' },
            { path: 'clothing-items/:id/edit', component: EditClothingItem, name: 'edit-clothing-item' },
            { path: 'clothing-items/:id', component: SingleClothingItem, name: 'single-clothing-item' },
            { path: 'clothing-items-gallery', component: ClothingItemGallery, name: 'clothing-items-gallery' }
        ]
    },
    {
        path: '/clothing-items',
        name: 'clothing-items',
        component: ClothingItems,
        props: { isModal: false }
    },
    {
        path: '/clothing-items/create',
        name: 'create-clothing-item',
        component: CreateClothingItem
    },
    {
        path: '/clothing-items/:id/edit',
        name: 'edit-clothing-item',
        component: EditClothingItem,
        props: true
    },
    {
        path: '/clothing-items/:id',
        name: 'single-clothing-item',
        component: SingleClothingItem,
        props: true
    },
    {
        path: '/clothing-items-gallery',
        name: 'clothing-items-gallery',
        component: ClothingItemGallery
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound,
    },
    {
      path: '/dashboard/outfit-matcher',
      component: OutfitMatcher,
      name: 'dashboard-outfit-matcher',
      meta: { requiresAuth: true }
    },
    {
      path: '/outfit-matcher',
      name: 'outfit-matcher',
      component: OutfitMatcher,
      meta: { requiresAuth: true }
    },
];

// Create router
const router = createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
});

// Enhanced navigation guard to check authentication and handle redirects
router.beforeEach((to, from, next) => {
  // Check if user is authenticated
  const token = localStorage.getItem('token');
  const isLoggedIn = !!token;
  
  // Check if the route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isLoggedIn) {
      // Not authenticated, redirect to login
      next({
        name: 'login',
        query: { redirect: to.fullPath } // Store the path they were trying to access
      });
    } else {
      // User is authenticated, proceed
      next();
    }
  } 
  // Check if route should redirect authenticated users (login/register pages)
  else if (isLoggedIn && to.matched.some(record => record.meta.redirectIfAuth)) {
    // User is logged in and trying to access login/register page, redirect to dashboard
    next({ name: 'dashboard' });
  } 
  else {
    // Route doesn't have special requirements, proceed normally
    next();
  }
});

export default router;
