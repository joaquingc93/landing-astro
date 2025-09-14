import NodeCache from "node-cache";
import {
  WPPostSchema,
  ServiceSchema,
  ProjectSchema,
  TestimonialSchema,
  CompanyInfoSchema,
  APIErrorSchema,
  type WPPost,
  type Service,
  type Project,
  type Testimonial,
  type CompanyInfo,
  type APIError,
} from "@/schemas/wordpress";

// Cache configuration
const cache = new NodeCache({
  stdTTL: parseInt(process.env.API_CACHE_TTL || "300"), // 5 minutes default
  checkperiod: 120, // Check for expired keys every 2 minutes
  useClones: false,
});

// API Configuration for Local development
const WORDPRESS_API_URL =
  process.env.WORDPRESS_API_URL || "http://renovalinksite.local/wp-json";
const WORDPRESS_CUSTOM_API_URL =
  process.env.WORDPRESS_CUSTOM_API_URL ||
  "http://renovalinksite.local/wp-json/renovalink/v1";

// Helper functions for ACF Free version limitations
export const ACFHelpers = {
  // Convert individual feature fields to array
  getServiceFeatures(acf: any): string[] {
    const features = [];
    for (let i = 1; i <= 5; i++) {
      const feature = acf?.[`service_feature_${i}`];
      if (feature && feature.trim()) {
        features.push(feature);
      }
    }
    return features;
  },

  // Convert individual image fields to gallery array
  async getServiceGallery(acf: any): Promise<any[]> {
    const gallery = [];
    for (let i = 1; i <= 4; i++) {
      const imageId = acf?.[`service_image_${i}`];
      if (imageId) {
        try {
          const media = await wordpressClient.getMediaById(imageId);
          if (media) {
            gallery.push({
              id: media.id,
              url: media.source_url || media.url,
              alt: media.alt_text || "",
              title: media.title?.rendered || "",
              width: media.media_details?.width,
              height: media.media_details?.height,
              mime_type: media.mime_type,
            });
          }
        } catch (error) {
          console.warn(`Failed to fetch service image ${imageId}:`, error);
        }
      }
    }
    return gallery;
  },

  // Convert individual project image fields to gallery array
  async getProjectGallery(acf: any): Promise<any[]> {
    const gallery = [];
    for (let i = 1; i <= 6; i++) {
      const imageId = acf?.[`project_image_${i}`];
      if (imageId) {
        try {
          const media = await wordpressClient.getMediaById(imageId);
          if (media) {
            gallery.push({
              id: media.id,
              url: media.source_url || media.url,
              alt: media.alt_text || "",
              title: media.title?.rendered || "",
              width: media.media_details?.width,
              height: media.media_details?.height,
              mime_type: media.mime_type,
            });
          }
        } catch (error) {
          console.warn(`Failed to fetch project image ${imageId}:`, error);
        }
      }
    }
    return gallery;
  },

  // Get single media by ID for before/after images
  async getMediaByIdFormatted(imageId: number): Promise<any | null> {
    if (!imageId) return null;
    try {
      const media = await wordpressClient.getMediaById(imageId);
      if (media) {
        return {
          id: media.id,
          url: media.source_url || media.url,
          alt: media.alt_text || "",
          title: media.title?.rendered || "",
          width: media.media_details?.width,
          height: media.media_details?.height,
          mime_type: media.mime_type,
        };
      }
    } catch (error) {
      console.warn(`Failed to fetch media ${imageId}:`, error);
    }
    return null;
  },
};

// Error classes
export class WordPressAPIError extends Error {
  constructor(
    message: string,
    public code: string,
    public status?: number
  ) {
    super(message);
    this.name = "WordPressAPIError";
  }
}

export class WordPressNetworkError extends Error {
  constructor(
    message: string,
    public originalError: Error
  ) {
    super(message);
    this.name = "WordPressNetworkError";
  }
}

// Base API client
class WordPressClient {
  private baseUrl: string;
  private customUrl: string;

  constructor() {
    this.baseUrl = WORDPRESS_API_URL;
    this.customUrl = WORDPRESS_CUSTOM_API_URL;
  }

