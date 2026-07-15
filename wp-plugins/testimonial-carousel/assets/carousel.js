(function () {
  const setupCarousel = (carousel) => {
    const slides = Array.from(carousel.querySelectorAll('[data-tc-slide]'));
    const dots = Array.from(carousel.querySelectorAll('[data-tc-dot]'));
    const prev = carousel.querySelector('[data-tc-prev]');
    const next = carousel.querySelector('[data-tc-next]');
    const toggle = carousel.querySelector('[data-tc-toggle]');
    const interval = parseInt(carousel.getAttribute('data-tc-interval'), 10) || 5000;
    let activeIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
    let autoplay = carousel.getAttribute('data-tc-autoplay') === 'true';
    let timer = null;

    if (!slides.length) {
      return;
    }

    if (activeIndex < 0) {
      activeIndex = 0;
    }

    const showSlide = (index) => {
      activeIndex = (index + slides.length) % slides.length;

      slides.forEach((slide, slideIndex) => {
        const isActive = slideIndex === activeIndex;
        slide.hidden = !isActive;
        slide.classList.toggle('is-active', isActive);
      });

      dots.forEach((dot, dotIndex) => {
        dot.classList.toggle('is-active', dotIndex === activeIndex);
      });
    };

    const stopAutoplay = () => {
      if (timer) {
        window.clearInterval(timer);
      }

      timer = null;
    };

    const startAutoplay = () => {
      stopAutoplay();

      if (autoplay && slides.length > 1) {
        timer = window.setInterval(() => showSlide(activeIndex + 1), interval);
      }
    };

    if (prev) {
      prev.addEventListener('click', () => {
        showSlide(activeIndex - 1);
        startAutoplay();
      });
    }

    if (next) {
      next.addEventListener('click', () => {
        showSlide(activeIndex + 1);
        startAutoplay();
      });
    }

    if (toggle) {
      toggle.addEventListener('click', () => {
        autoplay = !autoplay;
        toggle.setAttribute('aria-pressed', autoplay ? 'true' : 'false');
        toggle.textContent = autoplay ? 'Pause' : 'Play';
        startAutoplay();
      });
    }

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        showSlide(parseInt(dot.getAttribute('data-tc-dot'), 10));
        startAutoplay();
      });
    });

    showSlide(activeIndex);
    startAutoplay();
  };

  document.querySelectorAll('[data-tc-carousel]').forEach(setupCarousel);
})();
