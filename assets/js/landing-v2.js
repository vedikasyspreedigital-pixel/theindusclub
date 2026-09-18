// Wires up navigation, mobile menu, gallery lightbox and scroll reveal for the landing page.
(function () {
  var scrollTargets = {
    'experiences': '#experiences',
    'membership': '#membership',
    'the club': '#gallery',
    'register interest': '#register-interest',
    'register your interest': '#register-interest'
  };

  function scrollToSection(sel) {
    var el = document.querySelector(sel);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  }

  document.querySelectorAll('button').forEach(function (btn) {
    var label = (btn.textContent || '').trim().toLowerCase();
    if (scrollTargets[label]) {
      btn.addEventListener('click', function () { scrollToSection(scrollTargets[label]); });
    }
  });

  // Logo scrolls to top
  var logo = document.querySelector('header button[aria-label="The Indus Club home"]');
  if (logo) logo.addEventListener('click', function () { scrollToSection('#top'); });

  // Mobile menu
  var menuBtn = document.querySelector('button[aria-controls="mobile-navigation"]');
  if (menuBtn) {
    var menu = document.createElement('div');
    menu.id = 'mobile-navigation';
    menu.style.cssText = 'display:none;position:fixed;inset:0;z-index:50;background:rgba(7,17,32,.97);padding:96px 32px 32px;flex-direction:column;gap:20px;';
    ['Experiences', 'Membership', 'The Club', 'Register Interest'].forEach(function (label) {
      var item = document.createElement('button');
      item.textContent = label;
      item.style.cssText = 'display:block;background:none;border:none;color:#fff;font-size:1.4rem;text-align:left;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.12);cursor:pointer;font-family:inherit;';
      item.addEventListener('click', function () {
        menu.style.display = 'none';
        menuBtn.setAttribute('aria-expanded', 'false');
        scrollToSection(scrollTargets[label.toLowerCase()]);
      });
      menu.appendChild(item);
    });
    var closeBtn = document.createElement('button');
    closeBtn.textContent = '×';
    closeBtn.setAttribute('aria-label', 'Close menu');
    closeBtn.style.cssText = 'position:absolute;top:24px;right:24px;background:none;border:1px solid rgba(255,255,255,.25);color:#fff;width:44px;height:44px;font-size:1.5rem;cursor:pointer;';
    closeBtn.addEventListener('click', function () {
      menu.style.display = 'none';
      menuBtn.setAttribute('aria-expanded', 'false');
    });
    menu.appendChild(closeBtn);
    document.body.appendChild(menu);
    menuBtn.addEventListener('click', function () {
      var open = menu.style.display === 'flex';
      menu.style.display = open ? 'none' : 'flex';
      menuBtn.setAttribute('aria-expanded', String(!open));
    });
  }

  // Gallery lightbox
  var galleryTiles = [].slice.call(document.querySelectorAll('#gallery .grid button'));
  if (galleryTiles.length) {
    var slides = galleryTiles.map(function (tile) {
      var img = tile.querySelector('img');
      return { src: img.getAttribute('src'), alt: img.getAttribute('alt') || 'The Indus Club' };
    });
    var current = 0;

    var lb = document.createElement('div');
    lb.className = 'lightbox';
    lb.setAttribute('role', 'dialog');
    lb.setAttribute('aria-modal', 'true');
    lb.setAttribute('aria-label', 'Image gallery');
    lb.innerHTML =
      '<figure>' +
        '<img alt="">' +
        '<figcaption><span class="lb-caption"></span><span class="lb-counter"></span></figcaption>' +
      '</figure>' +
      '<button type="button" class="lb-close" aria-label="Close gallery">' +
        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>' +
      '</button>' +
      '<button type="button" class="lb-prev" aria-label="Previous image">' +
        '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>' +
      '</button>' +
      '<button type="button" class="lb-next" aria-label="Next image">' +
        '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>' +
      '</button>';
    document.body.appendChild(lb);

    var lbImg = lb.querySelector('img');
    var lbCaption = lb.querySelector('.lb-caption');
    var lbCounter = lb.querySelector('.lb-counter');

    function render() {
      var s = slides[current];
      lbImg.src = s.src;
      lbImg.alt = s.alt;
      lbCaption.textContent = s.alt;
      lbCounter.textContent = (current + 1) + ' / ' + slides.length;
    }
    function openLightbox(i) {
      current = i;
      render();
      lb.classList.add('is-open');
      document.body.classList.add('lightbox-open');
      lb.querySelector('.lb-close').focus();
    }
    function closeLightbox() {
      lb.classList.remove('is-open');
      document.body.classList.remove('lightbox-open');
    }
    function step(delta) {
      current = (current + delta + slides.length) % slides.length;
      render();
    }

    galleryTiles.forEach(function (tile, i) {
      tile.addEventListener('click', function () { openLightbox(i); });
    });
    lb.querySelector('.lb-close').addEventListener('click', closeLightbox);
    lb.querySelector('.lb-prev').addEventListener('click', function () { step(-1); });
    lb.querySelector('.lb-next').addEventListener('click', function () { step(1); });
    lb.addEventListener('click', function (e) {
      if (e.target === lb || e.target.tagName === 'FIGURE') closeLightbox();
    });
    document.addEventListener('keydown', function (e) {
      if (!lb.classList.contains('is-open')) return;
      if (e.key === 'Escape') closeLightbox();
      else if (e.key === 'ArrowLeft') step(-1);
      else if (e.key === 'ArrowRight') step(1);
    });
  }

  // Scroll reveal for the membership path steps
  var steps = [].slice.call(document.querySelectorAll('.path-step'));
  if (steps.length && 'IntersectionObserver' in window) {
    steps.forEach(function (el, i) {
      el.classList.add('reveal');
      el.style.setProperty('--reveal-delay', (i * 90) + 'ms');
    });
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.25 });
    steps.forEach(function (el) { observer.observe(el); });
  }

  // Register-interest form: split full name into first/last for the CRM, then let the real POST submit.
  var form = document.getElementById('enquire-form');
  if (form) {
    form.addEventListener('submit', function () {
      var fullname = form.querySelector('#fullname').value.trim();
      var parts = fullname.split(' ');
      var first = parts[0];
      var last = parts.length > 1 ? parts.slice(1).join(' ') : '';
      document.getElementById('NAME').value = fullname;
      form.querySelector('input[name="COBJ5CF1"]').value = first;
      document.getElementById('lastname').value = last;
    });
  }
})();