  private async fetchWithErrorHandling(
    url: string,
    options: RequestInit = {}
  ): Promise<Response> {
    try {
      const response = await fetch(url, {
        ...options,
        headers: {
          "Content-Type": "application/json",
          ...options.headers,
        },
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => ({
          code: "unknown_error",
          message: `HTTP ${response.status}`,
        }));

        const validatedError = APIErrorSchema.safeParse(errorData);
        if (validatedError.success) {
          throw new WordPressAPIError(
            validatedError.data.message,
            validatedError.data.code,
            response.status
          );
        } else {
          throw new WordPressAPIError(
            `API Error: ${response.status}`,
            "api_error",
            response.status
          );
        }
      }

      return response;
    } catch (error) {
      if (error instanceof WordPressAPIError) {
        throw error;
      }

      if (error instanceof TypeError && error.message.includes("fetch")) {
        throw new WordPressNetworkError(
          "Network connection failed. Please check if WordPress is running.",
          error
        );
      }

      throw new WordPressNetworkError(
        "Unexpected error occurred while fetching data",
        error as Error
      );
    }
  }

  private getCacheKey(endpoint: string, params?: Record<string, any>): string {
    const paramString = params ? JSON.stringify(params) : "";
    return `wp_${endpoint}_${paramString}`;
  }

  private async fetchFromAPI<T>(
    endpoint: string,
    schema: any,
    params?: Record<string, any>,
    useCustomAPI = false
  ): Promise<T[]> {
    const cacheKey = this.getCacheKey(endpoint, params);
    const cached = cache.get<T[]>(cacheKey);

    if (cached) {
      return cached;
    }

    const baseUrl = useCustomAPI ? this.customUrl : this.baseUrl;
    const searchParams = new URLSearchParams({
      _embed: "true",
      per_page: "100",
      ...params,
    });

    const url = `${baseUrl}/${endpoint}?${searchParams}`;

    const response = await this.fetchWithErrorHandling(url);
    const data = await response.json();

    // Validate each item in the array
    const validatedData = data
      .map((item: any) => {
        const result = schema.safeParse(item);
        if (!result.success) {
          console.warn(`Invalid data structure for ${endpoint}:`, result.error);
          return null;
        }
        return result.data;
      })
      .filter(Boolean);

    cache.set(cacheKey, validatedData);
    return validatedData;
  }

  // Public methods
  async getAllPosts(): Promise<WPPost[]> {
    return this.fetchFromAPI("wp/v2/posts", WPPostSchema, {
      status: "publish",
    });
  }

  async getPostBySlug(slug: string): Promise<WPPost | null> {
    const posts = await this.fetchFromAPI("wp/v2/posts", WPPostSchema, {
      slug,
      status: "publish",
    });
    return posts[0] || null;
  }

  async getAllServices(): Promise<Service[]> {
    console.log(
      "🌐 Fetching services from:",
      `${this.baseUrl}/wp/v2/servicios`
    );
    try {
      const services = await this.fetchFromAPI(
        "wp/v2/servicios",
        ServiceSchema,
        { status: "publish" }
      );
      console.log("✅ Services fetched successfully:", services.length);
      return services;
    } catch (error) {
      console.error("❌ Error fetching services:", error);
      throw error;
    }
  }

  async getServiceBySlug(slug: string): Promise<Service | null> {
    const services = await this.fetchFromAPI("wp/v2/servicios", ServiceSchema, {
      slug,
      status: "publish",
    });
    return services[0] || null;
  }

