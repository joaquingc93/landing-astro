/**
 * RenovaLink Interactive Design System
 * Professional remodeling company interactions and animations
 * Optimized for conversion and accessibility
 */

class RenovaLinkInteractions {
  constructor() {
    this.init();
    this.setupScrollAnimations();
    this.setupNavigationInteractions();
    this.setupFormInteractions();
    this.setupGalleryInteractions();
    this.setupServiceCardAnimations();
    this.setupContactFormValidation();
    this.setupAccessibilityEnhancements();
  }

  init() {
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.initializeComponents());
    } else {
      this.initializeComponents();
    }
  }

  initializeComponents() {
    // Initialize all interactive components
    this.setupIntersectionObserver();
    this.setupSmoothScrolling();
    this.setupParallaxEffects();
    this.setupPreloaderAnimation();
  }

  // ===========================================
  // SCROLL ANIMATIONS & REVEALS
  // ===========================================

  setupScrollAnimations() {
    // Intersection Observer for scroll reveals
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    this.scrollObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          
          // Add stagger delay for grouped elements
          if (entry.target.dataset.stagger) {
            const siblings = entry.target.parentElement.children;
            Array.from(siblings).forEach((sibling, index) => {
              if (sibling.classList.contains('scroll-reveal')) {
                setTimeout(() => {
                  sibling.classList.add('revealed');
                }, index * 100);
              }
            });
          }
        }
      });
    }, observerOptions);
  }

  setupIntersectionObserver() {
    // Observe all scroll reveal elements
    const scrollRevealElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale');
    scrollRevealElements.forEach(el => {
      this.scrollObserver.observe(el);
    });

    // Observe service cards for staggered animation
    const serviceCards = document.querySelectorAll('.service-card, .service-card-enhanced');
    serviceCards.forEach((card, index) => {
      card.style.animationDelay = `${index * 100}ms`;
      this.scrollObserver.observe(card);
    });

    // Observe gallery items
    const galleryItems = document.querySelectorAll('.gallery-item, .gallery-item-enhanced');
    galleryItems.forEach((item, index) => {
      item.style.animationDelay = `${index * 50}ms`;
      this.scrollObserver.observe(item);
    });
  }

  // ===========================================
  // NAVIGATION INTERACTIONS
  // ===========================================

  setupNavigationInteractions() {
    const navbar = document.querySelector('.navbar');
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');
    const navLinks = document.querySelectorAll('.navbar-link');

    // Navbar scroll behavior
    if (navbar) {
      let lastScrollY = window.scrollY;
      
      window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        // Add shadow on scroll
        if (currentScrollY > 10) {
          navbar.classList.add('navbar-scrolled');
        } else {
          navbar.classList.remove('navbar-scrolled');
        }

        // Hide/show navbar on scroll direction
        if (currentScrollY > lastScrollY && currentScrollY > 100) {
          navbar.style.transform = 'translateY(-100%)';
        } else {
          navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
      });
    }

    // Mobile menu toggle
    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.contains('open');
        mobileMenu.classList.toggle('open');
        
        // Update aria-expanded
        mobileMenuButton.setAttribute('aria-expanded', !isOpen);
        
        // Toggle hamburger icon animation
        const hamburgerIcon = mobileMenuButton.querySelector('.hamburger-icon');
        if (hamburgerIcon) {
          hamburgerIcon.classList.toggle('active');
        }
      });

      // Close mobile menu when clicking outside
      document.addEventListener('click', (e) => {
        if (!navbar.contains(e.target)) {
          mobileMenu.classList.remove('open');
          mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // Active link highlighting
    this.setupActiveNavigation();
  }

  setupActiveNavigation() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-link[href^="#"]');

    if (sections.length === 0 || navLinks.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        const id = entry.target.getAttribute('id');
        const navLink = document.querySelector(`.navbar-link[href="#${id}"]`);
        
        if (entry.isIntersecting && navLink) {
          // Remove active class from all links
          navLinks.forEach(link => link.classList.remove('active'));
          // Add active class to current link
          navLink.classList.add('active');
        }
      });
    }, { threshold: 0.3 });

    sections.forEach(section => observer.observe(section));
  }

  setupSmoothScrolling() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        
        const targetId = link.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
          const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 0;
          const targetPosition = targetElement.offsetTop - navbarHeight - 20;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  }

  // ===========================================
  // SERVICE CARDS ANIMATIONS
  // ===========================================

  setupServiceCardAnimations() {
    const serviceCards = document.querySelectorAll('.service-card, .service-card-enhanced');
    
    serviceCards.forEach(card => {
      // Hover effect enhancements
      card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-8px) scale(1.02)';
        
        // Add glow effect
        card.style.boxShadow = '0 20px 40px -10px rgba(14, 165, 233, 0.2)';
        
        // Animate icon
        const icon = card.querySelector('.service-card-icon');
        if (icon) {
          icon.style.transform = 'scale(1.1) rotate(5deg)';
        }
      });
      
      card.addEventListener('mouseleave', () => {
        card.style.transform = '';
        card.style.boxShadow = '';
        
        const icon = card.querySelector('.service-card-icon');
        if (icon) {
          icon.style.transform = '';
        }
      });

      // Add click ripple effect
      card.addEventListener('click', (e) => {
        this.createRippleEffect(e, card);
      });
    });
  }

  // ===========================================
  // GALLERY INTERACTIONS
  // ===========================================

  setupGalleryInteractions() {
    this.setupGalleryFilters();
    this.setupGalleryModal();
    this.setupBeforeAfterSliders();
  }

  setupGalleryFilters() {
    const filterButtons = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item, .gallery-item-enhanced');

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        const filter = button.dataset.filter;
        
        // Update active button
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Filter gallery items
        galleryItems.forEach(item => {
          const itemCategory = item.dataset.category;
          
          if (filter === 'all' || itemCategory === filter) {
            item.style.display = 'block';
            item.classList.add('animate-scale-in');
          } else {
            item.style.display = 'none';
            item.classList.remove('animate-scale-in');
          }
        });
      });
    });
  }

  setupGalleryModal() {
    const galleryItems = document.querySelectorAll('.gallery-item, .gallery-item-enhanced');
    
    galleryItems.forEach(item => {
      item.addEventListener('click', () => {
        const imageSrc = item.querySelector('img')?.src;
        const title = item.dataset.title;
        const description = item.dataset.description;
        
        if (imageSrc) {
          this.openGalleryModal(imageSrc, title, description);
        }
      });
    });
  }

  openGalleryModal(imageSrc, title, description) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('gallery-modal');
    
    if (!modal) {
      modal = this.createGalleryModal();
      document.body.appendChild(modal);
    }

    // Update modal content
    const modalImage = modal.querySelector('.modal-image');
    const modalTitle = modal.querySelector('.modal-title');
    const modalDescription = modal.querySelector('.modal-description');

    if (modalImage) modalImage.src = imageSrc;
    if (modalTitle) modalTitle.textContent = title || '';
    if (modalDescription) modalDescription.textContent = description || '';

    // Show modal
    modal.classList.add('active');
    document.body.classList.add('modal-open');

    // Focus trap
    modal.querySelector('.modal-close')?.focus();
  }

  createGalleryModal() {
    const modal = document.createElement('div');
    modal.id = 'gallery-modal';
    modal.className = 'fixed inset-0 z-modal bg-neutral-900/95 backdrop-blur-md flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'modal-title');

    modal.innerHTML = `
      <div class="relative max-w-4xl max-h-full bg-white rounded-2xl shadow-strong overflow-hidden">
        <button class="modal-close absolute top-4 right-4 z-10 w-10 h-10 bg-neutral-900/50 hover:bg-neutral-900/70 text-white rounded-full flex items-center justify-center transition-colors duration-200" aria-label="Close modal">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <img class="modal-image w-full h-auto max-h-96 object-cover" src="" alt="">
        <div class="p-6">
          <h3 class="modal-title text-2xl font-bold text-neutral-900 mb-2"></h3>
          <p class="modal-description text-neutral-600"></p>
        </div>
      </div>
    `;

    // Close modal functionality
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        this.closeGalleryModal();
      }
    });

    modal.querySelector('.modal-close').addEventListener('click', () => {
      this.closeGalleryModal();
    });

    // Keyboard navigation
    modal.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.closeGalleryModal();
      }
    });

    return modal;
  }

  closeGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    if (modal) {
      modal.classList.remove('active');
      document.body.classList.remove('modal-open');
      
      // Return focus to trigger element
      setTimeout(() => {
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
      }, 300);
    }
  }

  setupBeforeAfterSliders() {
    const sliders = document.querySelectorAll('.before-after-slider');
    
    sliders.forEach(slider => {
      const overlay = slider.querySelector('.before-after-overlay');
      
      if (overlay) {
        slider.addEventListener('mousemove', (e) => {
          const rect = slider.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const percentage = (x / rect.width) * 100;
          
          overlay.style.clipPath = `polygon(${percentage}% 0, 100% 0, 100% 100%, ${percentage}% 100%)`;
        });
        
        slider.addEventListener('mouseleave', () => {
          overlay.style.clipPath = 'polygon(50% 0, 100% 0, 100% 100%, 50% 100%)';
        });
      }
    });
  }

  // ===========================================
  // FORM INTERACTIONS
  // ===========================================

  setupFormInteractions() {
    const forms = document.querySelectorAll('.contact-form, form');
    
    forms.forEach(form => {
      // Enhanced input focus effects
      const inputs = form.querySelectorAll('.form-input, .form-input-enhanced');
      
      inputs.forEach(input => {
        // Floating label effect
        const label = form.querySelector(`label[for="${input.id}"]`);
        
        input.addEventListener('focus', () => {
          input.parentElement.classList.add('focused');
          if (label) label.classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
          if (!input.value) {
            input.parentElement.classList.remove('focused');
            if (label) label.classList.remove('focused');
          }
        });
        
        // Check if input has value on load
        if (input.value) {
          input.parentElement.classList.add('focused');
          if (label) label.classList.add('focused');
        }
      });
    });
  }

  setupContactFormValidation() {
    const contactForms = document.querySelectorAll('.contact-form');
    
    contactForms.forEach(form => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (this.validateForm(form)) {
          this.submitForm(form);
        }
      });

      // Real-time validation
      const inputs = form.querySelectorAll('input, textarea, select');
      inputs.forEach(input => {
        input.addEventListener('blur', () => this.validateField(input));
        input.addEventListener('input', () => this.clearFieldError(input));
      });
    });
  }

  validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
      if (!this.validateField(input)) {
        isValid = false;
      }
    });
    
    return isValid;
  }

  validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    let isValid = true;
    let errorMessage = '';

    // Required field validation
    if (field.hasAttribute('required') && !value) {
      isValid = false;
      errorMessage = 'This field is required.';
    }

    // Email validation
    if (type === 'email' && value && !this.isValidEmail(value)) {
      isValid = false;
      errorMessage = 'Please enter a valid email address.';
    }

    // Phone validation
    if (type === 'tel' && value && !this.isValidPhone(value)) {
      isValid = false;
      errorMessage = 'Please enter a valid phone number.';
    }

    // Update field appearance
    this.updateFieldValidation(field, isValid, errorMessage);
    
    return isValid;
  }

  updateFieldValidation(field, isValid, errorMessage) {
    const fieldContainer = field.closest('.form-group') || field.parentElement;
    const existingError = fieldContainer.querySelector('.form-error');
    
    // Remove existing error
    if (existingError) {
      existingError.remove();
    }
    
    // Update field classes
    field.classList.remove('form-input-error', 'form-input-success');
    
    if (!isValid) {
      field.classList.add('form-input-error');
      
      // Add error message
      const errorElement = document.createElement('div');
      errorElement.className = 'form-error';
      errorElement.textContent = errorMessage;
      fieldContainer.appendChild(errorElement);
    } else if (field.value.trim()) {
      field.classList.add('form-input-success');
    }
  }

  clearFieldError(field) {
    const fieldContainer = field.closest('.form-group') || field.parentElement;
    const errorElement = fieldContainer.querySelector('.form-error');
    
    if (errorElement) {
      errorElement.remove();
    }
    
    field.classList.remove('form-input-error');
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  isValidPhone(phone) {
    const phoneRegex = /^[\+]?[(]?[\+]?\d{3}[)]?[-\s\.]?\d{3}[-\s\.]?\d{4,6}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
  }

  async submitForm(form) {
    const submitButton = form.querySelector('.form-submit-btn, button[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Show loading state
    submitButton.classList.add('form-submit-loading');
    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';
    
    try {
      const formData = new FormData(form);
      
      // Simulate form submission (replace with actual endpoint)
      await this.simulateFormSubmission(formData);
      
      // Success state
      this.showFormSuccess(form);
      form.reset();
      
    } catch (error) {
      console.error('Form submission error:', error);
      this.showFormError(form, 'An error occurred. Please try again.');
    } finally {
      // Reset button state
      submitButton.classList.remove('form-submit-loading');
      submitButton.disabled = false;
      submitButton.textContent = originalText;
    }
  }

  async simulateFormSubmission(formData) {
    // Simulate network delay
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    // For demo purposes, randomly succeed or fail
    if (Math.random() > 0.1) {
      return { success: true };
    } else {
      throw new Error('Submission failed');
    }
  }

  showFormSuccess(form) {
    const message = document.createElement('div');
    message.className = 'bg-success-50 border border-success-200 text-success-800 px-6 py-4 rounded-lg mb-6 animate-fade-in-down';
    message.innerHTML = `
      <div class="flex items-center">
        <svg class="w-5 h-5 text-success-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="font-medium">Thank you! Your message has been sent successfully.</span>
      </div>
    `;
    
    form.insertBefore(message, form.firstChild);
    
    // Remove success message after 5 seconds
    setTimeout(() => {
      message.remove();
    }, 5000);
  }

  showFormError(form, errorText) {
    const message = document.createElement('div');
    message.className = 'bg-error-50 border border-error-200 text-error-800 px-6 py-4 rounded-lg mb-6 animate-fade-in-down';
    message.innerHTML = `
      <div class="flex items-center">
        <svg class="w-5 h-5 text-error-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="font-medium">${errorText}</span>
      </div>
    `;
    
    form.insertBefore(message, form.firstChild);
    
    // Remove error message after 5 seconds
    setTimeout(() => {
      message.remove();
    }, 5000);
  }

  // ===========================================
  // PARALLAX & VISUAL EFFECTS
  // ===========================================

  setupParallaxEffects() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    if (parallaxElements.length === 0) return;
    
    const handleParallax = () => {
      const scrollTop = window.pageYOffset;
      
      parallaxElements.forEach(element => {
        const speed = parseFloat(element.dataset.parallax) || 0.5;
        const yPos = -(scrollTop * speed);
        element.style.transform = `translateY(${yPos}px)`;
      });
    };
    
    // Throttle scroll events for performance
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          handleParallax();
          ticking = false;
        });
        ticking = true;
      }
    });
  }

  // ===========================================
  // ACCESSIBILITY ENHANCEMENTS
  // ===========================================

  setupAccessibilityEnhancements() {
    // Skip to main content link
    this.createSkipLink();
    
    // Enhanced keyboard navigation
    this.setupKeyboardNavigation();
    
    // High contrast mode detection
    this.setupHighContrastMode();
    
    // Reduced motion preferences
    this.setupReducedMotionPreferences();
  }

  createSkipLink() {
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-500 focus:text-white focus:rounded-md focus:shadow-lg';
    skipLink.textContent = 'Skip to main content';
    
    document.body.insertBefore(skipLink, document.body.firstChild);
  }

  setupKeyboardNavigation() {
    // Improve focus visibility
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        document.body.classList.add('keyboard-navigation');
      }
    });
    
    document.addEventListener('mousedown', () => {
      document.body.classList.remove('keyboard-navigation');
    });
  }

  setupHighContrastMode() {
    // Detect high contrast mode
    if (window.matchMedia('(prefers-contrast: high)').matches) {
      document.documentElement.classList.add('high-contrast');
    }
    
    // Listen for changes
    window.matchMedia('(prefers-contrast: high)').addEventListener('change', (e) => {
      if (e.matches) {
        document.documentElement.classList.add('high-contrast');
      } else {
        document.documentElement.classList.remove('high-contrast');
      }
    });
  }

  setupReducedMotionPreferences() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    if (prefersReducedMotion) {
      document.documentElement.classList.add('reduce-motion');
    }
  }

  // ===========================================
  // UTILITY FUNCTIONS
  // ===========================================

  createRippleEffect(event, element) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
      position: absolute;
      width: ${size}px;
      height: ${size}px;
      left: ${x}px;
      top: ${y}px;
      background: rgba(14, 165, 233, 0.3);
      border-radius: 50%;
      transform: scale(0);
      animation: ripple 0.6s ease-out;
      pointer-events: none;
      z-index: 1;
    `;
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes ripple {
        to {
          transform: scale(2);
          opacity: 0;
        }
      }
    `;
    
    if (!document.querySelector('style[data-ripple]')) {
      style.setAttribute('data-ripple', '');
      document.head.appendChild(style);
    }
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    // Remove ripple after animation
    setTimeout(() => {
      ripple.remove();
    }, 600);
  }

  setupPreloaderAnimation() {
    // Simple preloader fade out
    window.addEventListener('load', () => {
      const preloader = document.querySelector('.preloader');
      if (preloader) {
        preloader.style.opacity = '0';
        preloader.style.pointerEvents = 'none';
        
        setTimeout(() => {
          preloader.remove();
        }, 500);
      }
    });
  }

  // Public method to manually trigger scroll animations
  triggerScrollAnimations() {
    const elements = document.querySelectorAll('.scroll-reveal:not(.revealed)');
    elements.forEach(el => {
      el.classList.add('revealed');
    });
  }
}

// Initialize RenovaLink Interactions when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.renovaLinkInteractions = new RenovaLinkInteractions();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
  module.exports = RenovaLinkInteractions;
}