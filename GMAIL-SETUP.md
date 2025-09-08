# Configuración de Gmail para Formulario de Contacto

## ✅ Implementación Completada

Se ha implementado un sistema completo de envío de emails usando Astro Actions y Gmail SMTP.

## 📋 Configuración Requerida

### 1. Configurar Gmail App Password

Para usar Gmail como SMTP, **NO puedes usar tu contraseña normal**. Necesitas crear una **App Password**:

#### Pasos para crear App Password:

1. **Habilitar 2FA en tu cuenta Gmail**:
   - Ve a [myaccount.google.com](https://myaccount.google.com)
   - Sección "Seguridad" → "Verificación en 2 pasos"
   - Sigue los pasos para habilitar 2FA

2. **Crear App Password**:
   - Ve a [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
   - Selecciona "Correo" y "Otro (nombre personalizado)"
   - Escribe "RenovaLink Website" como nombre
   - Google generará una contraseña de 16 caracteres como: `abcd efgh ijkl mnop`

### 2. Configurar Variables de Entorno

Actualiza tu archivo `.env.development` y `.env.production`:

```bash
# Email Configuration
GMAIL_USER=tu-email@gmail.com
GMAIL_APP_PASSWORD=abcd-efgh-ijkl-mnop
COMPANY_EMAIL=info@renovalink.com
```

**Importante**: 
- `GMAIL_USER`: Tu email de Gmail que enviará los emails
- `GMAIL_APP_PASSWORD`: La App Password generada (sin espacios)
- `COMPANY_EMAIL`: El email donde quieres recibir los formularios

### 3. Configurar emails de ejemplo

Puedes usar estas combinaciones:

**Opción 1: Gmail personal**
```bash
GMAIL_USER=tu-personal@gmail.com
GMAIL_APP_PASSWORD=tu-app-password-generada
COMPANY_EMAIL=tu-personal@gmail.com  # Recibes en el mismo email
```

**Opción 2: Email de empresa**
```bash
GMAIL_USER=contacto@renovalink.com  # Si tienes Gmail Workspace
GMAIL_APP_PASSWORD=tu-app-password-generada
COMPANY_EMAIL=info@renovalink.com   # Recibes en email diferente
```

## 🚀 Funcionalidades Implementadas

### ✅ Validación Completa
- Validación frontend y backend con Zod
- Validación de email, teléfono, y campos requeridos
- Mensajes de error específicos por campo

### ✅ Email Template Profesional
- Diseño HTML responsivo
- Información del cliente organizada
- Detalles del proyecto claramente mostrados
- Enlaces directos para llamar/email al cliente

### ✅ Experiencia de Usuario
- Loading states durante envío
- Mensajes de éxito/error claros
- Formulario se resetea después de envío exitoso
- Formato automático de número de teléfono

### ✅ Funcionalidades de Email
- Reply-to configurado al email del cliente
- Subject line descriptivo con tipo de servicio
- Timestamp en zona horaria de Florida
- Información completa para seguimiento

## 🔧 Archivos Modificados

```
src/
├── actions/
│   ├── index.ts           # ✅ Exporta todas las actions
│   └── contact.ts         # ✅ Action para envío de emails
├── components/
│   └── ContactCTA.astro   # ✅ Actualizado para usar Actions
└── .env.development       # ✅ Variables de entorno agregadas

astro.config.mjs          # ✅ Cambiado a output: 'hybrid'
package.json              # ✅ nodemailer agregado
```

## 🧪 Cómo Probar

1. **Configurar variables de entorno** con tus credenciales reales
2. **Iniciar servidor**: `npm run dev`
3. **Llenar formulario** en `/contacto`
4. **Verificar email** en la dirección configurada en `COMPANY_EMAIL`

## 📧 Ejemplo de Email Recibido

Los emails llegan con este formato:

**Subject**: 💼 Nueva Consulta: Pool Remodeling - Juan Pérez

**Content**: Email HTML profesional con:
- Información completa del cliente
- Detalles del proyecto solicitado
- Botones para responder directamente
- Próximos pasos sugeridos

## ⚡ Comandos de Desarrollo

```bash
# Desarrollo (con emails funcionales)
npm run dev

# Build para producción
npm run build

# Preview de producción
npm run preview
```

## 🔐 Seguridad

- ✅ Validación server-side con Zod
- ✅ App Passwords en lugar de contraseña normal
- ✅ Variables de entorno para credenciales
- ✅ Sanitización de inputs
- ✅ Rate limiting recomendado para producción

## 📱 Compatibilidad

- ✅ Funciona con Gmail personal
- ✅ Funciona con Gmail Workspace (G Suite)
- ✅ Compatible con otros proveedores SMTP si cambias la config
- ✅ Responsive en todos los dispositivos

## 🚨 Troubleshooting

**Error "Invalid login"**: 
- Verifica que la App Password esté correcta
- Confirma que 2FA esté habilitado

**Error "Less secure app"**:
- Usa App Password, no la contraseña normal

**Emails no llegan**:
- Verifica COMPANY_EMAIL
- Revisa spam/junk folder
- Confirma que Gmail tenga espacio disponible

## 🌟 Próximos Pasos Opcionales

1. **Rate Limiting**: Agregar límites de envío por IP
2. **Captcha**: Integrar Google reCAPTCHA para mayor seguridad
3. **Analytics**: Tracking de conversiones del formulario
4. **Auto-respuesta**: Email automático de confirmación al cliente
5. **Webhook**: Integrar con CRM o herramientas de gestión