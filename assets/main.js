(function() {
  const slider = document.querySelector('.hero-slider');
  if (!slider) {
    return;
  }

  const slides = Array.from(slider.querySelectorAll('.slide'));
  const indicators = slider.querySelectorAll('[data-slide-to]');
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');
  let currentIndex = 0;
  let timerId = null;
  const interval = 6000;

  if (slides.length <= 1) {
    slides.forEach((slide, i) => {
      slide.setAttribute('aria-hidden', i === 0 ? 'false' : 'true');
      slide.classList.toggle('is-active', i === 0);
    });
    indicators.forEach((dot, i) => {
      dot.classList.toggle('is-active', i === 0);
      dot.disabled = true;
    });
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = true;
    return;
  }

  function goTo(index) {
    currentIndex = (index + slides.length) % slides.length;
    slides.forEach((slide, i) => {
      slide.setAttribute('aria-hidden', i !== currentIndex ? 'true' : 'false');
      slide.classList.toggle('is-active', i === currentIndex);
    });
    indicators.forEach((dot, i) => {
      dot.classList.toggle('is-active', i === currentIndex);
    });
  }

  function startAutoPlay() {
    stopAutoPlay();
    timerId = window.setInterval(() => {
      goTo(currentIndex + 1);
    }, interval);
  }

  function stopAutoPlay() {
    if (timerId !== null) {
      window.clearInterval(timerId);
      timerId = null;
    }
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      goTo(currentIndex - 1);
      startAutoPlay();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      goTo(currentIndex + 1);
      startAutoPlay();
    });
  }

  indicators.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      goTo(i);
      startAutoPlay();
    });
  });

  slider.addEventListener('mouseenter', stopAutoPlay);
  slider.addEventListener('mouseleave', startAutoPlay);

  goTo(0);
  startAutoPlay();
})();
(function () {
  const carousels = document.querySelectorAll('[data-carousel]');
  if (!carousels.length) return;

  carousels.forEach((carousel) => {
    const track = carousel.querySelector('.certificate-track');
    const prev = carousel.querySelector('.carousel-prev');
    const next = carousel.querySelector('.carousel-next');
    if (!track) return;

    const getStep = () => track.clientWidth / 2;

    const scrollBy = (delta) => {
      track.scrollBy({ left: delta, behavior: 'smooth' });
    };

    prev?.addEventListener('click', () => scrollBy(-getStep()));
    next?.addEventListener('click', () => scrollBy(getStep()));

    if (carousel.hasAttribute('data-autoplay')) {
      let autoplayId = null;
      const startAutoplay = () => {
        stopAutoplay();
        autoplayId = window.setInterval(() => {
          const maxScroll = track.scrollWidth - track.clientWidth;
          if (track.scrollLeft + track.clientWidth >= maxScroll - 2) {
            track.scrollTo({ left: 0, behavior: 'smooth' });
          } else {
            scrollBy(getStep());
          }
        }, 4000);
      };

      const stopAutoplay = () => {
        if (autoplayId) {
          window.clearInterval(autoplayId);
          autoplayId = null;
        }
      };

      carousel.addEventListener('mouseenter', stopAutoplay);
      carousel.addEventListener('mouseleave', startAutoplay);
      startAutoplay();
    }
  });
})();
