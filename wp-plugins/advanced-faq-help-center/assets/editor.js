(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, useBlockProps } = blockEditor;
  const { PanelBody, SelectControl, TextControl, ToggleControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  registerBlockType('advanced-faq-help-center/help-center', {
    title: __('Advanced FAQ Help Center', 'advanced-faq-help-center'),
    icon: 'editor-help',
    category: 'widgets',
    attributes: {
      title: { type: 'string', default: 'Help Center' },
      description: { type: 'string', default: 'Browse common questions and answers.' },
      category: { type: 'string', default: '' },
      limit: { type: 'string', default: '12' },
      layout: { type: 'string', default: 'grouped' },
      search: { type: 'string', default: 'true' },
      schema: { type: 'string', default: 'true' },
    },
    edit({ attributes, setAttributes }) {
      const blockProps = useBlockProps({ className: `afhc-help-center afhc-help-center--${attributes.layout}` });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Help center settings', 'advanced-faq-help-center') },
            el(TextControl, {
              label: __('Title', 'advanced-faq-help-center'),
              value: attributes.title,
              onChange: (title) => setAttributes({ title }),
            }),
            el(TextControl, {
              label: __('Description', 'advanced-faq-help-center'),
              value: attributes.description,
              onChange: (description) => setAttributes({ description }),
            }),
            el(TextControl, {
              label: __('Category slugs', 'advanced-faq-help-center'),
              help: __('Optional comma-separated FAQ category slugs.', 'advanced-faq-help-center'),
              value: attributes.category,
              onChange: (category) => setAttributes({ category }),
            }),
            el(TextControl, {
              label: __('FAQ limit', 'advanced-faq-help-center'),
              value: attributes.limit,
              onChange: (limit) => setAttributes({ limit }),
            }),
            el(SelectControl, {
              label: __('Layout', 'advanced-faq-help-center'),
              value: attributes.layout,
              options: [
                { label: __('Grouped', 'advanced-faq-help-center'), value: 'grouped' },
                { label: __('Single list', 'advanced-faq-help-center'), value: 'single' },
              ],
              onChange: (layout) => setAttributes({ layout }),
            }),
            el(ToggleControl, {
              label: __('Enable search/filter UI', 'advanced-faq-help-center'),
              checked: attributes.search === 'true',
              onChange: (search) => setAttributes({ search: search ? 'true' : 'false' }),
            }),
            el(ToggleControl, {
              label: __('Output FAQ schema', 'advanced-faq-help-center'),
              checked: attributes.schema === 'true',
              onChange: (schema) => setAttributes({ schema: schema ? 'true' : 'false' }),
            })
          )
        ),
        el(
          'section',
          blockProps,
          el('header', { className: 'afhc-help-center__header' }, el('h2', {}, attributes.title), el('p', {}, attributes.description)),
          el('p', {}, __('FAQ posts will render here on the frontend using the selected settings.', 'advanced-faq-help-center'))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