  async getAllProjects(): Promise<Project[]> {
    const projects = await this.fetchFromAPI("wp/v2/proyectos", ProjectSchema, {
      status: "publish",
    });

    // WordPress should return acf_fields with complete image data
    // If it doesn't, we'll enrich manually
    const enrichedProjects = await Promise.all(
      projects.map(async (project: any) => {
        // If we already have acf_fields with complete image objects, return as is
        if (
          project.acf_fields &&
          this.hasCompleteImageData(project.acf_fields)
        ) {
          return project;
        }

        // If we only have IDs in acf, fetch the complete image data
        if (project.acf) {
          const enrichedFields: any = { ...project.acf };

          for (let i = 1; i <= 6; i++) {
            const imageId = project.acf[`project_image_${i}`];
            if (imageId && typeof imageId === "number") {
              try {
                const media = await this.getMediaById(imageId);
                if (media) {
                  enrichedFields[`project_image_${i}`] = {
                    ID: media.id,
                    id: media.id,
                    title: media.title?.rendered || "",
                    filename: media.media_details?.file || "",
                    url: media.source_url || "",
                    alt: media.alt_text || "",
                    width: media.media_details?.width || 0,
                    height: media.media_details?.height || 0,
                    sizes: media.media_details?.sizes || {},
                    mime_type: media.mime_type || "",
                  };
                }
              } catch (error) {
                console.warn(
                  `Failed to enrich project image ${imageId}:`,
                  error
                );
              }
            }
          }

          // Set both acf and acf_fields to the enriched data
          return {
            ...project,
            acf: enrichedFields,
            acf_fields: enrichedFields,
          };
        }

        return project;
      })
    );

    return enrichedProjects;
  }

  private hasCompleteImageData(acfFields: any): boolean {
    // Check if we have at least one complete image object (not just ID)
    for (let i = 1; i <= 6; i++) {
      const imageField = acfFields[`project_image_${i}`];
      if (imageField && typeof imageField === "object" && imageField.url) {
        return true;
      }
    }
    return false;
  }

  async getProjectsByCategory(category: string): Promise<Project[]> {
    return this.fetchFromAPI("wp/v2/proyectos", ProjectSchema, {
      status: "publish",
      meta_key: "project_category",
      meta_value: category,
    });
  }

  async getProjectsByService(serviceSlug: string): Promise<Project[]> {
    // First, get the service by slug to get its ID
    const services = await this.fetchFromAPI("wp/v2/servicios", ServiceSchema, {
      status: "publish",
      slug: serviceSlug,
    });

    if (services.length === 0) {
      return [];
    }

    const serviceId = (services[0] as any).id;

    // Get all enriched projects
    const allProjects = await this.getAllProjects();

    // Filter projects where related_service ID matches the service ID
    return allProjects.filter((project) => {
      const relatedServiceId =
        project.acf?.related_service || project.acf_fields?.related_service;
      return (
        relatedServiceId &&
        (relatedServiceId === serviceId ||
          relatedServiceId.id === serviceId ||
          relatedServiceId.ID === serviceId)
      );
    });
  }

  async getAllTestimonials(): Promise<Testimonial[]> {
    return this.fetchFromAPI("wp/v2/testimonios", TestimonialSchema, {
      status: "publish",
    });
  }

  async getFeaturedTestimonials(): Promise<Testimonial[]> {
    return this.fetchFromAPI("wp/v2/testimonios", TestimonialSchema, {
      status: "publish",
      featured: "true",
    });
  }

  // Get media by ID - essential for ACF Free version
  async getMediaById(id: number): Promise<any | null> {
    const cacheKey = `media_${id}`;
    const cached = cache.get(cacheKey);

    if (cached) {
      return cached;
    }

    try {
      const response = await this.fetchWithErrorHandling(
        `${this.baseUrl}/wp/v2/media/${id}`
      );
      const media = await response.json();

      cache.set(cacheKey, media);
      return media;
    } catch (error) {
      console.warn(`Failed to fetch media ${id}:`, error);
      return null;
    }
  }

  async getCompanyInfo(): Promise<CompanyInfo | null> {
    const cacheKey = "company_info";
    const cached = cache.get<CompanyInfo>(cacheKey);

    if (cached) {
      return cached;
    }

    try {
      const response = await this.fetchWithErrorHandling(
        `${this.customUrl}/company-info`
      );
      const data = await response.json();

      const result = CompanyInfoSchema.safeParse(data);
      if (!result.success) {
        console.warn("Invalid company info data structure:", result.error);
        return null;
      }

      cache.set(cacheKey, result.data);
      return result.data;
    } catch (error) {
      console.warn("Failed to fetch company info:", error);
      return null;
    }
  }

