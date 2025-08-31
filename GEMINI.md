# Project: RenovaLink Landing Page

## Overview

This project is a high-performance, professional landing page for RenovaLink, a Florida-based remodeling company. It's built with Astro.js and uses a headless WordPress backend for content management. The frontend is styled with Tailwind CSS and includes interactive components built with React. The project is optimized for performance, SEO, and accessibility.

**Key Technologies:**

*   **Framework:** Astro.js
*   **Styling:** Tailwind CSS
*   **CMS:** Headless WordPress
*   **Type-Safe API:** Zod for validation
*   **Development:** TypeScript, ESLint

## Building and Running

### Prerequisites

*   Node.js 18.17.1+ or 20.3.0+
*   npm 9.6.5+
*   A local WordPress development environment

### Installation

1.  **Clone the repository:**
    ```bash
    git clone [repository-url]
    cd landing-astro
    ```

2.  **Install dependencies:**
    ```bash
    npm install
    ```

3.  **Set up environment variables:**
    Create a `.env.local` file by copying `.env.example` and configure your WordPress API endpoints.

### Development

*   **Start the development server:**
    ```bash
    npm run dev
    ```

### Building for Production

*   **Build the project:**
    ```bash
    npm run build
    ```

*   **Preview the production build:**
    ```bash
    npm run preview
    ```

### Linting

*   **Run the linter:**
    ```bash
    npm run lint
    ```

*   **Fix linting errors:**
    ```bash
    npm run lint:fix
    ```

## Development Conventions

*   **Components:** Reusable Astro components are located in `src/components`.
*   **Layouts:** The base layout is in `src/layouts/Layout.astro`.
*   **Pages:** File-based routing is used, with pages in `src/pages`.
*   **WordPress Integration:** The WordPress API client and utility functions are in `src/lib`.
*   **Validation:** Zod schemas for WordPress API responses are in `src/schemas`.
*   **Styling:** Global styles and Tailwind CSS configuration are in `src/styles`.
*   **Types:** TypeScript type definitions are in `src/types`.
