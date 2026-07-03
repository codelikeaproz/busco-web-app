document.addEventListener('DOMContentLoaded', function () {
  var nav = document.querySelector('.site-nav');
  var toggle = document.querySelector('[data-nav-toggle]');
  var menu = document.querySelector('[data-nav-menu]');

  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var isOpen = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    menu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });

    document.addEventListener('click', function (event) {
      if (!menu.classList.contains('open')) {
        return;
      }

      var clickedInside = menu.contains(event.target) || toggle.contains(event.target);
      if (!clickedInside) {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  window.addEventListener('scroll', function () {
    if (!nav) {
      return;
    }

    if (window.scrollY > 12) {
      nav.classList.add('scrolled');
      return;
    }

    nav.classList.remove('scrolled');
  });

  var reveals = document.querySelectorAll('.reveal');
  if (!reveals.length) {
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.12 }
  );

  reveals.forEach(function (item) {
    observer.observe(item);
  });
});
