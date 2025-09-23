// Extensiones de tipos para TypeScript
// Este archivo define tipos adicionales para Window y elementos DOM

declare global {
  interface Window {
    currentImageIndex: number;
    currentGallery: Array<{
      url: string;
      alt: string;
      title?: string;
      index?: number;
    }>;
  }

  interface HTMLElement {
    disabled?: boolean;
  }

  interface HTMLImageElement {
    alt: string;
    src: string;
  }

  interface HTMLButtonElement {
    disabled: boolean;
  }
}

export {};
