(function () {
  var PREMIUM_KEY = 'hestia_premium_mode';
  var PREMIUM_LABELS = [
    "chef's kiss listing",
    'main character balcony',
    'dangerously saveable',
    'hearth-certified vibes',
    "landlord's pride piece",
    "save-before-it's-gone energy"
  ];

  function removePremiumChips() {
    document.querySelectorAll('.hestia-premium-chip').forEach(function (el) {
      el.remove();
    });
  }

  function applyPremiumLabels() {
    if (sessionStorage.getItem(PREMIUM_KEY) !== '1') {
      return;
    }
    document.querySelectorAll('.hestia-property-card').forEach(function (card, i) {
      if (card.querySelector('.hestia-premium-chip')) {
        return;
      }
      var label = PREMIUM_LABELS[i % PREMIUM_LABELS.length];
      var chip = document.createElement('span');
      chip.className = 'hestia-premium-chip badge bg-warning text-dark position-absolute';
      chip.style.top = '42px';
      chip.style.left = '8px';
      chip.style.zIndex = '11';
      chip.style.fontSize = '0.65rem';
      chip.style.maxWidth = '11rem';
      chip.style.whiteSpace = 'normal';
      chip.textContent = label;
      if (!card.classList.contains('position-relative')) {
        card.classList.add('position-relative');
      }
      card.appendChild(chip);
    });
  }

  function enablePremiumMode() {
    sessionStorage.setItem(PREMIUM_KEY, '1');
    if (window.hestiaEaster) {
      window.hestiaEaster.unlock('premium-labels');
    }
    applyPremiumLabels();
  }

  function clearPremiumOnReloadOrLegacyStorage() {
    try {
      localStorage.removeItem(PREMIUM_KEY);
    } catch (e) {}
    var isReload = false;
    try {
      var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
      if (nav && nav.type === 'reload') {
        isReload = true;
      }
    } catch (e1) {}
    if (!isReload) {
      try {
        var pn = performance.navigation;
        if (pn && pn.type === 1) {
          isReload = true;
        }
      } catch (e2) {}
    }
    if (isReload) {
      try {
        sessionStorage.removeItem(PREMIUM_KEY);
      } catch (e3) {}
      removePremiumChips();
    }
  }

  function bindBrand(brand) {
    var BURST_KEY = 'hestia_logo_burst';
    var BURST_MS = 3200;
    brand.addEventListener('click', function (e) {
      var now = Date.now();
      var state = { n: 0, t: now };
      try {
        var raw = sessionStorage.getItem(BURST_KEY);
        if (raw) {
          var prev = JSON.parse(raw);
          if (now - prev.t < BURST_MS) {
            state.n = prev.n;
          }
        }
      } catch (err) {}
      state.n += 1;
      state.t = now;
      sessionStorage.setItem(BURST_KEY, JSON.stringify(state));
      if (state.n >= 5) {
        e.preventDefault();
        sessionStorage.removeItem(BURST_KEY);
        enablePremiumMode();
      }
    });
    brand.addEventListener('dblclick', function (e) {
      if (!e.altKey) {
        return;
      }
      e.preventDefault();
      document.body.classList.add('hestia-hearth-active');
      if (window.hestiaEaster) {
        window.hestiaEaster.unlock('hearth-glow');
      }
      setTimeout(function () {
        document.body.classList.remove('hestia-hearth-active');
      }, 5000);
    });
  }

  function initNavBrand() {
    document.querySelectorAll('.hestia-easter-brand').forEach(bindBrand);
    var adminBrand = document.querySelector('.admin-brand');
    if (adminBrand && !adminBrand.classList.contains('hestia-easter-brand')) {
      bindBrand(adminBrand);
    }
  }

  function initKonami() {
    if (document.body.getAttribute('data-hestia-page') !== 'home') {
      return;
    }
    var seq = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    var pos = 0;
    document.addEventListener('keydown', function (e) {
      var key = e.key.length === 1 ? e.key.toLowerCase() : e.key;
      var want = seq[pos];
      var match = want === 'b' || want === 'a' ? key === want : key === want;
      if (!match) {
        pos = key === seq[0] ? 1 : 0;
        return;
      }
      pos += 1;
      if (pos === seq.length) {
        pos = 0;
        var def = document.querySelector('.hestia-hero-default');
        var oly = document.querySelector('.hestia-hero-olympus');
        if (def && oly) {
          def.classList.add('d-none');
          oly.classList.remove('d-none');
          if (window.hestiaEaster) {
            window.hestiaEaster.unlock('konami-olympus');
          }
          setTimeout(function () {
            oly.classList.add('d-none');
            def.classList.remove('d-none');
          }, 8000);
        }
      }
    });
  }

  function initNightOwl() {
    var h = new Date().getHours();
    var late = h >= 23 || h < 5;
    if (!late) {
      return;
    }
    var page = document.body.getAttribute('data-hestia-page');
    var dash = document.body.getAttribute('data-hestia-dashboard');
    if (page !== 'home' && !dash) {
      return;
    }
    var el = document.getElementById('hestiaNightOwlLine');
    if (el) {
      el.classList.remove('d-none');
      if (window.hestiaEaster) {
        window.hestiaEaster.unlock('night-owl');
      }
    }
  }

  function initWishlistSeven() {
    if (!document.body.hasAttribute('data-hestia-wishlist-count')) {
      return;
    }
    var c = parseInt(document.body.getAttribute('data-hestia-wishlist-count') || '0', 10);
    var key = 'hestia_wishlist_count_snapshot';
    var prevRaw = sessionStorage.getItem(key);
    var prevNum = prevRaw === null || prevRaw === '' ? null : parseInt(prevRaw, 10);

    if (c === 7) {
      var toastEl = document.getElementById('hestiaWishlistSevenToast');
      var crossedIntoSeven = prevNum !== 7;
      if (crossedIntoSeven && toastEl && typeof bootstrap !== 'undefined') {
        var t = new bootstrap.Toast(toastEl, { autohide: true, delay: 5500 });
        t.show();
      }
      var he = window.hestiaEaster;
      if (he) {
        he.unlock('wishlist-seven');
      }
    }
    sessionStorage.setItem(key, String(c));
  }

  function initReviewsTeaser() {
    var card = document.getElementById('hestiaReviewsTeaser');
    if (!card) {
      return;
    }
    var n = 0;
    card.addEventListener('click', function () {
      n += 1;
      if (n !== 3) {
        return;
      }
      var badge = document.getElementById('hestiaReviewsTeaserReveal');
      if (!badge) {
        badge = document.createElement('div');
        badge.id = 'hestiaReviewsTeaserReveal';
        badge.className = 'alert alert-info small mt-3 mb-0';
        badge.innerHTML =
          '<span class="badge bg-secondary me-2">Beta</span><strong>Future feature found.</strong> Reviews are still in the forge - nice sleuthing.';
        card.appendChild(badge);
      }
      badge.classList.remove('d-none');
      if (window.hestiaEaster) {
        window.hestiaEaster.unlock('reviews-teaser');
      }
    });
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initNavBrand();
    initKonami();
    initNightOwl();
    initWishlistSeven();
    initReviewsTeaser();
  });

  window.addEventListener('load', function () {
    clearPremiumOnReloadOrLegacyStorage();
    applyPremiumLabels();
  });
})();
