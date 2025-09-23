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

    const getGapValue = () => {
      const styles = window.getComputedStyle(track);
      const gapValue = parseFloat(styles.columnGap || styles.gap || '0');
      return Number.isFinite(gapValue) ? gapValue : 0;
    };

    let hasLoop = false;
    let stepSize = 0;
    let loopWidth = track.scrollWidth;
    let startLoop = () => {};
    let stopLoop = () => {};

    const updateMetrics = () => {
      const firstSlide = track.querySelector('.certificate-slide');
      const firstWidth = firstSlide ? firstSlide.getBoundingClientRect().width : 0;
      stepSize = firstWidth ? firstWidth + getGapValue() : track.clientWidth / 2;
      loopWidth = hasLoop ? track.scrollWidth / 2 : track.scrollWidth;
    };

    updateMetrics();
    window.addEventListener('resize', updateMetrics, { passive: true });

    const scrollBy = (delta) => {
      track.scrollBy({ left: delta, behavior: 'smooth' });
    };

    const handleNav = (direction) => {
      updateMetrics();
      if (hasLoop) {
        stopLoop();
      }
      scrollBy(direction * stepSize || direction * (track.clientWidth / 2));
      if (hasLoop) {
        window.setTimeout(startLoop, 350);
      }
    };

    prev?.addEventListener('click', () => handleNav(-1));
    next?.addEventListener('click', () => handleNav(1));

    const slides = Array.from(track.children);

    if (carousel.hasAttribute('data-autoplay') && slides.length > 1) {
      const speedAttr = parseFloat(carousel.getAttribute('data-speed') || '');
      const pixelsPerFrame = Number.isFinite(speedAttr) && speedAttr > 0 ? speedAttr : 0.6;

      slides.forEach((slide) => {
        const clone = slide.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);
      });

      hasLoop = true;
      updateMetrics();

      let frameId = null;
      const loopStep = () => {
        track.scrollLeft += pixelsPerFrame;
        if (loopWidth > 0 && track.scrollLeft >= loopWidth) {
          track.scrollLeft -= loopWidth;
        }
        frameId = window.requestAnimationFrame(loopStep);
      };

      startLoop = () => {
        if (frameId !== null) {
          return;
        }
        frameId = window.requestAnimationFrame(loopStep);
      };

      stopLoop = () => {
        if (frameId === null) {
          return;
        }
        window.cancelAnimationFrame(frameId);
        frameId = null;
      };

      const resumeLoop = () => {
        if (!hasLoop) {
          return;
        }
        stopLoop();
        startLoop();
      };

      carousel.addEventListener('mouseenter', stopLoop);
      carousel.addEventListener('mouseleave', startLoop);
      carousel.addEventListener('touchstart', stopLoop, { passive: true });
      carousel.addEventListener('touchend', startLoop, { passive: true });
      carousel.addEventListener('focusin', stopLoop);
      carousel.addEventListener('focusout', resumeLoop);

      startLoop();
    }
  });
})();
