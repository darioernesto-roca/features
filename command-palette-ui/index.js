const actions = [
    { label: 'Go to Dashboard', hint: 'Navigate', run: () => alert('Dashboard opened') },
    { label: 'Create New Post', hint: 'Content', run: () => alert('New post flow started') },
    { label: 'Open Site Settings', hint: 'Configuration', run: () => alert('Settings opened') },
    { label: 'View Notifications', hint: 'Inbox', run: () => alert('Notifications opened') },
    { label: 'Sign Out', hint: 'Session', run: () => alert('Signed out') }
];

const palette = document.getElementById('command-palette');
const panel = palette.querySelector('.palette-panel');
const searchInput = document.getElementById('palette-search');
const list = palette.querySelector('.palette-list');
const openButton = document.querySelector('[data-open-palette]');

let activeIndex = 0;
let filteredActions = actions;
let restoreFocusTo = null;

function renderActions() {
    list.innerHTML = '';

    if (!filteredActions.length) {
        const empty = document.createElement('li');
        empty.textContent = 'No matching command';
        empty.className = 'palette-item';
        empty.setAttribute('aria-disabled', 'true');
        list.appendChild(empty);
        return;
    }

    filteredActions.forEach((action, index) => {
        const item = document.createElement('li');
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `palette-item${index === activeIndex ? ' active' : ''}`;
        button.setAttribute('role', 'option');
        button.setAttribute('aria-selected', String(index === activeIndex));
        button.innerHTML = `<span>${action.label}</span><small>${action.hint}</small>`;

        button.addEventListener('mouseenter', () => {
            activeIndex = index;
            renderActions();
        });

        button.addEventListener('click', () => runAction(index));
        item.appendChild(button);
        list.appendChild(item);
    });
}

function openPalette(trigger) {
    restoreFocusTo = trigger || document.activeElement;
    palette.classList.add('is-open');
    palette.setAttribute('aria-hidden', 'false');
    document.body.classList.add('palette-open');

    filteredActions = actions;
    activeIndex = 0;
    searchInput.value = '';
    renderActions();
    searchInput.focus();
}

function closePalette() {
    palette.classList.remove('is-open');
    palette.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('palette-open');

    if (restoreFocusTo) {
        restoreFocusTo.focus();
    }
}

function runAction(index) {
    const action = filteredActions[index];
    if (!action) {
        return;
    }

    closePalette();
    action.run();
}

function moveActive(direction) {
    if (!filteredActions.length) {
        return;
    }

    activeIndex = (activeIndex + direction + filteredActions.length) % filteredActions.length;
    renderActions();
}

function filterActions(value) {
    const query = value.trim().toLowerCase();
    filteredActions = actions.filter((action) => action.label.toLowerCase().includes(query) || action.hint.toLowerCase().includes(query));
    activeIndex = 0;
    renderActions();
}

openButton.addEventListener('click', () => openPalette(openButton));

palette.addEventListener('click', (event) => {
    if (event.target === palette) {
        closePalette();
    }
});

searchInput.addEventListener('input', () => filterActions(searchInput.value));

document.addEventListener('keydown', (event) => {
    const isHotkey = (event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k';
    if (isHotkey) {
        event.preventDefault();

        if (palette.classList.contains('is-open')) {
            closePalette();
        } else {
            openPalette(document.activeElement);
        }

        return;
    }

    if (!palette.classList.contains('is-open')) {
        return;
    }

    if (event.key === 'Escape') {
        event.preventDefault();
        closePalette();
    } else if (event.key === 'ArrowDown') {
        event.preventDefault();
        moveActive(1);
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        moveActive(-1);
    } else if (event.key === 'Enter') {
        event.preventDefault();
        runAction(activeIndex);
    } else if (event.key === 'Tab') {
        event.preventDefault();
        searchInput.focus();
    }
});

renderActions();
