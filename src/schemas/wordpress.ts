import { z } from 'zod';

// Base WordPress schemas
export const WPMediaSchema = z.object({
  id: z.number(),
  url: z.string().url(),
  alt: z.string().default(''),
  title: z.string().default(''),
  width: z.number().optional(),
  height: z.number().optional(),
  mime_type: z.string().optional(),
});

export const WPMetaSchema = z.object({
  title: z.string(),
  description: z.string().optional(),
  keywords: z.string().optional(),
  og_image: WPMediaSchema.optional(),
});

// ACF Free version compatible fields only
export const WPACFFieldsSchema = z.object({
  // Hero fields (basic text and image fields only)
  hero_title: z.string().optional(),
  hero_subtitle: z.string().optional(),
  hero_description: z.string().optional(),
  hero_image: z.number().optional(), // ACF returns image ID in free version
  hero_cta_text: z.string().optional(),
  hero_cta_link: z.string().optional(),
  
  // Service fields (no gallery arrays, use individual image fields)
  service_icon: z.number().optional(), // Image ID
  short_description: z.string().optional(),
  service_feature_1: z.string().optional(),
  service_feature_2: z.string().optional(),
  service_feature_3: z.string().optional(),
  service_feature_4: z.string().optional(),
  service_feature_5: z.string().optional(),
  service_image_1: z.union([z.number(), z.string()]).optional(),
  service_image_2: z.union([z.number(), z.string()]).optional(),
  service_image_3: z.union([z.number(), z.string()]).optional(),
  service_image_4: z.union([z.number(), z.string()]).optional(),
  cta_text: z.string().optional(),
  cta_link: z.string().optional(),
  project_category: z.string().optional(),
  
  // Project fields (individual image fields instead of gallery)
  project_category_select: z.string().optional(),
  project_location: z.string().optional(),
  project_duration: z.string().optional(),
  project_before_image: z.union([z.number(), z.string()]).optional(),
  project_after_image: z.union([z.number(), z.string()]).optional(),
  project_image_1: z.union([z.number(), z.string()]).optional(),
  project_image_2: z.union([z.number(), z.string()]).optional(),
  project_image_3: z.union([z.number(), z.string()]).optional(),
  project_image_4: z.union([z.number(), z.string()]).optional(),
  project_image_5: z.union([z.number(), z.string()]).optional(),
  project_image_6: z.union([z.number(), z.string()]).optional(),
  
  // Testimonial fields
  client_name: z.string().optional(),
  client_location: z.string().optional(),
  rating: z.number().min(1).max(5).optional(),
  testimonial_image: z.number().optional(),
  service_category: z.string().optional(),
});

export const WPPostSchema = z.object({
  id: z.number(),
  slug: z.string(),
  title: z.object({
    rendered: z.string(),
  }),
  content: z.object({
    rendered: z.string(),
  }),
  excerpt: z.object({
    rendered: z.string(),
  }),
  date: z.string(),
  modified: z.string(),
  status: z.enum(['publish', 'draft', 'private']),
  featured_media: z.number().optional(),
  acf: WPACFFieldsSchema.optional(),
  _embedded: z.object({
    'wp:featuredmedia': z.array(WPMediaSchema).optional(),
  }).optional(),
});

// Service specific schemas (ACF Free compatible)
export const ServiceSchema = WPPostSchema.extend({
  acf: z.object({
    service_icon: z.number().optional(), // Image ID
    short_description: z.string().optional(),
    service_feature_1: z.string().optional(),
    service_feature_2: z.string().optional(),
    service_feature_3: z.string().optional(),
    service_feature_4: z.string().optional(),
    service_feature_5: z.string().optional(),
    service_feature_description_1: z.string().optional(),
    service_feature_description_2: z.string().optional(),
    service_feature_description_3: z.string().optional(),
    service_feature_description_4: z.string().optional(),
    service_feature_description_5: z.string().optional(),
    service_image_1: z.union([z.number(), z.string()]).optional(),
    service_image_2: z.union([z.number(), z.string()]).optional(),
    service_image_3: z.union([z.number(), z.string()]).optional(),
    service_image_4: z.union([z.number(), z.string()]).optional(),
    cta_text: z.string().optional(),
    cta_link: z.string().optional(),
    project_category: z.string().optional(),
  }).optional(),
  acf_fields: z.record(z.any()).optional(), // For full ACF field objects
});

