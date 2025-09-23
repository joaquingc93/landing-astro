# Guía de Creación de Contenido para RenovaLink

Esta guía te explica **paso a paso** cómo crear el contenido dinámico necesario para RenovaLink en WordPress usando Local.

## 🎯 **ENFOQUE: Solo Custom Post Types**

Tu frontend de Astro ya tiene todas las páginas necesarias (`/`, `/contacto`, `/servicios`). Solo necesitas crear el **contenido dinámico** que poblará los componentes.

## ⚠️ **IMPORTANTE: ACF VERSIÓN GRATUITA**

Esta guía está actualizada para usar **Advanced Custom Fields versión GRATUITA**. Los campos han sido convertidos de la versión Pro:

### **Cambios principales:**

- ❌ **Repeater** → ✅ **Campos individuales** (service_feature_1, service_feature_2, etc.)
- ❌ **Gallery** → ✅ **Campos image separados** (service_image_1, service_image_2, etc.)
- ❌ **Date Picker** → ✅ **Campo text** (fecha como texto o YYYY-MM-DD)
- ❌ **Range** → ✅ **Campo select** (opciones predefinidas)

### **Nombres de campos actualizados:**

- `service_description` → `short_description`
- `service_features` → `service_feature_1`, `service_feature_2`, etc.
- `service_gallery` → `service_image_1`, `service_image_2`, etc.
- `project_category_select` → `project_type`
- `service_category` → `service_provided`

## 📋 **ESTRUCTURA DE CONTENIDO**

### ✅ **PÁGINAS ASTRO YA IMPLEMENTADAS:**

- 🏠 **Homepage** (`/`) → Landing completa con todos los componentes
- 📞 **Contacto** (`/contacto`) → Formulario y información de contacto
- 🔧 **Servicios** (`/servicios`) → Lista de servicios
- 🔧 **Detalle Servicio** (`/servicios/[slug]`) → Página individual de cada servicio

### 🔧 **CUSTOM POST TYPES A CREAR:**

1. **Servicios** (`servicios`) → Pobla `ServicesGrid.astro`
2. **Proyectos** (`proyectos`) → Pobla `ProjectGallery.astro`

---

## 🔧 3. CREAR SERVICIOS (Custom Post Type)

Ve a **Servicios > Añadir nuevo** y crea estos 4 servicios:

### 🏊‍♂️ Servicio 1: Remodelación de Piscinas

```
INFORMACIÓN BÁSICA:
Título: Remodelación de Piscinas
Slug: pool-remodeling

CONTENIDO PRINCIPAL:
Transformamos piscinas antiguas en oasis modernos. Renovación completa de azulejos,
sistemas de filtración avanzados, iluminación LED subacuática y acabados
antideslizantes de última generación.

Nuestro equipo especializado maneja desde reparaciones menores hasta
remodelaciones completas, siempre con materiales de primera calidad
y técnicas profesionales certificadas.

CAMPOS ACF:
✅ service_icon: [Subir imagen/icono que represente piscinas]
✅ short_description: Renovación profesional de piscinas con tecnología moderna y acabados de lujo
✅ service_feature_1: Renovación completa de azulejos y revestimientos
✅ service_feature_2: Sistemas de filtración y purificación modernos
✅ service_feature_3: Iluminación LED subacuática personalizable
✅ service_feature_4: Acabados antideslizantes y de seguridad
✅ service_feature_5: Mantenimiento y garantía post-instalación
✅ price_range: $15,000 - $50,000
✅ service_duration: 2-4 semanas
✅ warranty_info: Garantía de 5 años en materiales y mano de obra
✅ cta_text: Solicitar Cotización de Piscina
✅ cta_link: #contact
✅ service_image_1: [Subir imagen de piscina antes - REQUERIDA]
✅ service_image_2: [Subir imagen de piscina después - REQUERIDA]
✅ service_image_3: [Subir imagen de azulejos modernos]
✅ service_image_4: [Subir imagen de iluminación LED]

IMAGEN DESTACADA: Imagen principal de una piscina remodelada
ESTADO: Publicado
```

### 🏗️ Servicio 2: Concreto y Pisos

