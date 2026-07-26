(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, RichText, useBlockProps } = blockEditor;
  const { PanelBody, SelectControl, TextControl, ToggleControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  registerBlockType('content-reveal-spoiler/reveal', {
    title: __('Content Reveal / Spoiler', 'content-reveal-spoiler'),
    icon: 'hidden',
    category: 'design',
    attributes: {
      label: { type: 'string', default: 'Show hidden content' },
      hideLabel: { type: 'string', default: 'Hide content' },
      style: { type: 'string', default: 'panel' },
      open: { type: 'boolean', default: false },
      oneWay: { type: 'boolean', default: false },
      screenLabel: { type: 'string', default: '' },
      content: { type: 'string', default: '' },
    },
    edit({ attributes, setAttributes }) {
      const blockProps = useBlockProps({ className: `crs-reveal crs-reveal--${attributes.style} is-open` });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Reveal settings', 'content-reveal-spoiler') },
            el(TextControl, {
              label: __('Reveal button label', 'content-reveal-spoiler'),
              value: attributes.label,
              onChange: (label) => setAttributes({ label }),
            }),
            el(TextControl, {
              label: __('Hide button label', 'content-reveal-spoiler'),
              value: attributes.hideLabel,
              onChange: (hideLabel) => setAttributes({ hideLabel }),
            }),
            el(TextControl, {
              label: __('Assistive content label', 'content-reveal-spoiler'),
              help: __('Optional context announced before the revealed content.', 'content-reveal-spoiler'),
              value: attributes.screenLabel,
              onChange: (screenLabel) => setAttributes({ screenLabel }),
            }),
            el(SelectControl, {
              label: __('Presentation', 'content-reveal-spoiler'),
              value: attributes.style,
              options: [
                { label: __('Hidden panel', 'content-reveal-spoiler'), value: 'panel' },
                { label: __('Blurred spoiler', 'content-reveal-spoiler'), value: 'blur' },
              ],
              onChange: (style) => setAttributes({ style }),
            }),
            el(ToggleControl, {
              label: __('Initially open', 'content-reveal-spoiler'),
              checked: attributes.open,
              onChange: (open) => setAttributes({ open }),
            }),
            el(ToggleControl, {
              label: __('One-way reveal', 'content-reveal-spoiler'),
              help: __('Remove the button after the content is revealed.', 'content-reveal-spoiler'),
              checked: attributes.oneWay,
              onChange: (oneWay) => setAttributes({ oneWay }),
            })
          )
        ),
        el(
          'div',
          blockProps,
          el('button', { className: 'crs-reveal__trigger', type: 'button' }, attributes.label),
          el(
            'div',
            { className: 'crs-reveal__content' },
            el(RichText, {
              tagName: 'div',
              value: attributes.content,
              placeholder: __('Add content to reveal…', 'content-reveal-spoiler'),
              onChange: (content) => setAttributes({ content }),
            })
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
