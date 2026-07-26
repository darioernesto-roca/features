(function () {
	'use strict';

	var settings = window.ReadingProgressTocSettings || {};
	var activeClass = 'is-active';

	function slugify(text) {
		return text.toLowerCase()
			.trim()
			.replace(/[^a-z0-9\s-]/g, '')
			.replace(/\s+/g, '-')
			.replace(/-+/g, '-') || 'section';
	}

	function uniqueId(base, used) {
		var id = base;
		var count = 2;

		while (used[id] || document.getElementById(id)) {
			id = base + '-' + count;
			count += 1;
		}

		used[id] = true;
		return id;
	}

	function getContentRoot(selector) {
		return document.querySelector(selector || settings.contentSelector || '.entry-content, article, main') || document.body;
	}

	function getHeadings(root, minLevel, maxLevel) {
		var selectors = [];
		var level;

		for (level = minLevel; level <= maxLevel; level += 1) {
			selectors.push('h' + level);
		}

		return Array.prototype.slice.call(root.querySelectorAll(selectors.join(','))).filter(function (heading) {
			return heading.textContent.trim() && !heading.closest('[data-rptoc-toc]');
		});
	}

	function buildToc(toc) {
		var selector = toc.dataset.rptocSelector || settings.contentSelector;
		var minLevel = parseInt(toc.dataset.rptocMinLevel || '2', 10);
		var maxLevel = parseInt(toc.dataset.rptocMaxLevel || '3', 10);
		var root = getContentRoot(selector);
		var headings = getHeadings(root, minLevel, maxLevel);
		var list = toc.querySelector('[data-rptoc-list]');
		var placeholder = toc.querySelector('[data-rptoc-placeholder]');
		var used = {};

		if (!list) {
			return [];
		}

		list.innerHTML = '';

		if (!headings.length) {
			if (placeholder) {
				placeholder.hidden = false;
			}
			return [];
		}

		if (placeholder) {
			placeholder.hidden = true;
		}

		headings.forEach(function (heading) {
			var link = document.createElement('a');
			var item = document.createElement('li');
			var level = parseInt(heading.tagName.substring(1), 10);

			if (!heading.id) {
				heading.id = uniqueId(slugify(heading.textContent), used);
			}

			link.href = '#' + heading.id;
			link.textContent = heading.textContent.trim();
			link.className = 'rptoc-toc__link';
			link.dataset.rptocTarget = heading.id;
			item.className = 'rptoc-toc__item rptoc-toc__item--level-' + level;
			item.appendChild(link);
			list.appendChild(item);
		});

		return headings;
	}

	function updateProgress() {
		var bar = document.querySelector('[data-rptoc-progress-bar]');
		var doc = document.documentElement;
		var scrollable = doc.scrollHeight - window.innerHeight;
		var progress = scrollable > 0 ? Math.min(100, Math.max(0, (window.scrollY / scrollable) * 100)) : 0;

		if (bar) {
			bar.style.width = progress + '%';
		}
	}

	function observeActiveHeadings(headings) {
		var links = Array.prototype.slice.call(document.querySelectorAll('[data-rptoc-target]'));

		if (!('IntersectionObserver' in window) || !headings.length || !links.length) {
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				var id = entry.target.id;
				var link = links.find(function (item) {
					return item.dataset.rptocTarget === id;
				});

				if (entry.isIntersecting && link) {
					links.forEach(function (item) {
						item.classList.remove(activeClass);
						item.removeAttribute('aria-current');
					});
					link.classList.add(activeClass);
					link.setAttribute('aria-current', 'true');
				}
			});
		}, { rootMargin: '-20% 0px -70% 0px', threshold: 0.01 });

		headings.forEach(function (heading) {
			observer.observe(heading);
		});
	}

	function bindSmoothScroll() {
		document.addEventListener('click', function (event) {
			var link = event.target.closest('[data-rptoc-target]');
			var target;

			if (!link || settings.smoothScroll === false) {
				return;
			}

			target = document.getElementById(link.dataset.rptocTarget);

			if (!target) {
				return;
			}

			event.preventDefault();
			target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			history.pushState(null, '', '#' + target.id);
		});
	}

	function boot() {
		var allHeadings = [];

		document.querySelectorAll('[data-rptoc-toc]').forEach(function (toc) {
			allHeadings = allHeadings.concat(buildToc(toc));
		});

		observeActiveHeadings(allHeadings);
		bindSmoothScroll();
		updateProgress();
		window.addEventListener('scroll', updateProgress, { passive: true });
		window.addEventListener('resize', updateProgress);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}());