```
INFORMACIÓN BÁSICA:
Título: Concreto y Pisos
Slug: concrete-flooring

CONTENIDO PRINCIPAL:
Especialistas en instalación y acabado de pisos de concreto pulido,
tratamientos anti-manchas, acabados decorativos y reparación profesional
de grietas y daños estructurales.

Utilizamos técnicas avanzadas de pulido y sellado que garantizan
durabilidad y estética superior para espacios residenciales y comerciales.

CAMPOS ACF:
✅ service_icon: [Subir imagen/icono que represente concreto]
✅ short_description: Instalación y acabado profesional de pisos de concreto con tratamientos especializados
✅ service_feature_1: Pisos de concreto pulido de alta calidad
✅ service_feature_2: Tratamientos anti-manchas y selladores
✅ service_feature_3: Acabados decorativos y texturas personalizadas
✅ service_feature_4: Reparación profesional de grietas y daños
✅ service_feature_5: Mantenimiento preventivo y garantía
✅ price_range: $5,000 - $25,000
✅ service_duration: 1-2 semanas
✅ warranty_info: Garantía de 3 años en acabados
✅ cta_text: Solicitar Cotización de Concreto
✅ cta_link: #contact
✅ service_image_1: [Subir imagen de piso de concreto pulido - REQUERIDA]
✅ service_image_2: [Subir imagen de acabado decorativo - REQUERIDA]
✅ service_image_3: [Subir imagen de reparación de grietas]
✅ service_image_4: [Subir imagen de sellado profesional]

IMAGEN DESTACADA: Imagen de piso de concreto pulido brillante
ESTADO: Publicado
```

### 🧽 Servicio 3: Limpieza Residencial

```
INFORMACIÓN BÁSICA:
Título: Limpieza Residencial
Slug: residential-cleaning

CONTENIDO PRINCIPAL:
Servicios profesionales de limpieza profunda y mantenimiento residencial.
Especialistas en limpieza post-construcción, ventanas, alfombras y
desinfección profunda con productos ecológicos certificados.

Nuestro equipo capacitado utiliza equipos profesionales y técnicas
especializadas para garantizar resultados impecables en cada servicio.

CAMPOS ACF:
✅ service_icon: [Subir imagen/icono que represente limpieza]
✅ short_description: Servicios profesionales de limpieza profunda y mantenimiento residencial especializado
✅ service_feature_1: Limpieza post-construcción y remodelación
✅ service_feature_2: Limpieza profesional de ventanas y cristales
✅ service_feature_3: Limpieza y desinfección profunda de alfombras
✅ service_feature_4: Desinfección profunda con productos ecológicos
✅ service_feature_5: Mantenimiento regular programado
✅ price_range: $100 - $500
✅ service_duration: 1-3 días
✅ warranty_info: Garantía de satisfacción 100%
✅ cta_text: Solicitar Servicio de Limpieza
✅ cta_link: #contact
✅ service_image_1: [Subir imagen de limpieza post-construcción - REQUERIDA]
✅ service_image_2: [Subir imagen de limpieza de ventanas - REQUERIDA]
✅ service_image_3: [Subir imagen de limpieza de alfombras]
✅ service_image_4: [Subir imagen de productos ecológicos]

IMAGEN DESTACADA: Imagen de espacio limpio post-construcción
ESTADO: Publicado
```

### 📋 Servicio 4: Soporte Técnico y Planos

```
INFORMACIÓN BÁSICA:
Título: Soporte Técnico y Planos
Slug: technical-support

CONTENIDO PRINCIPAL:
Servicios de ingeniería y consultoría técnica especializada. Desarrollo de
planos arquitectónicos, cálculos estructurales, gestión de permisos de
construcción y supervisión técnica profesional de obras.

Nuestro equipo de ingenieros certificados garantiza cumplimiento normativo
y excelencia técnica en cada proyecto.

CAMPOS ACF:
✅ service_icon: [Subir imagen/icono que represente ingeniería/planos]
✅ short_description: Consultoría técnica e ingeniería especializada con planos y supervisión profesional
✅ service_feature_1: Desarrollo de planos arquitectónicos detallados
✅ service_feature_2: Cálculos estructurales y análisis técnico
✅ service_feature_3: Gestión completa de permisos de construcción
✅ service_feature_4: Supervisión técnica profesional de obras
✅ service_feature_5: Consultoría en cumplimiento normativo
✅ price_range: $1,000 - $5,000
✅ service_duration: 1-3 semanas
✅ warranty_info: Garantía profesional en todos los cálculos
✅ cta_text: Solicitar Consultoría Técnica
✅ cta_link: #contact
✅ service_image_1: [Subir imagen de planos arquitectónicos - REQUERIDA]
✅ service_image_2: [Subir imagen de cálculos estructurales - REQUERIDA]
✅ service_image_3: [Subir imagen de permisos]
✅ service_image_4: [Subir imagen de supervisión de obra]

IMAGEN DESTACADA: Imagen de planos técnicos y herramientas de ingeniería
ESTADO: Publicado
```

