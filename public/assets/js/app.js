function nbToggleMenu() {
  const nav = document.getElementById('nbMenu');
  if (!nav) return;
  nav.classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', () => {
  const nav = document.getElementById('nbMenu');
  const burger = document.querySelector('.nb-burger');

  if (!nav || !burger) return;

  const links = nav.querySelectorAll('a');

  links.forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 980) {
        nav.classList.remove('open');
      }
    });
  });

  document.addEventListener('click', (e) => {
    const clickInNav = nav.contains(e.target);
    const clickInBurger = burger.contains(e.target);

    if (!clickInNav && !clickInBurger && window.innerWidth <= 980) {
      nav.classList.remove('open');
    }
  });
});

// Carousel optionnel
function initCarousel(rootId) {
  const root = document.getElementById(rootId);
  if (!root) return;

  const cards = root.querySelectorAll('.carousel-card');
  let i = 0;

  const show = (idx) => {
    cards.forEach(c => c.classList.remove('active'));
    cards[idx].classList.add('active');
  };

  root.querySelector('[data-prev]')?.addEventListener('click', () => {
    i = (i - 1 + cards.length) % cards.length;
    show(i);
  });

  root.querySelector('[data-next]')?.addEventListener('click', () => {
    i = (i + 1) % cards.length;
    show(i);
  });

  show(0);
}