(function(){
  "use strict";

  /* Header scroll state + scroll progress bar */
  var header = document.getElementById('siteHeader');
  var scrollBar = document.getElementById('scrollBar');
  var backToTop = document.getElementById('backToTop');

  function onScroll(){
    var y = window.scrollY || document.documentElement.scrollTop;
    header.classList.toggle('is-scrolled', y > 40);
    backToTop.classList.toggle('is-visible', y > 600);

    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    var progress = docHeight > 0 ? (y / docHeight) * 100 : 0;
    scrollBar.style.width = progress + '%';
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Scroll reveal — geometry-based so fast/instant scrolls (flicks, anchor
     jumps, key nav) can't skip an element past a threshold-crossing check
     the way IntersectionObserver's callback can. */
  var revealEls = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
  var revealTicking = false;

  function checkReveal(){
    revealTicking = false;
    var limit = window.innerHeight * 0.92;
    revealEls = revealEls.filter(function(el){
      if (el.getBoundingClientRect().top < limit){
        el.classList.add('is-visible');
        return false;
      }
      return true;
    });
    if (!revealEls.length){
      window.removeEventListener('scroll', onRevealScroll);
      window.removeEventListener('resize', onRevealScroll);
    }
  }
  function onRevealScroll(){
    if (!revealTicking){
      revealTicking = true;
      setTimeout(checkReveal, 50);
    }
  }
  window.addEventListener('scroll', onRevealScroll, { passive: true });
  window.addEventListener('resize', onRevealScroll);
  window.addEventListener('load', checkReveal);
  window.addEventListener('pageshow', checkReveal);
  checkReveal();


  /* Google Ads / UTM attribution capture */
  var attributionFields = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content','utm_id','landing_page','last_landing_page','referrer','first_visit_time'];
  function safeDecode(value){
    try { return decodeURIComponent(String(value).replace(/\+/g, ' ')); } catch(e) { return String(value || '').replace(/\+/g, ' '); }
  }
  function safeEncode(value){
    try { return encodeURIComponent(value); } catch(e) { return String(value || ''); }
  }
  function getCookieValue(name){
    var parts = document.cookie ? document.cookie.split('; ') : [];
    for (var i = 0; i < parts.length; i++){
      var pair = parts[i].split('=');
      if (pair[0] === name) return safeDecode(pair.slice(1).join('='));
    }
    return '';
  }
  function setCookieValue(name, value){
    if (!value) return;
    var expires = new Date();
    expires.setTime(expires.getTime() + (90 * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + safeEncode(value) + '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
  }
  function getUrlParam(name){
    var match = new RegExp('[?&]' + name + '=([^&#]*)').exec(window.location.search);
    return match ? safeDecode(match[1]) : '';
  }
  function captureAttribution(){
    var firstVisit = getCookieValue('first_visit_time') || new Date().toISOString();
    setCookieValue('first_visit_time', firstVisit);
    setCookieValue('landing_page', getCookieValue('landing_page') || window.location.href);
    setCookieValue('last_landing_page', window.location.href);
    setCookieValue('referrer', getCookieValue('referrer') || document.referrer || '');
    for (var i = 0; i < attributionFields.length; i++){
      var field = attributionFields[i];
      var value = getUrlParam(field);
      if (value) setCookieValue(field, value);
    }
  }
  function attributionValue(name){
    if (name === 'landing_page') return getCookieValue('landing_page') || window.location.href;
    if (name === 'last_landing_page') return window.location.href;
    if (name === 'referrer') return getCookieValue('referrer') || document.referrer || '';
    if (name === 'first_visit_time') return getCookieValue('first_visit_time');
    return getUrlParam(name) || getCookieValue(name);
  }
  function populateAttributionFields(form){
    if (!form) return;
    for (var i = 0; i < attributionFields.length; i++){
      var field = attributionFields[i];
      var input = form.querySelector('[name="' + field + '"]');
      if (!input){
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = field;
        input.id = field;
        form.appendChild(input);
      }
      input.value = attributionValue(field);
    }
    var zcGad = form.querySelector('[name="zc_gad"]');
    if (zcGad) zcGad.value = attributionValue('gclid');
  }
  captureAttribution();

  /* Register Interest form */
  var registerForm = document.getElementById('registerForm');
  if (registerForm){
    populateAttributionFields(registerForm);
    window.addEventListener('load', function(){ populateAttributionFields(registerForm); });
    setTimeout(function(){ populateAttributionFields(registerForm); }, 750);
    setTimeout(function(){ populateAttributionFields(registerForm); }, 2000);
    registerForm.addEventListener('submit', function(e){
      e.preventDefault();
      if (!registerForm.checkValidity()){
        registerForm.reportValidity();
        return;
      }
      var btn = registerForm.querySelector('.form-submit');
      var originalLabel = btn.textContent;
      btn.disabled = true;
      btn.textContent = 'Submitting…';

      populateAttributionFields(registerForm);
      var data = {
        fullName: registerForm.fullName.value,
        companyName: registerForm.companyName.value,
        city: registerForm.city.value,
        age: registerForm.age.value,
        designation: registerForm.designation.value,
        referral: registerForm.referral.value,
        mobile: registerForm.mobile.value,
        email: registerForm.email.value,
        gclid: attributionValue('gclid'),
        gbraid: attributionValue('gbraid'),
        wbraid: attributionValue('wbraid'),
        utm_source: attributionValue('utm_source'),
        utm_medium: attributionValue('utm_medium'),
        utm_campaign: attributionValue('utm_campaign'),
        utm_term: attributionValue('utm_term'),
        utm_content: attributionValue('utm_content'),
        utm_id: attributionValue('utm_id'),
        landing_page: attributionValue('landing_page'),
        last_landing_page: attributionValue('last_landing_page'),
        referrer: attributionValue('referrer'),
        first_visit_time: attributionValue('first_visit_time')
      };

      fetch('register-landing.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
        .then(function(res){ return res.json(); })
        .then(function(json){
          if (!json || json.status !== 'success') throw new Error('submit-failed');
          registerForm.reset();
          btn.disabled = false;
          btn.textContent = originalLabel;

          var modal = document.getElementById('successModal');
          if (modal){
            modal.classList.add('is-visible');
            modal.setAttribute('aria-hidden', 'false');
          }
          setTimeout(function(){
            window.location.href = 'https://www.theindusclub.com/';
          }, 2600);
        })
        .catch(function(){
          btn.disabled = false;
          btn.textContent = originalLabel;
          alert('Something went wrong submitting your interest. Please try again, or email contact@theindusclub.com directly.');
        });
    });
  }

  /* Smooth in-page nav for back-to-top / brand link already handled via CSS scroll-behavior */
})();
