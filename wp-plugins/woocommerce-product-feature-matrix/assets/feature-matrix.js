(function () {
	'use strict';

	function bindMatrix(matrix) {
		var filter = matrix.querySelector('[data-wpfm-filter]');
		var rows = Array.prototype.slice.call(matrix.querySelectorAll('[data-wpfm-row]'));

		if (!filter || !rows.length) {
			return;
		}

		filter.addEventListener('change', function () {
			var value = filter.value;

			rows.forEach(function (row) {
				var isCore = row.dataset.wpfmRow.indexOf('core-') === 0;
				row.hidden = value !== 'all' && row.dataset.wpfmRow !== value && !isCore;
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-wpfm-matrix]').forEach(bindMatrix);
	});
}());
