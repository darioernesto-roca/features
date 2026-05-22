const carousel = document.querySelector('.testimonial-carousel');

if (carousel) {
  const viewport = carousel.querySelector('.carousel-viewport');
  const track = carousel.querySelector('.carousel-track');
  const slides = Array.from(carousel.querySelectorAll('.testimonial-slide'));
  const dots = Array.from(carousel.querySelectorAll('.dot'));
  const prevButton = carousel.querySelector('.prev');
  const nextButton = carousel.querySelector('.next');
  const autoplayEnabled = carousel.dataset.autoplay !== 'false';
  const interval = Number(carousel.dataset.interval) || 5000;

  let currentIndex = 0;
  let autoplayTimer = null;
  let pointerStartX = 0;
  let pointerDeltaX = 0;
  let isPointerDown = false;

  const render = (index) => {
    currentIndex = (index + slides.length) % slides.length;
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    slides.forEach((slide, slideIndex) => {
      const isActive = slideIndex === currentIndex;
      slide.classList.toggle('is-active', isActive);
      slide.setAttribute('aria-hidden', String(!isActive));
    });

    dots.forEach((dot, dotIndex) => {
      const isActive = dotIndex === currentIndex;
      dot.classList.toggle('is-active', isActive);
      dot.setAttribute('aria-selected', String(isActive));
    });
  };

  const stopAutoplay = () => {
    if (autoplayTimer) {
      window.clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
  };

  const startAutoplay = () => {
    if (!autoplayEnabled || slides.length < 2) {
      return;
    }

    stopAutoplay();
    autoplayTimer = window.setInterval(() => {
      render(currentIndex + 1);
    }, interval);
  };

  const handlePointerDown = (event) => {
    isPointerDown = true;
    pointerStartX = event.clientX;
    pointerDeltaX = 0;
    stopAutoplay();
    viewport.setPointerCapture(event.pointerId);
  };

  const handlePointerMove = (event) => {
    if (!isPointerDown) {
      return;
    }

    pointerDeltaX = event.clientX - pointerStartX;
  };

  const handlePointerUp = () => {
    if (!isPointerDown) {
      return;
    }

    const swipeThreshold = 40;

    if (Math.abs(pointerDeltaX) > swipeThreshold) {
      render(currentIndex + (pointerDeltaX < 0 ? 1 : -1));
    }

    isPointerDown = false;
    startAutoplay();
  };

  prevButton.addEventListener('click', () => {
    render(currentIndex - 1);
    startAutoplay();
  });

  nextButton.addEventListener('click', () => {
    render(currentIndex + 1);
    startAutoplay();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      render(index);
      startAutoplay();
    });
  });

  viewport.addEventListener('pointerdown', handlePointerDown);
  viewport.addEventListener('pointermove', handlePointerMove);
  viewport.addEventListener('pointerup', handlePointerUp);
  viewport.addEventListener('pointercancel', handlePointerUp);
  carousel.addEventListener('mouseenter', stopAutoplay);
  carousel.addEventListener('mouseleave', startAutoplay);
  carousel.addEventListener('focusin', stopAutoplay);
  carousel.addEventListener('focusout', startAutoplay);

  render(0);
  startAutoplay();
}
