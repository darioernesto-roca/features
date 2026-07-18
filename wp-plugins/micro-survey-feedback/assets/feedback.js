(function () {
	'use strict';

	var settings = window.MicroSurveyFeedback || {};

	function showStatus(form, message, isError) {
		var status = form.querySelector('[data-msfw-status]');

		if (!status) {
			return;
		}

		status.textContent = message;
		status.classList.toggle('is-error', Boolean(isError));
	}

	function maybeShowToast(form, message) {
		if (form.dataset.msfwToast !== 'true') {
			return;
		}

		if (window.NotificationToast && typeof window.NotificationToast.show === 'function') {
			window.NotificationToast.show({
				type: 'success',
				message: message,
				duration: 4000
			});
		}
	}

	function bindWidget(form) {
		var reactionInput = form.querySelector('[data-msfw-reaction-input]');
		var submit = form.querySelector('.msfw-submit');

		form.querySelectorAll('[data-msfw-reaction]').forEach(function (button) {
			button.addEventListener('click', function () {
				form.querySelectorAll('[data-msfw-reaction]').forEach(function (item) {
					item.classList.remove('is-active');
					item.setAttribute('aria-pressed', 'false');
				});

				button.classList.add('is-active');
				button.setAttribute('aria-pressed', 'true');

				if (reactionInput) {
					reactionInput.value = button.dataset.msfwReaction || '';
				}
			});
		});

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!settings.ajaxUrl || !settings.nonce) {
				showStatus(form, 'Feedback is not available right now.', true);
				return;
			}

			var data = new FormData(form);
			data.append('action', 'msfw_submit_feedback');
			data.append('nonce', settings.nonce);

			if (submit) {
				submit.disabled = true;
			}

			fetch(settings.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: data
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (payload) {
					var fallback = form.dataset.msfwThankYou || 'Thanks for the feedback!';
					var message = payload && payload.data && payload.data.message ? payload.data.message : fallback;

					if (!payload || !payload.success) {
						showStatus(form, message, true);
						return;
					}

					showStatus(form, fallback, false);
					maybeShowToast(form, fallback);
					form.reset();
					form.querySelectorAll('[data-msfw-reaction]').forEach(function (button) {
						button.classList.remove('is-active');
						button.setAttribute('aria-pressed', 'false');
					});
				})
				.catch(function () {
					showStatus(form, 'Feedback could not be submitted. Please try again.', true);
				})
				.finally(function () {
					if (submit) {
						submit.disabled = false;
					}
				});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-msfw-widget]').forEach(bindWidget);
	});
}());
