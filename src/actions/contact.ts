import { defineAction } from "astro:actions";
import { z } from "astro:content";
import nodemailer from "nodemailer";

// Schema de validación para el formulario de contacto
export const contactFormSchema = z.object({
  name: z.string().min(2, "Name must be at least 2 characters"),
  email: z.string().email("Invalid email address"),
  phone: z.string().min(10, "Phone number must be at least 10 digits"),
  service: z.enum(["pool", "concrete", "cleaning", "technical", "other"]),
  timeline: z
    .enum(["immediate", "1-3-months", "3-6-months", "planning"])
    .optional(),
  message: z.string().min(10, "Message must be at least 10 characters"),
  preferred_contact: z.enum(["phone", "email"]).optional().default("phone"),
});

// Configuración del transportador de email
function createEmailTransporter() {
  return nodemailer.createTransport({
    service: "gmail",
    auth: {
      user: process.env.GMAIL_USER,
      pass: process.env.GMAIL_APP_PASSWORD, // App Password, no la contraseña normal
    },
  });
}

// Acción para enviar el formulario de contacto
export const sendContactForm = defineAction({
  accept: "form",
  input: contactFormSchema,
  handler: async (input) => {
    try {
      // Crear el transportador
      const transporter = createEmailTransporter();

      // Mapear servicios para mejor legibilidad
      const serviceMap = {
        pool: "Pool Remodeling",
        concrete: "Concrete & Flooring",
        cleaning: "Residential Cleaning",
        technical: "Technical Support & Plans",
        other: "Other / Multiple Services",
      };

      const timelineMap = {
        immediate: "As soon as possible",
        "1-3-months": "1-3 months",
        "3-6-months": "3-6 months",
        planning: "Still planning",
      };

      // Formatear el contenido del email
      const emailContent = `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9fafb;">
          <div style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Nueva Consulta - RenovaLink</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Formulario de contacto del sitio web</p>
          </div>
          
          <div style="background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h2 style="color: #1f2937; margin-top: 0; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px;">Información del Cliente</h2>
            
            <div style="margin-bottom: 20px;">
              <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">👤 Nombre Completo</h3>
              <p style="margin: 0; padding: 10px; background: #f3f4f6; border-radius: 5px; font-weight: 500;">${input.name}</p>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
              <div style="flex: 1;">
                <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">📧 Email</h3>
                <p style="margin: 0; padding: 10px; background: #f3f4f6; border-radius: 5px;">
                  <a href="mailto:${input.email}" style="color: #2563eb; text-decoration: none;">${input.email}</a>
                </p>
              </div>
              
              <div style="flex: 1;">
                <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">📱 Teléfono</h3>
                <p style="margin: 0; padding: 10px; background: #f3f4f6; border-radius: 5px;">
                  <a href="tel:${input.phone}" style="color: #2563eb; text-decoration: none;">${input.phone}</a>
                </p>
              </div>
            </div>

            <h2 style="color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; margin-top: 30px;">Detalles del Proyecto</h2>
            
            <div style="margin-bottom: 20px;">
              <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">🔧 Servicio Solicitado</h3>
              <p style="margin: 0; padding: 10px; background: #dbeafe; border-radius: 5px; color: #1e40af; font-weight: 500;">${serviceMap[input.service]}</p>
            </div>

            ${
              input.timeline
                ? `
            <div style="margin-bottom: 20px;">
              <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">⏰ Timeline del Proyecto</h3>
              <p style="margin: 0; padding: 10px; background: #f3f4f6; border-radius: 5px;">${timelineMap[input.timeline]}</p>
            </div>
            `
                : ""
            }

            <div style="margin-bottom: 20px;">
              <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">💬 Detalles del Proyecto</h3>
              <div style="padding: 15px; background: #f9fafb; border-left: 4px solid #2563eb; border-radius: 5px;">
                <p style="margin: 0; line-height: 1.6; white-space: pre-wrap;">${input.message}</p>
              </div>
            </div>

            <div style="margin-bottom: 20px;">
              <h3 style="color: #374151; margin: 0 0 8px 0; font-size: 16px;">📞 Método de Contacto Preferido</h3>
              <p style="margin: 0; padding: 10px; background: #f3f4f6; border-radius: 5px; text-transform: capitalize;">${input.preferred_contact}</p>
            </div>

            <div style="margin-top: 30px; padding: 20px; background: #ecfdf5; border-radius: 8px; border: 1px solid #d1fae5;">
              <h3 style="color: #065f46; margin: 0 0 10px 0; font-size: 16px;">📋 Próximos Pasos</h3>
              <ul style="margin: 0; padding-left: 20px; color: #047857;">
                <li>Responder al cliente dentro de 24 horas</li>
                <li>Programar consulta ${input.preferred_contact === "phone" ? "telefónica" : "por email"}</li>
                <li>Preparar estimado detallado si es necesario</li>
              </ul>
            </div>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-center; color: #6b7280; font-size: 14px;">
              <p style="margin: 0;">Enviado desde el formulario de contacto de RenovaLink.com</p>
              <p style="margin: 5px 0 0 0;">Fecha: ${new Date().toLocaleString("es-ES", { timeZone: "America/New_York" })}</p>
            </div>
          </div>
        </div>
      `;

      // Configurar el email
      const mailOptions = {
        from: {
          name: "RenovaLink Website",
          address: process.env.GMAIL_USER!,
        },
        to: process.env.COMPANY_EMAIL!, // Email de la empresa
        subject: `💼 Nueva Consulta: ${serviceMap[input.service]} - ${input.name}`,
        html: emailContent,
        replyTo: input.email, // Para que puedan responder directamente al cliente
      };

      // Enviar el email
      const info = await transporter.sendMail(mailOptions);

      console.log("Email enviado exitosamente:", info.messageId);

      return {
        success: true,
        message:
          "Tu mensaje ha sido enviado exitosamente. Te contactaremos dentro de 24 horas.",
        messageId: info.messageId,
      };
    } catch (error) {
      console.error("Error enviando email:", error);

      return {
        success: false,
        message:
          "Hubo un error enviando tu mensaje. Por favor intenta de nuevo o llámanos directamente.",
        error: error instanceof Error ? error.message : "Error desconocido",
      };
    }
  },
});
