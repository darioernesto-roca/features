(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, useBlockProps } = blockEditor;
  const { PanelBody, TextControl, ToggleControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  registerBlockType('testimonial-carousel/carousel', {
    title: __('Testimonial Carousel', 'testimonial-carousel'),
    icon: 'format-quote',
    category: 'widgets',
    attributes: {
      title: { type: 'string', default: 'What clients say' },
      limit: { type: 'string', default: '6' },
      autoplay: { type: 'string', default: 'true' },
      interval: { type: 'string', default: '5000' },
      schema: { type: 'string', default: 'true' },
      schemaItemName: { type: 'string', default: '' },
    },
    edit({ attributes, setAttributes }) {
      const blockProps = useBlockProps({ className: 'tc-carousel' });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Carousel settings', 'testimonial-carousel') },
            el(TextControl, {
              label: __('Title', 'testimonial-carousel'),
              value: attributes.title,
              onChange: (title) => setAttributes({ title }),
            }),
            el(TextControl, {
              label: __('Testimonial limit', 'testimonial-carousel'),
              value: attributes.limit,
              onChange: (limit) => setAttributes({ limit }),
            }),
            el(TextControl, {
              label: __('Autoplay interval', 'testimonial-carousel'),
              help: __('Milliseconds between slides.', 'testimonial-carousel'),
              value: attributes.interval,
              onChange: (interval) => setAttributes({ interval }),
            }),
            el(TextControl, {
              label: __('Schema item name', 'testimonial-carousel'),
              value: attributes.schemaItemName,
              onChange: (schemaItemName) => setAttributes({ schemaItemName }),
            }),
            el(ToggleControl, {
              label: __('Enable autoplay', 'testimonial-carousel'),
              checked: attributes.autoplay === 'true',
              onChange: (autoplay) => setAttributes({ autoplay: autoplay ? 'true' : 'false' }),
            }),
            el(ToggleControl, {
              label: __('Output review schema', 'testimonial-carousel'),
              checked: attributes.schema === 'true',
              onChange: (schema) => setAttributes({ schema: schema ? 'true' : 'false' }),
            })
          )
        ),
        el(
          'section',
          blockProps,
          el('header', { className: 'tc-carousel__header' }, el('h2', {}, attributes.title)),
          el('p', {}, __('Published testimonial posts will render here on the frontend.', 'testimonial-carousel'))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
