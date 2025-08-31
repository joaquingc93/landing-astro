/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  darkMode: 'class',
  theme: {
    extend: {
      // RenovaLink Brand Colors (optimized for latest Tailwind)
      colors: {
        primary: {
          50: '#f0f9ff',   // Light blue backgrounds, cards
          100: '#e0f2fe',  // Subtle accents
          200: '#bae6fd',  // Disabled states
          300: '#7dd3fc',  // Borders
          400: '#38bdf8',  // Interactive elements
          500: '#0ea5e9',  // Main brand blue (trust, professionalism)
          600: '#0284c7',  // Hover states, pressed buttons
          700: '#0369a1',  // Active states
          800: '#075985',  // Strong accents
          900: '#0c4a6e',  // Dark blue for headers, strong text
          950: '#082f49',  // Darkest shade
        },
        secondary: {
          50: '#fefce8',   // Light yellow backgrounds
          100: '#fef3c7',  // Subtle accents
          200: '#fde68a',  // Borders
          300: '#fcd34d',  // Icons, badges
          400: '#facc15',  // Gold (premium quality)
          500: '#eab308',  // Hover yellow, active states
          600: '#ca8a04',  // Pressed states
          700: '#a16207',  // Strong accents
          800: '#854d0e',  // Text on light backgrounds
          900: '#713f12',  // Dark text
          950: '#422006',  // Darkest shade
        },
        neutral: {
          50: '#f8fafc',   // Pure white, card backgrounds
          100: '#f1f5f9',  // Light gray, subtle backgrounds
          200: '#e2e8f0',  // Borders, dividers
          300: '#cbd5e1',  // Disabled text, placeholders
          400: '#94a3b8',  // Helper text
          500: '#64748b',  // Secondary text
          600: '#475569',  // Primary text (readable)
          700: '#334155',  // Headings
          800: '#1e293b',  // Strong headings
          900: '#0f172a',  // Black for titles, emphasis
          950: '#020617',  // Darkest shade
        },
        // Semantic Colors
        success: {
          50: '#f0fdf4',
          500: '#22c55e',
          600: '#16a34a'
        },
        warning: {
          50: '#fffbeb',
          500: '#f59e0b',
          600: '#d97706'
        },
        error: {
          50: '#fef2f2',
          500: '#ef4444',
          600: '#dc2626'
        }
      },
      // Typography System  
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Inter', 'system-ui', 'sans-serif'], 
        serif: ['Playfair Display', 'serif'],
      },

      // Enhanced spacing for modern layouts
      spacing: {
        '18': '4.5rem',   // 72px
        '22': '5.5rem',   // 88px  
        '26': '6.5rem',   // 104px
        '30': '7.5rem',   // 120px
        '34': '8.5rem',   // 136px
        '38': '9.5rem',   // 152px
      },

      // Enhanced shadows for depth
      boxShadow: {
        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
        'medium': '0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 25px -5px rgba(0, 0, 0, 0.04)',
        'strong': '0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 20px 25px -5px rgba(0, 0, 0, 0.1)',
        'glow-primary': '0 0 20px rgba(14, 165, 233, 0.3)',
        'glow-secondary': '0 0 20px rgba(250, 204, 21, 0.3)',
      },

      // Enhanced border radius
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem', 
        '6xl': '3rem'
      },

      // Modern animation system
      animation: {
        // Scroll-triggered animations
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'fade-in-up': 'fadeInUp 0.6s ease-out',
        'fade-in-down': 'fadeInDown 0.6s ease-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'slide-in': 'slideIn 0.3s ease-out',
        'scale-in': 'scaleIn 0.5s ease-out',
        
        // Interactive animations
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'bounce-gentle': 'bounceGentle 2s infinite',
        'float': 'float 6s ease-in-out infinite',
        'wiggle': 'wiggle 0.3s ease-in-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        fadeInDown: {
          '0%': { opacity: '0', transform: 'translateY(-30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideIn: {
          '0%': { transform: 'translateX(-10px)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { opacity: '0', transform: 'scale(0.9)' },
          '100%': { opacity: '1', transform: 'scale(1)' }
        },
        bounceGentle: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' }
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' }
        },
        wiggle: {
          '0%, 100%': { transform: 'rotate(0deg)' },
          '25%': { transform: 'rotate(1deg)' },
          '75%': { transform: 'rotate(-1deg)' }
        },
      },

      // Enhanced responsive breakpoints
      screens: {
        'xs': '475px',
        '3xl': '1600px',
      },

      // Container customization
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
          lg: '3rem',
          xl: '4rem',
          '2xl': '5rem',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
};