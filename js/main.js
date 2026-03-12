/* =========================================
   سوبر جات - Main JavaScript
   ========================================= */

(function () {
  'use strict';

  // Match the --navbar-height CSS variable value
  var NAVBAR_HEIGHT = 72;

  // ---- Navbar scroll effect ----
  const navbar = document.getElementById('navbar');

  function handleNavbarScroll() {
    if (window.scrollY > 20) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  window.addEventListener('scroll', handleNavbarScroll, { passive: true });

  // ---- Mobile hamburger menu ----
  const hamburger = document.getElementById('hamburger');
  const navLinks = document.getElementById('navLinks');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', function () {
      const isOpen = navLinks.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', String(isOpen));
    });

    // Close menu when a nav link is clicked
    navLinks.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        navLinks.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
      if (
        navLinks.classList.contains('open') &&
        !navLinks.contains(event.target) &&
        !hamburger.contains(event.target)
      ) {
        navLinks.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ---- Active nav link on scroll ----
  const sections = document.querySelectorAll('section[id]');
  const navLinksAll = document.querySelectorAll('.nav-links a');

  function updateActiveNavLink() {
    const scrollPos = window.scrollY + 100;

    sections.forEach(function (section) {
      const top = section.offsetTop;
      const bottom = top + section.offsetHeight;
      const id = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < bottom) {
        navLinksAll.forEach(function (link) {
          link.classList.remove('active-link');
          if (link.getAttribute('href') === '#' + id) {
            link.classList.add('active-link');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', updateActiveNavLink, { passive: true });

  // ---- Billing toggle (monthly / yearly) ----
  const billingToggle = document.getElementById('billingToggle');
  const priceAmounts = document.querySelectorAll('.price-amount');

  if (billingToggle) {
    billingToggle.addEventListener('click', function () {
      const isYearly = billingToggle.getAttribute('aria-checked') === 'true';
      const newState = !isYearly;
      billingToggle.setAttribute('aria-checked', String(newState));

      priceAmounts.forEach(function (el) {
        const monthly = el.getAttribute('data-monthly');
        const yearly = el.getAttribute('data-yearly');
        el.textContent = newState ? yearly : monthly;
      });
    });

    // Keyboard support
    billingToggle.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        billingToggle.click();
      }
    });
  }

  // ---- Contact form validation & submission ----
  const contactForm = document.getElementById('contactForm');
  const formSuccess = document.getElementById('formSuccess');
  const submitBtn = document.getElementById('submitBtn');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      if (!validateForm()) return;

      // Show loading state
      submitBtn.classList.add('loading');
      submitBtn.disabled = true;

      // TODO: Replace this simulated delay with an actual API/fetch call
      // e.g. fetch('/api/contact', { method: 'POST', body: new FormData(contactForm) })
      setTimeout(function () {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        contactForm.reset();
        if (formSuccess) {
          formSuccess.classList.add('show');
          setTimeout(function () {
            formSuccess.classList.remove('show');
          }, 6000);
        }
      }, 1500);
    });

    // Real-time validation on blur
    ['fullName', 'email', 'message'].forEach(function (fieldId) {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('blur', function () {
          validateField(field);
        });
        field.addEventListener('input', function () {
          if (field.classList.contains('error')) {
            validateField(field);
          }
        });
      }
    });
  }

  function validateForm() {
    const fields = ['fullName', 'email', 'message'];
    let isValid = true;

    fields.forEach(function (fieldId) {
      const field = document.getElementById(fieldId);
      if (field && !validateField(field)) {
        isValid = false;
      }
    });

    return isValid;
  }

  function validateField(field) {
    const errorEl = document.getElementById(field.id + 'Error');
    let errorMsg = '';

    if (!field.value.trim()) {
      errorMsg = 'هذا الحقل مطلوب';
    } else if (field.type === 'email') {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(field.value.trim())) {
        errorMsg = 'يرجى إدخال بريد إلكتروني صحيح';
      }
    } else if (field.id === 'fullName' && field.value.trim().length < 2) {
      errorMsg = 'الاسم يجب أن يكون حرفين على الأقل';
    } else if (field.id === 'message' && field.value.trim().length < 10) {
      errorMsg = 'الرسالة يجب أن تكون 10 أحرف على الأقل';
    }

    if (errorEl) {
      errorEl.textContent = errorMsg;
    }

    if (errorMsg) {
      field.classList.add('error');
      return false;
    } else {
      field.classList.remove('error');
      return true;
    }
  }

  // ---- Intersection Observer: reveal on scroll ----
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px',
  };

  const revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        revealObserver.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll(
    '.feature-card, .pricing-card, .testimonial-card, .step'
  ).forEach(function (el) {
    el.classList.add('reveal');
    revealObserver.observe(el);
  });

  // ---- Back to top button ----
  const backToTop = document.getElementById('backToTop');

  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    }, { passive: true });

    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ---- Update current year in footer ----
  const yearEl = document.getElementById('currentYear');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // ---- Smooth scroll for anchor links ----
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const href = anchor.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const top = target.getBoundingClientRect().top + window.scrollY - NAVBAR_HEIGHT;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

})();
