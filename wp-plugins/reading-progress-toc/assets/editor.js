(function (blocks, element, components, blockEditor, i18n) {
	'use strict';

	var el = element.createElement;
	var InspectorControls = blockEditor.InspectorControls;
	var TextControl = components.TextControl;
	var RangeControl = components.RangeControl;
	var PanelBody = components.PanelBody;
	var __ = i18n.__;

	blocks.registerBlockType('reading-progress-toc/table-of-contents', {
		title: __('Reading Table of Contents', 'reading-progress-toc'),
		icon: 'list-view',
		category: 'widgets',
		attributes: {
			title: { type: 'string', default: __('On this page', 'reading-progress-toc') },
			selector: { type: 'string', default: '.entry-content, .wp-site-blocks main, article' },
			minLevel: { type: 'number', default: 2 },
			maxLevel: { type: 'number', default: 3 },
			placeholder: { type: 'string', default: __('Headings will appear here on the front end.', 'reading-progress-toc') }
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
						{ title: __('TOC settings', 'reading-progress-toc'), initialOpen: true },
						el(TextControl, {
							label: __('Title', 'reading-progress-toc'),
							value: attrs.title,
							onChange: function (value) { setAttributes({ title: value }); }
						}),
						el(TextControl, {
							label: __('Content selector', 'reading-progress-toc'),
							value: attrs.selector,
							onChange: function (value) { setAttributes({ selector: value }); }
						}),
						el(RangeControl, {
							label: __('Minimum heading level', 'reading-progress-toc'),
							min: 1,
							max: 6,
							value: attrs.minLevel,
							onChange: function (value) { setAttributes({ minLevel: value }); }
						}),
						el(RangeControl, {
							label: __('Maximum heading level', 'reading-progress-toc'),
							min: attrs.minLevel,
							max: 6,
							value: attrs.maxLevel,
							onChange: function (value) { setAttributes({ maxLevel: value }); }
						})
					)
				),
				el(
					'nav',
					{ className: 'rptoc-toc rptoc-editor-preview' },
					el('h2', { className: 'rptoc-toc__title' }, attrs.title),
					el('p', { className: 'rptoc-toc__placeholder' }, attrs.placeholder),
					el(
						'ol',
						{ className: 'rptoc-toc__list' },
						el('li', { className: 'rptoc-toc__item' }, __('Generated heading links display on the front end.', 'reading-progress-toc'))
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});
}(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.i18n));
