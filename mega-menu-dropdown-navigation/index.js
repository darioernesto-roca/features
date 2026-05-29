const navLinks = document.getElementById('nav-links');
const menuToggle = document.querySelector('.menu-toggle');
const megaItem = document.querySelector('.has-mega');
const megaToggle = document.querySelector('.mega-toggle');
const megaPanel = document.getElementById('mega-panel-products');
const mobileQuery = window.matchMedia('(max-width: 54rem)');
const menuPadding = 16;
const maxPanelWidth = 832;

function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
}

function positionMegaPanel() {
    if (mobileQuery.matches) {
        megaPanel.style.removeProperty('left');
        megaPanel.style.removeProperty('right');
        megaPanel.style.removeProperty('width');
        megaItem.style.removeProperty('--mega-left');
        megaItem.style.removeProperty('--mega-width');
        return;
    }

    const panelWidth = Math.min(maxPanelWidth, window.innerWidth - menuPadding * 2);
    const itemRect = megaItem.getBoundingClientRect();
    const toggleRect = megaToggle.getBoundingClientRect();
    const centeredLeft = toggleRect.left + toggleRect.width / 2 - panelWidth / 2 - itemRect.left;
    const minLeft = menuPadding - itemRect.left;
    const maxLeft = window.innerWidth - menuPadding - panelWidth - itemRect.left;
    const panelLeft = clamp(centeredLeft, minLeft, maxLeft);

    megaPanel.style.left = `${panelLeft}px`;
    megaPanel.style.right = 'auto';
    megaPanel.style.width = `${panelWidth}px`;
    megaItem.style.setProperty('--mega-left', `${panelLeft}px`);
    megaItem.style.setProperty('--mega-width', `${panelWidth}px`);
}

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

megaItem.addEventListener('mouseenter', positionMegaPanel);
megaItem.addEventListener('focusin', positionMegaPanel);

megaToggle.addEventListener('click', () => {
    if (!mobileQuery.matches) {
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
    positionMegaPanel();

    if (!mobileQuery.matches) {
        setMenuOpen(false);
        setMegaOpen(false);
    }
});

positionMegaPanel();
