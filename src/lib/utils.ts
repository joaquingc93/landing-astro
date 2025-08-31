import type { WPMedia } from '@/schemas/wordpress';

// Image optimization utilities
export function getOptimizedImageUrl(media: WPMedia, width?: number, height?: number): string {
  if (!media || !media.url) return '';
  
  try {
    // Check if it's a relative URL (starts with /)
    if (media.url.startsWith('/')) {
      // For relative URLs, just return the original URL since we can't optimize
      return media.url;
    }
    
    // If it's a WordPress media object, try to construct optimized URL
    const url = new URL(media.url);
    
    if (width) url.searchParams.set('w', width.toString());
    if (height) url.searchParams.set('h', height.toString());
    
    return url.toString();
  } catch (error) {
    console.warn('Invalid URL provided to getOptimizedImageUrl:', media.url);
    // Return the original URL if it's a string, otherwise empty string
    return typeof media.url === 'string' ? media.url : '';
  }
}

// Text utilities
export function stripHtml(html: string): string {
  return html.replace(/<[^>]*>/g, '');
}

export function truncateText(text: string, maxLength: number): string {
  if (text.length <= maxLength) return text;
  return text.slice(0, maxLength).trim() + '...';
}

export function slugify(text: string): string {
  return text
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

// Date utilities
export function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

// URL utilities
export function isExternalUrl(url: string): boolean {
  return url.startsWith('http://') || url.startsWith('https://');
}

export function normalizeUrl(url: string, baseUrl?: string): string {
  if (isExternalUrl(url)) return url;
  if (url.startsWith('/')) return url;
  return baseUrl ? `${baseUrl.replace(/\/$/, '')}/${url}` : `/${url}`;
}

// Performance utilities
export function preloadImage(src: string): Promise<void> {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => resolve();
    img.onerror = reject;
    img.src = src;
  });
}

// SEO utilities
export function generateMetaDescription(content: string, maxLength = 160): string {
  const stripped = stripHtml(content);
  return truncateText(stripped, maxLength);
}

export function generatePageTitle(title: string, siteName = 'RenovaLink'): string {
  return title.includes(siteName) ? title : `${title} | ${siteName}`;
}

// Form utilities
export function validatePhone(phone: string): boolean {
  const phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
  return phoneRegex.test(phone);
}

export function formatPhone(phone: string): string {
  const digits = phone.replace(/\D/g, '');
  if (digits.length === 10) {
    return `(${digits.slice(0, 3)}) ${digits.slice(3, 6)}-${digits.slice(6)}`;
  }
  return phone;
}

// Error handling utilities
export function handleApiError(error: unknown): { message: string; code?: string } {
  if (error instanceof Error) {
    return {
      message: error.message,
      code: 'name' in error ? error.name : undefined,
    };
  }
  return { message: 'An unexpected error occurred' };
}

// Performance monitoring
export function measurePerformance<T>(
  fn: () => Promise<T>,
  label: string
): Promise<T> {
  return new Promise(async (resolve, reject) => {
    const start = performance.now();
    
    try {
      const result = await fn();
      const end = performance.now();
      console.log(`${label} took ${end - start} milliseconds`);
      resolve(result);
    } catch (error) {
      const end = performance.now();
      console.error(`${label} failed after ${end - start} milliseconds:`, error);
      reject(error);
    }
  });
}

// Animation utilities
export function easeOutCubic(t: number): number {
  return 1 - Math.pow(1 - t, 3);
}

export function lerp(start: number, end: number, factor: number): number {
  return start + (end - start) * factor;
}

// Local storage utilities (for client-side)
export function getLocalStorage<T>(key: string, defaultValue: T): T {
  if (typeof window === 'undefined') return defaultValue;
  
  try {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : defaultValue;
  } catch {
    return defaultValue;
  }
}

export function setLocalStorage<T>(key: string, value: T): void {
  if (typeof window === 'undefined') return;
  
  try {
    localStorage.setItem(key, JSON.stringify(value));
  } catch (error) {
    console.warn('Failed to save to localStorage:', error);
  }
}

// Intersection Observer utilities
export function createIntersectionObserver(
  callback: IntersectionObserverCallback,
  options?: IntersectionObserverInit
): IntersectionObserver | null {
  if (typeof window === 'undefined' || !window.IntersectionObserver) {
    return null;
  }
  
  return new IntersectionObserver(callback, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px',
    ...options,
  });
}