// CSRF Token Handler
class CSRFHandler {
    constructor() {
        this.token = null;
        this.init();
    }

    init() {
        // Obtener token del meta tag
        this.updateTokenFromMeta();
        
        // Configurar interceptor para fetch
        this.setupFetchInterceptor();
        
        // Configurar interceptor para formularios
        this.setupFormInterceptor();
    }

    updateTokenFromMeta() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            this.token = metaToken.getAttribute('content');
            console.log('Token CSRF obtenido del meta tag:', this.token);
        } else {
            console.error('No se encontró el meta tag csrf-token');
        }
    }

    async refreshToken() {
        try {
            const response = await fetch('/test-csrf', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.token = data.csrf_token;
                
                // Actualizar el meta tag
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    metaToken.setAttribute('content', this.token);
                }
                
                console.log('Token CSRF actualizado:', this.token);
                return this.token;
            }
        } catch (error) {
            console.error('Error al actualizar token CSRF:', error);
        }
        return null;
    }

    setupFetchInterceptor() {
        const originalFetch = window.fetch;
        const self = this;
        
        window.fetch = function(url, options = {}) {
            // Solo para requests POST, PUT, PATCH, DELETE
            if (options.method && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(options.method.toUpperCase())) {
                options.headers = options.headers || {};
                options.headers['X-CSRF-TOKEN'] = self.token;
                
                // Si es FormData, agregar el token
                if (options.body instanceof FormData) {
                    options.body.append('_token', self.token);
                }
            }
            
            return originalFetch.apply(this, arguments);
        };
    }

    setupFormInterceptor() {
        const self = this;
        
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM' && form.method.toUpperCase() === 'POST') {
                // Verificar si ya existe un input de token
                let tokenInput = form.querySelector('input[name="_token"]');
                
                if (!tokenInput) {
                    tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    form.appendChild(tokenInput);
                }
                
                tokenInput.value = self.token;
                console.log('Token CSRF agregado al formulario:', self.token);
            }
        });
    }

    async handleTokenError() {
        console.log('Error de token CSRF detectado, intentando refrescar...');
        const newToken = await this.refreshToken();
        if (newToken) {
            return newToken;
        }
        return null;
    }
}

// Inicializar el handler cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.csrfHandler = new CSRFHandler();
});

// Exportar para uso global
window.CSRFHandler = CSRFHandler;
