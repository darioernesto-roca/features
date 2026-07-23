(function (blocks, element, components, blockEditor, i18n) {
	'use strict';

	var el = element.createElement;
	var InspectorControls = blockEditor.InspectorControls;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;
	var PanelBody = components.PanelBody;
	var __ = i18n.__;

	blocks.registerBlockType('woocommerce-product-feature-matrix/matrix', {
		title: __('Woo Product Feature Matrix', 'woocommerce-product-feature-matrix'),
		icon: 'products',
		category: 'widgets',
		attributes: {
			title: { type: 'string', default: __('Compare products', 'woocommerce-product-feature-matrix') },
			products: { type: 'string', default: '' },
			attributes: { type: 'string', default: '' },
			showFilters: { type: 'boolean', default: true }
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
						{ title: __('Matrix settings', 'woocommerce-product-feature-matrix'), initialOpen: true },
						el(TextControl, {
							label: __('Title', 'woocommerce-product-feature-matrix'),
							value: attrs.title,
							onChange: function (value) { setAttributes({ title: value }); }
						}),
						el(TextareaControl, {
							label: __('Product IDs', 'woocommerce-product-feature-matrix'),
							help: __('Comma-separated WooCommerce product IDs, for example: 12,34,56.', 'woocommerce-product-feature-matrix'),
							value: attrs.products,
							onChange: function (value) { setAttributes({ products: value }); }
						}),
						el(TextareaControl, {
							label: __('Attribute keys', 'woocommerce-product-feature-matrix'),
							help: __('Optional comma-separated attribute keys such as color,size,pa_material.', 'woocommerce-product-feature-matrix'),
							value: attrs.attributes,
							onChange: function (value) { setAttributes({ attributes: value }); }
						}),
						el(ToggleControl, {
							label: __('Show attribute filter', 'woocommerce-product-feature-matrix'),
							checked: attrs.showFilters,
							onChange: function (value) { setAttributes({ showFilters: value }); }
						})
					)
				),
				el(
					'div',
					{ className: 'wpfm-matrix wpfm-editor-preview' },
					el('h2', { className: 'wpfm-matrix__title' }, attrs.title),
					el('p', {}, attrs.products ? __('Selected product IDs: ', 'woocommerce-product-feature-matrix') + attrs.products : __('Add product IDs to render a WooCommerce-aware comparison matrix.', 'woocommerce-product-feature-matrix')),
					el('p', {}, __('The frontend pulls product image, price, rating, stock, attributes, and add-to-cart buttons from WooCommerce.', 'woocommerce-product-feature-matrix'))
				)
			);
		},
		save: function () {
			return null;
		}
	});
}(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.i18n));
