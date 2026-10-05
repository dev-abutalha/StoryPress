/* StoryPress interactions: deliberately dependency-free. */
document.addEventListener('DOMContentLoaded', function () {
  var colorToggle = document.querySelector('.color-mode-toggle');
  if (colorToggle) {
    colorToggle.setAttribute('aria-pressed', String(document.documentElement.classList.contains('theme-dark')));
    colorToggle.addEventListener('click', function () {
      var isDark = document.documentElement.classList.toggle('theme-dark');
      window.localStorage.setItem('storypress-color-mode', isDark ? 'dark' : 'light');
      this.setAttribute('aria-pressed', String(isDark));
    });
  }
  var backToTop = document.querySelector('.back-to-top');
  if (backToTop) {
    backToTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
  }
  var menuButton = document.querySelector('.menu-toggle');
  var navigation = document.querySelector('.main-navigation');
  function openMenu() {
    if (!menuButton || !navigation) return;
    menuButton.setAttribute('aria-expanded', 'true');
    menuButton.classList.add('is-active');
    navigation.classList.add('is-open');
    document.body.classList.add('nav-locked');
  }
  function closeMenu() {
    if (!menuButton || !navigation) return;
    menuButton.setAttribute('aria-expanded', 'false');
    menuButton.classList.remove('is-active');
    navigation.classList.remove('is-open');
    document.body.classList.remove('nav-locked');
    navigation.querySelectorAll('li.is-open').forEach(function (li) {
      li.classList.remove('is-open');
      li.querySelectorAll('.submenu-toggle').forEach(function (btn) {
        btn.setAttribute('aria-expanded', 'false');
      });
    });
  }
  if (menuButton && navigation) {
    menuButton.addEventListener('click', function () {
      if (this.getAttribute('aria-expanded') === 'true') { closeMenu(); } else { openMenu(); }
    });
    navigation.addEventListener('click', function (event) {
      if (event.target.closest('a')) closeMenu();
    });
    document.addEventListener('click', function (event) {
      if (event.target.closest('.menu-toggle') || event.target.closest('.main-navigation')) return;
      if (menuButton.getAttribute('aria-expanded') === 'true') closeMenu();
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && menuButton.getAttribute('aria-expanded') === 'true') {
        closeMenu();
        menuButton.focus();
      }
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 761 && menuButton.getAttribute('aria-expanded') === 'true') closeMenu();
    });
  }
  document.querySelectorAll('.main-navigation li.menu-item-has-children').forEach(function (item) {
    var submenu = null;
    for (var i = 0; i < item.children.length; i++) {
      if (item.children[i].classList.contains('sub-menu')) { submenu = item.children[i]; break; }
    }
    if (!submenu) return;
    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'submenu-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    var sr = document.createElement('span');
    sr.className = 'screen-reader-text';
    sr.textContent = 'Toggle submenu';
    var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 16 16');
    svg.setAttribute('aria-hidden', 'true');
    svg.setAttribute('fill', 'none');
    var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', 'M3.5 6.25L8 10.75L12.5 6.25');
    path.setAttribute('stroke', 'currentColor');
    path.setAttribute('stroke-width', '1.5');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');
    svg.appendChild(path);
    toggle.appendChild(sr);
    toggle.appendChild(svg);
    item.insertBefore(toggle, submenu);
    toggle.addEventListener('click', function () {
      var isOpen = item.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });
  });
  var searchButton = document.querySelector('.search-toggle');
  var searchPanel = document.querySelector('.search-panel');
  if (searchButton && searchPanel) {
    searchButton.addEventListener('click', function () {
      var open = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', String(!open));
      searchPanel.hidden = open;
      if (!open) { searchPanel.querySelector('input') && searchPanel.querySelector('input').focus(); }
    });

    searchPanel.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        searchButton.setAttribute('aria-expanded', 'false');
        searchPanel.hidden = true;
        searchButton.focus();
      }
    });
  }
  var article = document.querySelector('.single-post');
  var progress = document.querySelector('.reading-progress span');
  if (article && progress) {
    window.addEventListener('scroll', function () {
      var start = article.offsetTop;
      var total = article.offsetHeight - window.innerHeight;
      var complete = Math.min(100, Math.max(0, ((window.scrollY - start) / total) * 100));
      progress.style.transform = 'scaleX(' + complete / 100 + ')';
    }, { passive: true });
  }
  var toc = document.querySelector('[data-storypress-toc]');
  if (article && toc) {
    var headings = article.querySelectorAll('.entry-content h2');
    var list = toc.querySelector('ol');
    headings.forEach(function (heading, index) {
      var id = heading.id || 'section-' + (index + 1);
      heading.id = id;
      var item = document.createElement('li');
      var link = document.createElement('a'); link.href = '#' + id; link.textContent = heading.textContent;
      item.appendChild(link); list.appendChild(item);
    });
    toc.hidden = !headings.length;
    if (headings.length) {
      toc.open = window.innerWidth > 760;
    }
  }
  document.querySelectorAll('[data-copy-link]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(window.location.href).then(function () {
        button.textContent = button.dataset.copiedLabel;
        window.setTimeout(function () { button.textContent = button.dataset.copyLabel; }, 1800);
      });
    });
  });
  var consentBanner = document.querySelector('[data-storypress-consent]');
  if (consentBanner) {
    var finishConsent = function (value) {
      var expires = new Date();
      expires.setTime(expires.getTime() + 365 * 24 * 60 * 60 * 1000);
      document.cookie = 'storypress_consent=' + encodeURIComponent(value) + ';expires=' + expires.toUTCString() + ';path=/';
      consentBanner.remove();
    };
    var accept = consentBanner.querySelector('[data-consent-accept]');
    var decline = consentBanner.querySelector('[data-consent-decline]');
    if (accept) accept.addEventListener('click', function () { finishConsent('accepted'); });
    if (decline) decline.addEventListener('click', function () { finishConsent('declined'); });
  }
});
