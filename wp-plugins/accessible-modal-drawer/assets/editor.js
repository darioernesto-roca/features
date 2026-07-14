(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, RichText, useBlockProps } = blockEditor;
  const { PanelBody, SelectControl, TextControl, TextareaControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  registerBlockType('accessible-modal-drawer/trigger', {
    title: __('Accessible Modal / Drawer', 'accessible-modal-drawer'),
    icon: 'welcome-widgets-menus',
    category: 'design',
    attributes: {
      id: { type: 'string', default: 'accessible-dialog' },
      type: { type: 'string', default: 'modal' },
      template: { type: 'string', default: 'newsletter' },
      title: { type: 'string', default: 'Stay in the loop' },
      buttonText: { type: 'string', default: 'Open dialog' },
      content: { type: 'string', default: '' },
    },
    edit({ attributes, setAttributes }) {
      const blockProps = useBlockProps({ className: 'amd-dialog-wrap' });
      const previewContent = attributes.content || __('Subscribe for updates, product notes, and helpful resources delivered to your inbox.', 'accessible-modal-drawer');

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Dialog settings', 'accessible-modal-drawer') },
            el(TextControl, {
              label: __('Dialog ID', 'accessible-modal-drawer'),
              value: attributes.id,
              onChange: (id) => setAttributes({ id }),
            }),
            el(SelectControl, {
              label: __('Layout type', 'accessible-modal-drawer'),
              value: attributes.type,
              options: [
                { label: __('Modal', 'accessible-modal-drawer'), value: 'modal' },
                { label: __('Drawer', 'accessible-modal-drawer'), value: 'drawer' },
                { label: __('Side panel', 'accessible-modal-drawer'), value: 'side-panel' },
              ],
              onChange: (type) => setAttributes({ type }),
            }),
            el(SelectControl, {
              label: __('Template', 'accessible-modal-drawer'),
              value: attributes.template,
              options: [
                { label: __('Newsletter', 'accessible-modal-drawer'), value: 'newsletter' },
                { label: __('CTA', 'accessible-modal-drawer'), value: 'cta' },
                { label: __('Announcement', 'accessible-modal-drawer'), value: 'announcement' },
                { label: __('Custom', 'accessible-modal-drawer'), value: 'custom' },
              ],
              onChange: (template) => setAttributes({ template }),
            }),
            el(TextControl, {
              label: __('Button text', 'accessible-modal-drawer'),
              value: attributes.buttonText,
              onChange: (buttonText) => setAttributes({ buttonText }),
            }),
            el(TextareaControl, {
              label: __('Dialog content', 'accessible-modal-drawer'),
              value: attributes.content,
              onChange: (content) => setAttributes({ content }),
            })
          )
        ),
        el(
          'div',
          blockProps,
          el(RichText, {
            tagName: 'button',
            className: 'amd-dialog__trigger',
            value: attributes.buttonText,
            allowedFormats: [],
            onChange: (buttonText) => setAttributes({ buttonText }),
          }),
          el(
            'section',
            { className: `amd-dialog__panel amd-dialog__panel--preview amd-dialog__content--${attributes.template}` },
            el(RichText, {
              tagName: 'h2',
              className: 'amd-dialog__title',
              value: attributes.title,
              allowedFormats: [],
              onChange: (title) => setAttributes({ title }),
            }),
            el('p', {}, previewContent)
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