  async getSiteConfig(): Promise<any> {
    const cacheKey = "site_config";
    const cached = cache.get(cacheKey);

    if (cached) {
      return cached;
    }

    try {
      const response = await this.fetchWithErrorHandling(
        `${this.customUrl}/site-config`
      );
      const data = await response.json();

      cache.set(cacheKey, data);
      return data;
    } catch (error) {
      console.warn("Failed to fetch site config:", error);
      return null;
    }
  }

  async getHeroContent(): Promise<any> {
    const cacheKey = "hero_content";
    const cached = cache.get(cacheKey);

    if (cached) {
      return cached;
    }

    try {
      const response = await this.fetchWithErrorHandling(
        `${this.customUrl}/hero-content`
      );
      const data = await response.json();

      cache.set(cacheKey, data);
      return data;
    } catch (error) {
      console.warn("Failed to fetch hero content:", error);
      // Return fallback hero content
      return {
        title: "Transform Your Space with RenovaLink",
        subtitle: "Premier Remodeling Services in Florida",
        description:
          "From pool renovations to structural engineering, we bring your vision to life with quality craftsmanship and certified expertise.",
        cta_text: "Get Free Estimate",
        cta_link: "/contacto",
      };
    }
  }

  // Cache management
  clearCache(): void {
    cache.flushAll();
  }

  getCacheStats(): { keys: number; hits: number; misses: number } {
    return cache.getStats();
  }

  // Health check
  async healthCheck(): Promise<boolean> {
    try {
      const response = await this.fetchWithErrorHandling(
        `${this.baseUrl}/wp/v2/posts?per_page=1`
      );
      return response.ok;
    } catch {
      return false;
    }
  }
}

// Export singleton instance
export const wordpressClient = new WordPressClient();

// Utility functions for components
export async function getStaticProps<T>(
  fetcher: () => Promise<T>,
  fallback: T
): Promise<T> {
  try {
    return await fetcher();
  } catch (error) {
    console.error("WordPress API error:", error);
    return fallback;
  }
}

export function createFallbackData() {
  return {
    services: [
      {
        id: 1,
        slug: "pool-remodeling",
        title: { rendered: "Pool Remodeling" },
        content: {
          rendered:
            "Transform your pool with lighting, waterfalls, and modern features.",
        },
        excerpt: { rendered: "Complete pool renovation services" },
        date: new Date().toISOString(),
        modified: new Date().toISOString(),
        status: "publish" as const,
      },
      {
        id: 2,
        slug: "concrete-flooring",
        title: { rendered: "Concrete & Flooring" },
        content: {
          rendered:
            "Interior and exterior concrete work, repairs, and decorative solutions.",
        },
        excerpt: { rendered: "Professional concrete and flooring services" },
        date: new Date().toISOString(),
        modified: new Date().toISOString(),
        status: "publish" as const,
      },
      {
        id: 3,
        slug: "residential-cleaning",
        title: { rendered: "Residential Cleaning" },
        content: {
          rendered:
            "Deep cleaning and recurring maintenance with eco-friendly products.",
        },
        excerpt: { rendered: "Comprehensive residential cleaning services" },
        date: new Date().toISOString(),
        modified: new Date().toISOString(),
        status: "publish" as const,
      },
      {
        id: 4,
        slug: "technical-support",
        title: { rendered: "Technical Support & Plans" },
        content: {
          rendered:
            "Certified engineering support and structural planning services.",
        },
        excerpt: { rendered: "Professional technical support and planning" },
        date: new Date().toISOString(),
        modified: new Date().toISOString(),
        status: "publish" as const,
      },
    ],
    testimonials: [
      {
        id: 1,
        slug: "testimonial-1",
        title: { rendered: "Amazing Pool Renovation" },
        content: {
          rendered:
            "RenovaLink transformed our backyard completely. The attention to detail was exceptional.",
        },
        excerpt: { rendered: "Excellent service and results" },
        date: new Date().toISOString(),
        modified: new Date().toISOString(),
        status: "publish" as const,
        acf: {
          client_name: "Maria Rodriguez",
          client_location: "Miami, FL",
          rating: 5,
        },
      },
    ],
    projects: [],
  };
}
