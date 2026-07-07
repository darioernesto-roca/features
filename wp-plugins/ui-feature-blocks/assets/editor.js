(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, RichText, useBlockProps } = blockEditor;
  const { PanelBody, TextControl, TextareaControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  const textControl = (label, value, onChange) =>
    el(TextControl, {
      label,
      value,
      onChange,
    });

  registerBlockType('ui-feature/faq-accordion', {
    title: __('UI FAQ Accordion', 'ui-feature-blocks'),
    icon: 'editor-help',
    category: 'design',
    attributes: {
      question: { type: 'string', default: 'What makes this component reusable?' },
      answer: {
        type: 'string',
        default: 'It is rendered as a scoped WordPress block so it can be reused without modifying a theme.',
      },
    },
    edit({ attributes, setAttributes }) {
      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('FAQ content', 'ui-feature-blocks') },
            textControl(__('Question', 'ui-feature-blocks'), attributes.question, (question) =>
              setAttributes({ question })
            ),
            el(TextareaControl, {
              label: __('Answer', 'ui-feature-blocks'),
              value: attributes.answer,
              onChange: (answer) => setAttributes({ answer }),
            })
          )
        ),
        el(
          'details',
          { ...useBlockProps({ className: 'wp-block-ui-feature-faq' }), open: true },
          el(RichText, {
            tagName: 'summary',
            value: attributes.question,
            allowedFormats: [],
            onChange: (question) => setAttributes({ question }),
          }),
          el(RichText, {
            tagName: 'div',
            className: 'wp-block-ui-feature-faq__answer',
            value: attributes.answer,
            onChange: (answer) => setAttributes({ answer }),
          })
        )
      );
    },
    save() {
      return null;
    },
  });

  registerBlockType('ui-feature/pricing-card', {
    title: __('UI Pricing Card', 'ui-feature-blocks'),
    icon: 'money-alt',
    category: 'design',
    attributes: {
      planName: { type: 'string', default: 'Starter' },
      price: { type: 'string', default: '$19' },
      description: { type: 'string', default: 'A focused plan for small teams launching quickly.' },
      buttonText: { type: 'string', default: 'Get started' },
    },
    edit({ attributes, setAttributes }) {
      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Pricing content', 'ui-feature-blocks') },
            textControl(__('Plan name', 'ui-feature-blocks'), attributes.planName, (planName) =>
              setAttributes({ planName })
            ),
            textControl(__('Price', 'ui-feature-blocks'), attributes.price, (price) =>
              setAttributes({ price })
            ),
            textControl(__('Button text', 'ui-feature-blocks'), attributes.buttonText, (buttonText) =>
              setAttributes({ buttonText })
            )
          )
        ),
        el(
          'section',
          useBlockProps({ className: 'wp-block-ui-feature-pricing' }),
          el('p', { className: 'wp-block-ui-feature-pricing__eyebrow' }, __('Plan', 'ui-feature-blocks')),
          el(RichText, {
            tagName: 'h2',
            value: attributes.planName,
            allowedFormats: [],
            onChange: (planName) => setAttributes({ planName }),
          }),
          el(RichText, {
            tagName: 'p',
            className: 'wp-block-ui-feature-pricing__price',
            value: attributes.price,
            allowedFormats: [],
            onChange: (price) => setAttributes({ price }),
          }),
          el(RichText, {
            tagName: 'p',
            value: attributes.description,
            onChange: (description) => setAttributes({ description }),
          }),
          el(RichText, {
            tagName: 'span',
            className: 'wp-block-ui-feature-pricing__button',
            value: attributes.buttonText,
            allowedFormats: [],
            onChange: (buttonText) => setAttributes({ buttonText }),
          })
        )
      );
    },
    save() {
      return null;
    },
  });

  registerBlockType('ui-feature/testimonial', {
    title: __('UI Testimonial', 'ui-feature-blocks'),
    icon: 'format-quote',
    category: 'design',
    attributes: {
      quote: {
        type: 'string',
        default: 'This block helped us add a polished section without custom theme work.',
      },
      name: { type: 'string', default: 'Alex Morgan' },
      role: { type: 'string', default: 'Marketing Lead' },
    },
    edit({ attributes, setAttributes }) {
      return el(
        'figure',
        useBlockProps({ className: 'wp-block-ui-feature-testimonial' }),
        el(RichText, {
          tagName: 'blockquote',
          value: attributes.quote,
          onChange: (quote) => setAttributes({ quote }),
        }),
        el(
          'figcaption',
          {},
          el(RichText, {
            tagName: 'strong',
            value: attributes.name,
            allowedFormats: [],
            onChange: (name) => setAttributes({ name }),
          }),
          el(RichText, {
            tagName: 'span',
            value: attributes.role,
            allowedFormats: [],
            onChange: (role) => setAttributes({ role }),
          })
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
