// In src/axios.js or wherever you configure axios

import axios from 'axios';

// Create a custom axios instance
const api = axios.create({
    baseURL: '/api', // Ensure you're hitting the Laravel API routes
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Add a request interceptor to include the authentication token with every request
api.interceptors.request.use(
    config => {
        // Get the token from localStorage
        const token = localStorage.getItem('token');
        
        // If token exists, add it to the request headers
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

// Add a response interceptor to handle common errors
api.interceptors.response.use(
    response => response,
    error => {
        // Handle authentication errors (401)
        if (error.response && error.response.status === 401) {
            // Redirect to login or show an error message
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        
        return Promise.reject(error);
    }
);

export default api;
