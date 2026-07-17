(function () {
  const updateVisibility = (helpCenter) => {
    const searchInput = helpCenter.querySelector('[data-afhc-search]');
    const activeFilter = helpCenter.querySelector('[data-afhc-filter].is-active');
    const items = Array.from(helpCenter.querySelectorAll('[data-afhc-item]'));
    const empty = helpCenter.querySelector('[data-afhc-empty]');
    const searchValue = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const filterValue = activeFilter ? activeFilter.getAttribute('data-afhc-filter') : 'all';
    let visibleCount = 0;

    items.forEach((item) => {
      const searchText = item.getAttribute('data-afhc-search-text') || '';
      const categories = (item.getAttribute('data-afhc-categories') || '').split(' ');
      const matchesSearch = !searchValue || searchText.includes(searchValue);
      const matchesFilter = filterValue === 'all' || categories.includes(filterValue);
      const isVisible = matchesSearch && matchesFilter;

      item.hidden = !isVisible;

      if (isVisible) {
        visibleCount += 1;
      }
    });

    helpCenter.querySelectorAll('[data-afhc-group]').forEach((group) => {
      const visibleItems = group.querySelectorAll('[data-afhc-item]:not([hidden])');
      group.hidden = visibleItems.length === 0;
    });

    if (empty) {
      empty.hidden = visibleCount > 0;
    }
  };

  document.addEventListener('input', (event) => {
    if (!event.target.matches('[data-afhc-search]')) {
      return;
    }

    updateVisibility(event.target.closest('[data-afhc-help-center]'));
  });

  document.addEventListener('click', (event) => {
    const filter = event.target.closest('[data-afhc-filter]');

    if (!filter) {
      return;
    }

    const helpCenter = filter.closest('[data-afhc-help-center]');

    helpCenter.querySelectorAll('[data-afhc-filter]').forEach((button) => {
      button.classList.toggle('is-active', button === filter);
    });

    updateVisibility(helpCenter);
  });
})();
