(function (blocks, element, components, blockEditor, i18n) {
	'use strict';

	var el = element.createElement;
	var InspectorControls = blockEditor.InspectorControls;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;
	var PanelBody = components.PanelBody;
	var __ = i18n.__;

	blocks.registerBlockType('micro-survey-feedback/widget', {
		title: __('Micro Survey Feedback', 'micro-survey-feedback'),
		icon: 'feedback',
		category: 'widgets',
		attributes: {
			title: { type: 'string', default: __('Was this helpful?', 'micro-survey-feedback') },
			description: { type: 'string', default: __('Share a quick reaction so we can improve this page.', 'micro-survey-feedback') },
			comment: { type: 'boolean', default: true },
			toast: { type: 'boolean', default: true },
			thankYou: { type: 'string', default: __('Thanks for the feedback!', 'micro-survey-feedback') }
		},
		edit: function (props) {
			var attrs = props.attributes;
			var setAttributes = props.setAttributes;

			return el(
				'fragment',
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Feedback settings', 'micro-survey-feedback'), initialOpen: true },
						el(TextControl, {
							label: __('Title', 'micro-survey-feedback'),
							value: attrs.title,
							onChange: function (value) { setAttributes({ title: value }); }
						}),
						el(TextareaControl, {
							label: __('Description', 'micro-survey-feedback'),
							value: attrs.description,
							onChange: function (value) { setAttributes({ description: value }); }
						}),
						el(ToggleControl, {
							label: __('Show optional comment field', 'micro-survey-feedback'),
							checked: attrs.comment,
							onChange: function (value) { setAttributes({ comment: value }); }
						}),
						el(ToggleControl, {
							label: __('Trigger thank-you toast when available', 'micro-survey-feedback'),
							checked: attrs.toast,
							onChange: function (value) { setAttributes({ toast: value }); }
						}),
						el(TextControl, {
							label: __('Thank-you message', 'micro-survey-feedback'),
							value: attrs.thankYou,
							onChange: function (value) { setAttributes({ thankYou: value }); }
						})
					)
				),
				el(
					'div',
					{ className: 'msfw-widget msfw-editor-preview' },
					el(
						'div',
						{ className: 'msfw-card' },
						el('h2', { className: 'msfw-title' }, attrs.title),
						el('p', { className: 'msfw-description' }, attrs.description),
						el(
							'div',
							{ className: 'msfw-emoji-group' },
							['😞', '😐', '🙂', '😍'].map(function (emoji) {
								return el('span', { className: 'msfw-emoji', key: emoji }, emoji);
							})
						),
						el('p', { className: 'msfw-status' }, __('Submissions are saved with the current page/post association.', 'micro-survey-feedback'))
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});
}(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.i18n));
