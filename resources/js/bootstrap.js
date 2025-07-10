import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Importar Ziggy para las rutas
import { Ziggy } from './ziggy';
import { route as ziggyRoute } from 'ziggy-js';

// Create a safer route function with error handling
window.route = function(name, params = {}, absolute = false) {
    // Handle case where route() is called without parameters
    if (typeof name === 'undefined' || name === null) {
        // Return an object with a current function for route().current() calls
        return {
            current: function(routeName) {
                try {
                    if (!routeName) return false;
                    
                    const currentPath = window.location.pathname;
                    
                    // Handle wildcard patterns like 'customers.*'
                    if (routeName.endsWith('.*')) {
                        const baseRoute = routeName.replace('.*', '');
                        // Check if current path starts with the base route
                        return currentPath.startsWith('/' + baseRoute) || 
                               currentPath.includes('/' + baseRoute + '/');
                    }
                    
                    // Handle exact route names
                    if (routeName === 'dashboard') {
                        return currentPath === '/dashboard' || currentPath === '/' || currentPath === '';
                    }
                    
                    if (routeName === 'customers.index') {
                        return currentPath === '/customers';
                    }
                    
                    if (routeName === 'cart.index') {
                        return currentPath === '/cart';
                    }
                    
                    // General pattern: convert route.name to /route/name
                    const expectedPath = '/' + routeName.replace('.', '/');
                    return currentPath === expectedPath || currentPath.startsWith(expectedPath + '/');
                    
                } catch (error) {
                    console.error('Error checking current route:', error);
                    return false;
                }
            }
        };
    }
    
    try {
        if (!Ziggy || !Ziggy.routes) {
            console.error('Ziggy routes not available');
            return '#';
        }
        
        if (!Ziggy.routes[name]) {
            console.error(`Route '${name}' not found in Ziggy routes`);
            return '#';
        }
        
        const result = ziggyRoute(name, params, absolute, Ziggy);
        if (!result) {
            console.error(`Route '${name}' generated null result`);
            return '#';
        }
        
        return result;
    } catch (error) {
        console.error(`Error generating route '${name}':`, error);
        return '#';
    }
};

window.Ziggy = Ziggy;
