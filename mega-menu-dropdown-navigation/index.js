const navLinks = document.getElementById('nav-links');
const menuToggle = document.querySelector('.menu-toggle');
const megaItem = document.querySelector('.has-mega');
const megaToggle = document.querySelector('.mega-toggle');

function setMenuOpen(isOpen) {
    navLinks.classList.toggle('is-open', isOpen);
    menuToggle.setAttribute('aria-expanded', String(isOpen));
}

function setMegaOpen(isOpen) {
    megaItem.classList.toggle('is-open', isOpen);
    megaToggle.setAttribute('aria-expanded', String(isOpen));
}

menuToggle.addEventListener('click', () => {
    setMenuOpen(!navLinks.classList.contains('is-open'));
});

megaToggle.addEventListener('click', () => {
    const isMobile = window.matchMedia('(max-width: 54rem)').matches;
    if (!isMobile) {
        return;
    }

    setMegaOpen(!megaItem.classList.contains('is-open'));
});

document.addEventListener('click', (event) => {
    if (!megaItem.contains(event.target)) {
        setMegaOpen(false);
    }
});

window.addEventListener('resize', () => {
    const isMobile = window.matchMedia('(max-width: 54rem)').matches;
    if (!isMobile) {
        setMenuOpen(false);
        setMegaOpen(false);
    }
});
