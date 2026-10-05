/* StoryPress homepage sliders. */
(function () {
  'use strict';

  function initHeroSlider(slider) {
    var viewport = slider.querySelector('.hero-slider__viewport');
    var slides = slider.querySelectorAll('.hero-slide');
    var controls = slider.querySelector('.hero-slider__controls');
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var studioLayout = slider.closest('.storypress-home--studio');

    if (!window.Swiper) {
      slider.classList.add('hero-slider--static');
      Array.prototype.forEach.call(slides, function (slide, index) {
        slide.setAttribute('aria-hidden', index === 0 ? 'false' : 'true');
        Array.prototype.forEach.call(slide.querySelectorAll('a'), function (link) {
          link.tabIndex = index === 0 ? 0 : -1;
        });
      });
      if (controls) controls.hidden = true;
      return;
    }

    if (!viewport || slides.length < 2) {
      if (controls) controls.hidden = true;
      return;
    }

    function updateSlideAccessibility(swiper) {
      window.requestAnimationFrame(function () {
        if (swiper.destroyed) return;
        Array.prototype.forEach.call(swiper.slides, function (slide) {
          var active = slide.classList.contains('swiper-slide-active');
          slide.setAttribute('aria-hidden', active ? 'false' : 'true');
          Array.prototype.forEach.call(slide.querySelectorAll('a'), function (link) {
            link.tabIndex = active ? 0 : -1;
          });
        });
      });
    }

    new window.Swiper(viewport, {
      a11y: {
        enabled: true,
        nextSlideMessage: 'Next featured story',
        paginationBulletMessage: 'Go to featured story {{index}}',
        prevSlideMessage: 'Previous featured story'
      },
      autoplay: reduced ? false : {
        delay: 5000,
        disableOnInteraction: false
      },
      grabCursor: true,
      centeredSlides: !!studioLayout,
      keyboard: { enabled: true, onlyInViewport: true },
      loop: studioLayout ? slides.length >= 5 : slides.length >= 3,
      loopAdditionalSlides: 0,
      slidesPerView: studioLayout ? 1.15 : 1,
      breakpoints: studioLayout ? { 600: { slidesPerView: 2 } } : {},
      pagination: {
        bulletActiveClass: 'is-active',
        bulletClass: 'hero-slider__dot',
        clickable: true,
        el: slider.querySelector('[data-slide-dots]'),
        renderBullet: function (index, className) {
          return '<button type="button" class="' + className + '" aria-label="Go to featured story ' + (index + 1) + '"></button>';
        }
      },
      resistanceRatio: 0.8,
      speed: 700,
      watchSlidesProgress: true,
      on: {
        init: updateSlideAccessibility,
        slideChange: updateSlideAccessibility,
        slideChangeTransitionEnd: updateSlideAccessibility
      }
    });
  }

  function initDestinationsCarousel(carousel) {
    var slides = carousel.querySelectorAll('.destination-card');
    if (slides.length < 2) return;
    if (!window.Swiper) {
      carousel.classList.add('destinations-carousel--native');
      var track = carousel.querySelector('.swiper-wrapper');
      var previous = carousel.parentElement.querySelector('[data-dest-prev]');
      var next = carousel.parentElement.querySelector('[data-dest-next]');
      if (track && previous && next) {
        previous.addEventListener('click', function () {
          track.scrollBy({ left: -track.clientWidth * 0.8, behavior: 'smooth' });
        });
        next.addEventListener('click', function () {
          track.scrollBy({ left: track.clientWidth * 0.8, behavior: 'smooth' });
        });
      }
      return;
    }

    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    new window.Swiper(carousel, {
      autoplay: reduced ? false : {
        delay: 3600,
        disableOnInteraction: false
      },
      grabCursor: true,
      loop: slides.length >= 5,
      a11y: {
        enabled: true,
        nextSlideMessage: 'Next destinations',
        prevSlideMessage: 'Previous destinations'
      },
      navigation: {
        nextEl: carousel.parentElement.querySelector('[data-dest-next]'),
        prevEl: carousel.parentElement.querySelector('[data-dest-prev]')
      },
      resistanceRatio: 0.8,
      slidesPerView: 1.05,
      spaceBetween: 16,
      speed: 550,
      breakpoints: {
        540: { slidesPerView: 1.6 },
        760: { slidesPerView: 2.2 },
        1024: { slidesPerView: 4 }
      }
    });
  }

  document.querySelectorAll('[data-hero-slider]').forEach(initHeroSlider);
  document.querySelectorAll('[data-destinations-carousel]').forEach(initDestinationsCarousel);
})();