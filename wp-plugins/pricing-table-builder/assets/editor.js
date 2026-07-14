(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, useBlockProps } = blockEditor;
  const { PanelBody, TextControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  const control = (label, value, onChange, help) =>
    el(TextControl, {
      label,
      value,
      help,
      onChange,
    });

  registerBlockType('pricing-table-builder/table', {
    title: __('Pricing Table Builder', 'pricing-table-builder'),
    icon: 'money-alt',
    category: 'widgets',
    attributes: {
      title: { type: 'string', default: 'Choose your plan' },
      description: { type: 'string', default: 'Switch between monthly and yearly pricing, then compare included features.' },
      currency: { type: 'string', default: '$' },
      monthlyLabel: { type: 'string', default: '/mo' },
      yearlyLabel: { type: 'string', default: '/yr' },
      plans: { type: 'string', default: 'Starter|Growth|Scale' },
      monthlyPrices: { type: 'string', default: '19|49|99' },
      yearlyPrices: { type: 'string', default: '190|490|990' },
      descriptions: { type: 'string', default: 'Launch quickly|Grow with advanced tools|Scale with premium support' },
      ctaTexts: { type: 'string', default: 'Start now|Choose Growth|Contact sales' },
      ctaUrls: { type: 'string', default: '#|#|#' },
      productIds: { type: 'string', default: '' },
      featured: { type: 'string', default: '1' },
      ribbon: { type: 'string', default: 'Most Popular' },
      features: { type: 'string', default: 'Projects:5|25|Unlimited;Support:Email|Priority|Dedicated;Storage:10GB|100GB|1TB' },
    },
    edit({ attributes, setAttributes }) {
      const blockProps = useBlockProps({ className: 'ptb-pricing' });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Pricing table settings', 'pricing-table-builder') },
            control(__('Title', 'pricing-table-builder'), attributes.title, (title) => setAttributes({ title })),
            control(__('Description', 'pricing-table-builder'), attributes.description, (description) => setAttributes({ description })),
            control(__('Currency', 'pricing-table-builder'), attributes.currency, (currency) => setAttributes({ currency })),
            control(__('Plan names', 'pricing-table-builder'), attributes.plans, (plans) => setAttributes({ plans }), __('Pipe-separated values.', 'pricing-table-builder')),
            control(__('Monthly prices', 'pricing-table-builder'), attributes.monthlyPrices, (monthlyPrices) => setAttributes({ monthlyPrices })),
            control(__('Yearly prices', 'pricing-table-builder'), attributes.yearlyPrices, (yearlyPrices) => setAttributes({ yearlyPrices })),
            control(__('Plan descriptions', 'pricing-table-builder'), attributes.descriptions, (descriptions) => setAttributes({ descriptions })),
            control(__('CTA texts', 'pricing-table-builder'), attributes.ctaTexts, (ctaTexts) => setAttributes({ ctaTexts })),
            control(__('CTA URLs', 'pricing-table-builder'), attributes.ctaUrls, (ctaUrls) => setAttributes({ ctaUrls })),
            control(__('WooCommerce product IDs', 'pricing-table-builder'), attributes.productIds, (productIds) => setAttributes({ productIds }), __('Optional pipe-separated product IDs.', 'pricing-table-builder')),
            control(__('Featured plan index', 'pricing-table-builder'), attributes.featured, (featured) => setAttributes({ featured }), __('Zero-based index. Use 1 for the second plan.', 'pricing-table-builder')),
            control(__('Featured ribbon', 'pricing-table-builder'), attributes.ribbon, (ribbon) => setAttributes({ ribbon })),
            control(__('Feature rows', 'pricing-table-builder'), attributes.features, (features) => setAttributes({ features }), __('Use Feature:Plan 1|Plan 2;Other:Yes|No syntax.', 'pricing-table-builder'))
          )
        ),
        el(
          'section',
          blockProps,
          el('header', { className: 'ptb-pricing__header' }, el('h2', {}, attributes.title), el('p', {}, attributes.description)),
          el('p', {}, __('Pricing cards and comparison rows render on the frontend using these settings.', 'pricing-table-builder'))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