---

## 🏗️ 4. CREAR PROYECTOS (Portfolio)

Ve a **Proyectos > Añadir nuevo** y crea ejemplos como estos:

### Proyecto Ejemplo 1: Remodelación Piscina Coral Gables

```
INFORMACIÓN BÁSICA:
Título: Remodelación Completa Piscina Coral Gables
Slug: piscina-coral-gables-2024

CONTENIDO PRINCIPAL:
Transformación completa de piscina residencial en Coral Gables.
Renovación de azulejos, instalación de sistema LED, nuevos acabados
antideslizantes y modernización del sistema de filtración.

Proyecto completado en 4 semanas con materiales premium y garantía de 5 años.

CAMPOS ACF:
✅ project_type: pool (seleccionar de lista desplegable)
✅ project_location: Coral Gables, FL
✅ project_duration: 4 semanas
✅ project_size: Piscina residencial 30x15 pies
✅ before_image: [Subir imagen ANTES de la remodelación - REQUERIDA]
✅ after_image: [Subir imagen DESPUÉS de la remodelación - REQUERIDA]
✅ related_service: [Seleccionar servicio "Remodelación de Piscinas"]
✅ client_testimonial: "Superó nuestras expectativas, trabajo impecable"
✅ project_challenges: Acceso limitado y trabajo en temporada de lluvias
✅ project_image_1: [Subir imagen detalle azulejos]
✅ project_image_2: [Subir imagen iluminación LED]
✅ project_image_3: [Subir imagen acabados]
✅ project_image_4: [Subir imagen sistema filtración]
✅ project_image_5: [Subir imagen vista general terminada]

IMAGEN DESTACADA: Mejor imagen del proyecto terminado
ESTADO: Publicado
```

### Proyecto Ejemplo 2: Piso Concreto Pulido Miami

```
INFORMACIÓN BÁSICA:
Título: Piso de Concreto Pulido Residencial Miami
Slug: piso-concreto-miami-2024

CONTENIDO PRINCIPAL:
Instalación de 2,500 pies cuadrados de piso de concreto pulido en
residencia moderna de Miami. Incluye tratamiento anti-manchas,
acabado brillante y sellado profesional.

CAMPOS ACF:
✅ project_type: concrete (seleccionar de lista desplegable)
✅ project_location: Miami, FL
✅ project_duration: 2 semanas
✅ project_size: 2,500 pies cuadrados
✅ before_image: [Subir imagen piso original - REQUERIDA]
✅ after_image: [Subir imagen piso terminado - REQUERIDA]
✅ related_service: [Seleccionar servicio "Concreto y Pisos"]
✅ client_testimonial: "El acabado quedó espectacular, muy profesionales"
✅ project_challenges: Nivelación de piso irregular existente
✅ project_image_1: [Subir imagen proceso pulido]
✅ project_image_2: [Subir imagen aplicación sellador]
✅ project_image_3: [Subir imagen acabado final]
✅ project_image_4: [Subir imagen detalle brillante]
✅ project_image_5: [Imagen adicional opcional]

ESTADO: Publicado
```

---

## 💬 5. CREAR TESTIMONIOS

Ve a **Testimonios > Añadir nuevo**:

### Testimonio Ejemplo 1

```
INFORMACIÓN BÁSICA:
Título: Testimonio María García - Remodelación Piscina
Slug: testimonio-maria-garcia

CONTENIDO PRINCIPAL:
"Excelente trabajo en la remodelación de nuestra piscina. El equipo de RenovaLink
fue muy profesional, cumplieron los tiempos prometidos y la calidad superó
nuestras expectativas. Recomendamos sus servicios al 100%."

CAMPOS ACF:
✅ client_name: María García
✅ client_location: Hialeah, FL
✅ service_provided: pool (seleccionar de lista desplegable)
✅ rating: 5
✅ testimonial_text: "Excelente trabajo en la remodelación de nuestra piscina..."
✅ client_photo: [Subir imagen de la cliente - opcional]
✅ project_photo: [Subir imagen del proyecto completado]
✅ testimonial_date: 2024-01-15 (o "Enero 2024")
✅ featured_testimonial: No (marcar solo testimonios destacados)

ESTADO: Publicado
```

