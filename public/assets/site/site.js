// mhmapp.az — header, mobile menu, screenshot gallery, reveal on scroll.
(function () {
  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');

  function onScroll() {
    header.classList.toggle('is-scrolled', window.scrollY > 8);
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  function setMenu(open) {
    header.classList.toggle('nav-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Menyunu bağla' : 'Menyunu aç');
  }
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      setMenu(!header.classList.contains('nav-open'));
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) setMenu(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setMenu(false);
    });
  }

  document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
    var track = gallery.querySelector('.gallery-track');
    gallery.querySelectorAll('[data-dir]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var item = track.querySelector('.shot');
        var step = item ? item.getBoundingClientRect().width + 28 : 260;
        track.scrollBy({ left: step * 2 * Number(btn.getAttribute('data-dir')), behavior: 'smooth' });
      });
    });
  });

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if ('IntersectionObserver' in window && !reduce) {
    document.documentElement.classList.add('js-reveal');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px' });
    document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
  }
})();
