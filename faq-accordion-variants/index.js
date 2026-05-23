const accordions = Array.from(document.querySelectorAll('[data-accordion]'));

accordions.forEach((accordion) => {
    const items = Array.from(accordion.querySelectorAll('.faq-item'));

    items.forEach((item) => {
        item.addEventListener('toggle', () => {
            if (!item.open) {
                return;
            }

            items.forEach((sibling) => {
                if (sibling !== item) {
                    sibling.open = false;
                }
            });
        });
    });
});