### Testimonio Ejemplo 2

```
INFORMACIÓN BÁSICA:
Título: Testimonio Carlos Mendoza - Piso de Concreto
Slug: testimonio-carlos-mendoza

CONTENIDO PRINCIPAL:
"Increíble transformación de nuestros pisos. El concreto pulido quedó espectacular
y el proceso fue muy limpio y organizado. Definitivamente volveremos a contratarlos
para futuros proyectos."

CAMPOS ACF:
✅ client_name: Carlos Mendoza
✅ client_location: Doral, FL
✅ service_provided: concrete (seleccionar de lista desplegable)
✅ rating: 5
✅ testimonial_text: "Increíble transformación de nuestros pisos..."
✅ client_photo: [Subir imagen del cliente - opcional]
✅ project_photo: [Subir imagen del proyecto completado]
✅ testimonial_date: 2024-02-10 (o "Febrero 2024")
✅ featured_testimonial: Sí (marcar como testimonio destacado)

ESTADO: Publicado
```

---

## 📁 6. ORGANIZACIÓN DE IMÁGENES

### Preparar Imágenes en Media Library

1. **Ir a Medios > Biblioteca**
2. **Subir imágenes organizadas por categoría**:

```
📁 SERVICIOS/
├── 🏊‍♂️ pool/
│   ├── pool-before-1.jpg
│   ├── pool-after-1.jpg
│   ├── pool-tiles-modern.jpg
│   └── pool-led-lighting.jpg
├── 🏗️ concrete/
│   ├── concrete-polished-1.jpg
│   ├── concrete-sealing.jpg
│   └── concrete-decorative.jpg
├── 🧽 cleaning/
│   ├── cleaning-post-construction.jpg
│   └── cleaning-windows.jpg
└── 📋 technical/
    ├── architectural-plans.jpg
    └── structural-calculations.jpg

📁 PROYECTOS/
├── project-pool-before.jpg
├── project-pool-after.jpg
├── project-concrete-before.jpg
└── project-concrete-after.jpg

📁 TESTIMONIOS/
└── client-photos/ (opcional)
```

3. **Anotar los ID de cada imagen** que necesites para los campos ACF

---

## 🔗 7. CONFIGURAR MENÚS

### Menú Principal

1. **Ir a Apariencia > Menús**
2. **Crear menú "Menú Principal"**
3. **Agregar elementos**:
   ```
   🏠 Inicio (página)
   🔧 Servicios (página de archivo de servicios)
   🏗️ Proyectos (página de archivo de proyectos)
   💬 Testimonios (página de archivo de testimonios)
   📞 Contacto (página)
   ```
4. **Asignar a ubicación "Menú Principal"**

---

## ✅ 8. VERIFICAR TODO FUNCIONA

### Comprobar URLs:

- `renovalink.local/` → Página de inicio
- `renovalink.local/servicios/` → Lista de servicios
- `renovalink.local/servicios/pool-remodeling/` → Detalle servicio
- `renovalink.local/proyectos/` → Lista de proyectos
- `renovalink.local/testimonios/` → Lista de testimonios
- `renovalink.local/contacto/` → Página de contacto

### Comprobar API REST:

- `renovalink.local/wp-json/wp/v2/servicios` → JSON servicios
- `renovalink.local/wp-json/wp/v2/proyectos` → JSON proyectos
- `renovalink.local/wp-json/wp/v2/testimonios` → JSON testimonios

---

## 🎯 RESUMEN RÁPIDO

**TIENES QUE CREAR**:

✅ **3 Páginas normales**: Inicio, Contacto, Acerca de  
✅ **4 Servicios** (Custom Post Type): Pool, Concrete, Cleaning, Technical
✅ **4-6 Proyectos** (Custom Post Type): Ejemplos de trabajos realizados
✅ **4-6 Testimonios** (Custom Post Type): Reseñas de clientes
✅ **20-30 Imágenes**: Subidas a Media Library con IDs anotados

**TIEMPO ESTIMADO**: 2-3 horas para crear todo el contenido básico.

¿Te queda más claro ahora cómo crear el contenido? ¿Necesitas que detalle alguna parte específica?
