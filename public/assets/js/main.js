/* ==========================================================================
   SUMAS — Public site interactions
   ========================================================================== */
document.addEventListener('DOMContentLoaded', function () {

  /* Preloader */
  var preloader = document.getElementById('preloader');
  if (preloader) {
    window.addEventListener('load', function () {
      setTimeout(function () { preloader.classList.add('hide'); }, 350);
    });
    // Fallback in case load already fired
    setTimeout(function () { preloader.classList.add('hide'); }, 1600);
  }

  /* Nav scroll state */
  var nav = document.querySelector('.sumas-nav');
  function handleNavScroll() {
    if (!nav) return;
    if (window.scrollY > 40) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  }
  handleNavScroll();
  window.addEventListener('scroll', handleNavScroll);

  /* Close mobile nav on link click */
  document.querySelectorAll('.sumas-nav .nav-link').forEach(function (link) {
    link.addEventListener('click', function () {
      var collapse = document.querySelector('.navbar-collapse.show');
      if (collapse) bootstrap.Collapse.getOrCreateInstance(collapse).hide();
    });
  });

  /* Scroll reveal */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in'); });
  }

  /* Back to top */
  var backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 500) backToTop.classList.add('show');
      else backToTop.classList.remove('show');
    });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* Animated counters */
  var counters = document.querySelectorAll('[data-counter]');
  function runCounter(el) {
    var target = parseFloat(el.getAttribute('data-counter'));
    var duration = 1400;
    var start = null;
    var suffix = el.getAttribute('data-suffix') || '';
    function step(ts) {
      if (!start) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      var val = Math.floor(progress * target);
      el.textContent = val.toLocaleString() + suffix;
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = target.toLocaleString() + suffix;
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { runCounter(entry.target); cio.unobserve(entry.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  }

  /* Gallery filter (gallery.html) */
  var filterBtns = document.querySelectorAll('[data-filter]');
  var galleryItems = document.querySelectorAll('.gallery-item');
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.classList.remove('btn-sumas-primary'); b.classList.add('btn-sumas-outline'); });
      btn.classList.add('btn-sumas-primary'); btn.classList.remove('btn-sumas-outline');
      var filter = btn.getAttribute('data-filter');
      galleryItems.forEach(function (item) {
        if (filter === 'all' || item.getAttribute('data-category') === filter) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  /* Password toggle visibility (login/register/forgot) */
  document.querySelectorAll('.pw-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.querySelector(btn.getAttribute('data-target'));
      if (!input) return;
      var icon = btn.querySelector('i');
      if (input.type === 'password') { input.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
      else { input.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
    });
  });

  /* Password strength meter (register.html) */
  var pwInput = document.getElementById('regPassword');
  var pwBar = document.querySelector('.pw-strength .bar');
  if (pwInput && pwBar) {
    pwInput.addEventListener('input', function () {
      var val = pwInput.value;
      var score = 0;
      if (val.length >= 6) score += 25;
      if (val.length >= 10) score += 20;
      if (/[A-Z]/.test(val)) score += 20;
      if (/[0-9]/.test(val)) score += 20;
      if (/[^A-Za-z0-9]/.test(val)) score += 15;
      score = Math.min(score, 100);
      pwBar.style.width = score + '%';
      pwBar.style.background = score < 40 ? 'var(--sumas-danger)' : score < 75 ? 'var(--sumas-warning)' : 'var(--sumas-success)';
    });
  }

  /* Bootstrap-style client-side validation feedback.
     Forms now post to real Laravel routes, so we only ever preventDefault
     when the form is INVALID -- a valid submit is left alone so it actually
     reaches the server, which redirects back with a flashed status message
     or validation errors (see resources/views/layouts and .invalid-feedback
     blocks driven by $errors in each Blade view). */
  document.querySelectorAll('form.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });

  /* Contact form: let it submit normally to /contact (Laravel route).
     We only intercept to add validation styling client-side. */
  var contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      if (!contactForm.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      contactForm.classList.add('was-validated');
    });
  }

});