// Project specific schemas (ACF Free compatible)
export const ProjectSchema = WPPostSchema.extend({
  acf: z.object({
    project_type: z.string().optional(), // Project Type field
    project_location: z.string().optional(),
    project_duration: z.string().optional(),
    related_service: z.union([z.number(), z.string()]).optional(), // Related Service field - can be ID or string
    project_before_image: z.union([z.number(), z.string()]).optional(), // Image ID or empty string
    project_after_image: z.union([z.number(), z.string()]).optional(), // Image ID or empty string
    project_image_1: z.union([z.number(), z.string()]).optional(),
    project_image_2: z.union([z.number(), z.string()]).optional(),
    project_image_3: z.union([z.number(), z.string()]).optional(),
    project_image_4: z.union([z.number(), z.string()]).optional(),
    project_image_5: z.union([z.number(), z.string()]).optional(),
    project_image_6: z.union([z.number(), z.string()]).optional(),
    project_client: z.string().optional(),
  }).optional(),
  acf_fields: z.record(z.any()).optional(), // For full ACF field objects
});

// Testimonial specific schemas (ACF Free compatible)
export const TestimonialSchema = WPPostSchema.extend({
  acf: z.object({
    client_name: z.string(),
    client_location: z.string(),
    rating: z.number().min(1).max(5),
    testimonial_image: z.number().optional(), // Image ID
    service_category: z.enum(['pool', 'concrete', 'cleaning', 'technical']).optional(),
  }).optional(),
});

// API Response schemas
export const WPAPIResponseSchema = z.object({
  data: z.array(WPPostSchema),
  total: z.number(),
  total_pages: z.number(),
});

export const WPServiceResponseSchema = z.object({
  data: z.array(ServiceSchema),
  total: z.number(),
  total_pages: z.number(),
});

export const WPProjectResponseSchema = z.object({
  data: z.array(ProjectSchema),
  total: z.number(),
  total_pages: z.number(),
});

export const WPTestimonialResponseSchema = z.object({
  data: z.array(TestimonialSchema),
  total: z.number(),
  total_pages: z.number(),
});

// Company information schema
export const CompanyInfoSchema = z.object({
  name: z.string(),
  description: z.string(),
  phone: z.string(),
  email: z.string().email(),
  address: z.string(),
  social_media: z.object({
    facebook: z.string().url().optional(),
    instagram: z.string().url().optional(),
    linkedin: z.string().url().optional(),
    twitter: z.string().url().optional(),
  }).optional(),
  business_hours: z.array(z.object({
    day: z.string(),
    hours: z.string(),
  })).optional(),
  license_info: z.object({
    license_number: z.string().optional(),
    certifications: z.array(z.string()).optional(),
  }).optional(),
});

// Contact form schema
export const ContactFormSchema = z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  email: z.string().email('Please enter a valid email address'),
  phone: z.string().min(10, 'Please enter a valid phone number'),
  service: z.enum(['pool', 'concrete', 'cleaning', 'technical', 'other']),
  message: z.string().min(10, 'Message must be at least 10 characters'),
  preferred_contact: z.enum(['phone', 'email']).optional(),
  project_timeline: z.enum(['immediate', '1-3-months', '3-6-months', 'planning']).optional(),
});

// Error schema
export const APIErrorSchema = z.object({
  code: z.string(),
  message: z.string(),
  data: z.object({
    status: z.number(),
  }).optional(),
});

// Type exports
export type WPMedia = z.infer<typeof WPMediaSchema>;
export type WPPost = z.infer<typeof WPPostSchema>;
export type Service = z.infer<typeof ServiceSchema>;
export type Project = z.infer<typeof ProjectSchema>;
export type Testimonial = z.infer<typeof TestimonialSchema>;
export type CompanyInfo = z.infer<typeof CompanyInfoSchema>;
export type ContactForm = z.infer<typeof ContactFormSchema>;
export type APIError = z.infer<typeof APIErrorSchema>;