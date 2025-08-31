# Configuración de WordPress con Local (Flywheel) - Versión Gratuita

Esta guía detalla cómo configurar WordPress usando Local de Flywheel únicamente con plugins gratuitos, especialmente la versión gratuita de Advanced Custom Fields (ACF).

## 📋 Requisitos Previos

- **Local by Flywheel** instalado y funcionando
- **Node.js 18.19.1+** instalado
- Conocimientos básicos de WordPress y ACF

## 🚀 1. Instalación de Local

1. **Descargar Local**
   - Ve a [localwp.com](https://localwp.com/)
   - Descarga e instala Local para tu sistema operativo

2. **Crear nuevo sitio**
   ```
   Nombre del sitio: renovalink
   Dominio local: renovalink.local
   Entorno: Nginx, PHP 8.1, MySQL 8.0
   Usuario admin: admin
   Contraseña: (usar contraseña segura)
   ```

## 🏗️ 2. Configuración Básica de WordPress

### Plugins Necesarios (TODOS GRATUITOS)

1. **Advanced Custom Fields (Versión Gratuita)**
   - Ir a Plugins > Añadir nuevo
   - Buscar "Advanced Custom Fields"
   - Instalar y activar la versión de Elliot Condon

2. **Custom Post Type UI**
   - Buscar e instalar "Custom Post Type UI"
   - Activar plugin

3. **Yoast SEO (Opcional)**
   - Para optimización SEO básica
   - Buscar e instalar "Yoast SEO"

### Configuración de Permalinks
- Ir a Ajustes > Enlaces permanentes
- Seleccionar "Nombre de la entrada"
- Guardar cambios

## 🎯 3. Configuración de Custom Post Types

### Usando Custom Post Type UI, crear:

#### Servicios (servicios)
```
Nombre: Servicios
Slug: servicios
Nombre singular: Servicio
Público: Sí
Hierarchical: No
Has Archive: Sí
Rewrite Slug: servicios
```

#### Proyectos (proyectos)
```
Nombre: Proyectos  
Slug: proyectos
Nombre singular: Proyecto
Público: Sí
Hierarchical: No
Has Archive: Sí
Rewrite Slug: proyectos
```

#### Testimonios (testimonios)
```
Nombre: Testimonios
Slug: testimonios
Nombre singular: Testimonio
Público: Sí
Hierarchical: No
Has Archive: Sí
Rewrite Slug: testimonios
```

## 🔧 4. Configuración de ACF (Versión Gratuita)

### IMPORTANTE: Limitaciones de la Versión Gratuita

- ❌ **Sin campos repetidores**: No se pueden usar Repeater Fields
- ❌ **Sin galería nativa**: Usar múltiples campos de imagen
- ❌ **Sin campos flexibles**: Solo campos básicos
- ✅ **Campos disponibles**: Text, Textarea, Number, Email, URL, Image, Select, Radio, Checkbox, True/False

### Grupo de Campos para Servicios

1. **Crear nuevo Field Group**
   - Nombre: "Campos de Servicio"
   - Ubicación: Post Type = servicios

2. **Campos básicos**:
   ```
   service_icon (Text) - Icono del servicio
   service_description (Textarea) - Descripción detallada
   service_price_range (Text) - Rango de precios
   project_category (Text) - Categoría de proyectos relacionados
   ```

3. **Características del servicio** (campos individuales):
   ```
   service_feature_1 (Text) - Característica 1
   service_feature_2 (Text) - Característica 2  
   service_feature_3 (Text) - Característica 3
   service_feature_4 (Text) - Característica 4
   service_feature_5 (Text) - Característica 5
   ```

4. **Imágenes del servicio** (campos individuales):
   ```
   service_image_1 (Image) - Imagen 1
   service_image_2 (Image) - Imagen 2
   service_image_3 (Image) - Imagen 3
   service_image_4 (Image) - Imagen 4
   ```

### Grupo de Campos para Proyectos

1. **Crear nuevo Field Group**
   - Nombre: "Campos de Proyecto"
   - Ubicación: Post Type = proyectos

2. **Campos**:
   ```
   project_category_select (Select) - Categoría del proyecto
     Opciones: pool|Piscinas, concrete|Concreto, cleaning|Limpieza, technical|Técnico
   project_location (Text) - Ubicación del proyecto
   project_duration (Text) - Duración del proyecto
   project_client (Text) - Cliente (opcional)
   project_before_image (Image) - Imagen antes
   project_after_image (Image) - Imagen después
   ```

3. **Galería del proyecto** (campos individuales):
   ```
   project_image_1 (Image) - Imagen de galería 1
   project_image_2 (Image) - Imagen de galería 2
   project_image_3 (Image) - Imagen de galería 3
   project_image_4 (Image) - Imagen de galería 4
   project_image_5 (Image) - Imagen de galería 5
   project_image_6 (Image) - Imagen de galería 6
   ```

### Grupo de Campos para Testimonios

1. **Crear nuevo Field Group**
   - Nombre: "Campos de Testimonio"
   - Ubicación: Post Type = testimonios

2. **Campos**:
   ```
   client_name (Text) - Nombre del cliente
   client_location (Text) - Ubicación del cliente
   rating (Number) - Calificación (1-5)
   testimonial_image (Image) - Foto del cliente
   service_category (Select) - Categoría del servicio
     Opciones: pool|Piscinas, concrete|Concreto, cleaning|Limpieza, technical|Técnico
   ```

## 🔗 5. Configuración de API REST

### Habilitar API REST para Custom Post Types

En Custom Post Type UI, editar cada CPT y marcar:
- ✅ Show in REST API: True
- ✅ REST API Base Slug: (usar el slug del CPT)

### Verificar endpoints disponibles:

```
http://renovalink.local/wp-json/wp/v2/servicios
http://renovalink.local/wp-json/wp/v2/proyectos  
http://renovalink.local/wp-json/wp/v2/testimonios
http://renovalink.local/wp-json/wp/v2/media/{id}
```

## 📊 6. Contenido de Ejemplo

### Crear Servicios de Ejemplo:

#### 1. Remodelación de Piscinas
```
Título: Remodelación de Piscinas
Slug: pool-remodeling
Contenido: Servicios profesionales de renovación y remodelación de piscinas...

ACF:
- service_icon: pool
- service_description: Transformamos piscinas antiguas en espacios modernos...
- service_feature_1: Renovación completa de azulejos
- service_feature_2: Sistemas de filtración modernos
- service_feature_3: Iluminación LED subacuática
- service_feature_4: Acabados antideslizantes
- service_price_range: $15,000 - $50,000
- project_category: pool
```

#### 2. Concreto y Pisos
```
Título: Concreto y Pisos
Slug: concrete-flooring
Contenido: Especialistas en pisos de concreto y acabados...

ACF:
- service_icon: concrete
- service_description: Instalación y acabado de pisos de concreto...
- service_feature_1: Pisos de concreto pulido
- service_feature_2: Tratamientos anti-manchas
- service_feature_3: Acabados decorativos
- service_feature_4: Reparación de grietas
- service_price_range: $5,000 - $25,000
- project_category: concrete
```

#### 3. Limpieza Residencial
```
Título: Limpieza Residencial
Slug: residential-cleaning
Contenido: Servicios profesionales de limpieza para hogares...

ACF:
- service_icon: cleaning
- service_description: Limpieza profunda y mantenimiento residencial...
- service_feature_1: Limpieza post-construcción
- service_feature_2: Limpieza de ventanas
- service_feature_3: Limpieza de alfombras
- service_feature_4: Desinfección profunda
- service_price_range: $100 - $500
- project_category: cleaning
```

#### 4. Soporte Técnico y Planos
```
Título: Soporte Técnico y Planos
Slug: technical-support
Contenido: Consultoría técnica y diseño de planos...

ACF:
- service_icon: technical
- service_description: Servicios de ingeniería y consultoría técnica...
- service_feature_1: Planos arquitectónicos
- service_feature_2: Cálculos estructurales
- service_feature_3: Permisos de construcción
- service_feature_4: Supervisión de obra
- service_price_range: $1,000 - $5,000
- project_category: technical
```

## ⚙️ 7. Variables de Entorno para Astro

Crear archivo `.env.local` en el proyecto Astro:

```env
# WordPress Local Configuration
WORDPRESS_API_URL=http://renovalink.local/wp-json
WORDPRESS_CUSTOM_API_URL=http://renovalink.local/wp-json/renovalink/v1
WP_MEDIA_URL=http://renovalink.local/wp-content/uploads
API_CACHE_TTL=300
NODE_ENV=development
```

## 🔍 8. Testing y Verificación

### Verificar endpoints de API:

```bash
# Servicios
curl http://renovalink.local/wp-json/wp/v2/servicios

# Proyectos  
curl http://renovalink.local/wp-json/wp/v2/proyectos

# Testimonios
curl http://renovalink.local/wp-json/wp/v2/testimonios

# Media por ID
curl http://renovalink.local/wp-json/wp/v2/media/1
```

### Verificar campos ACF:
- Los campos ACF deben aparecer en la respuesta JSON bajo la clave `acf`
- Las imágenes retornan IDs que deben ser consultados por separado

## 🚨 9. Limitaciones y Soluciones

### Problema: Sin campos repetidores
**Solución**: Usar campos numerados individuales (service_feature_1, service_feature_2, etc.)

### Problema: Sin galería nativa
**Solución**: Usar múltiples campos de imagen y agruparlos en el frontend

### Problema: Imágenes por ID
**Solución**: El sistema hace consultas adicionales para obtener URLs de imágenes

### Problema: Rendimiento
**Solución**: Cache implementado en el cliente de WordPress

## 📝 10. Notas Importantes

- **CORS**: Local puede requerir configuración CORS si hay problemas
- **SSL**: Local usa HTTP por defecto, no HTTPS
- **Performance**: La versión gratuita requiere más consultas a la API
- **Escalabilidad**: Considerar actualizar a ACF Pro para proyectos más grandes

## 🔧 11. Comandos Útiles

```bash
# Iniciar el proyecto Astro
npm run dev

# Verificar conexión con WordPress
npm run check

# Build de producción
npm run build
```

## 📞 Soporte

Si tienes problemas con Local:
- [Documentación oficial](https://localwp.com/help-docs/)
- [Community Forum](https://community.localwp.com/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)

---

Esta configuración está optimizada para usar únicamente plugins gratuitos y funcionar perfectamente con Local by Flywheel para desarrollo local de RenovaLink.