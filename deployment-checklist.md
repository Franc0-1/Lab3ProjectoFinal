# Deployment Checklist para Render

## Problemas Identificados y Soluciones

### 1. Error 500 del Servidor
- **Problema**: El servidor responde con error 500
- **Causa**: Posible problema con configuración HTTPS, assets, o base de datos
- **Solución**: Configurar proxies confiables y forzar HTTPS

### 2. Assets CSS No Cargan
- **Problema**: `app-Cc5Ry4OS.css` no se carga correctamente
- **Causa**: Problemas con la configuración de Vite en producción
- **Solución**: Configurar correctamente el build de Vite y asset URL

### 3. Configuración de Render
Asegúrate de que las siguientes variables estén configuradas en Render:

```yaml
envVars:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: "false"
  - key: APP_KEY
    value: base64:tu_clave_aqui
  - key: APP_URL
    value: https://laquevapizza.onrender.com
  - key: ASSET_URL
    value: https://laquevapizza.onrender.com
  - key: APP_FORCE_HTTPS
    value: "true"
  - key: TRUSTED_PROXIES
    value: "*"
  - key: SESSION_SECURE_COOKIE
    value: "true"
  - key: SESSION_DOMAIN
    value: laquevapizza.onrender.com
  - key: LOG_CHANNEL
    value: stderr
  - key: LOG_LEVEL
    value: error
```

### 4. Cambios Realizados

#### Dockerfile
- ✅ Agregado Node.js para construir assets
- ✅ Configurado Apache con módulos rewrite, headers, expires
- ✅ Añadida configuración SSL personalizada
- ✅ Solucionado problema de Composer con COMPOSER_ALLOW_SUPERUSER=1
- ✅ Creado archivo .env.docker para el build

#### AppServiceProvider
- ✅ Configurado trusted proxies para producción
- ✅ Forzado HTTPS en producción

#### Vite Config
- ✅ Configurado base URL para producción
- ✅ Configurado build para assets correctos

#### Scripts de Deployment
- ✅ docker-start.sh actualizado con storage:link
- ✅ render-build.sh creado para deployment sin Docker

### 5. Verificación Post-Deploy

1. **Verificar que los logs no muestren errores**:
   ```bash
   # En Render, revisar los logs del deployment
   ```

2. **Verificar que los assets se carguen**:
   - CSS: `https://laquevapizza.onrender.com/build/assets/app-*.css`
   - JS: `https://laquevapizza.onrender.com/build/assets/app-*.js`

3. **Verificar que la base de datos esté accesible**:
   - Migraciones ejecutadas correctamente
   - Tablas creadas (incluyendo sessions)

### 6. Próximos Pasos

1. **Redeploy** la aplicación en Render
2. **Monitorear** los logs durante el deployment
3. **Verificar** que la página carga correctamente
4. **Probar** funcionalidades críticas

### 7. Troubleshooting

Si el problema persiste:
1. Revisar logs de Render
2. Verificar que todas las variables de entorno estén configuradas
3. Verificar que el build se complete exitosamente
4. Comprobar conectividad con la base de datos PostgreSQL
