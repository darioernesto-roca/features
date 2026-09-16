(function (blocks, blockEditor, components, element, i18n) {
  const { registerBlockType } = blocks;
  const { InspectorControls, RichText, useBlockProps } = blockEditor;
  const { Button, PanelBody, SelectControl, ToggleControl } = components;
  const { createElement: el, Fragment } = element;
  const { __ } = i18n;

  const starterItems = [
    { title: __('First section', 'accessible-tabs-accordion-blocks'), content: __('Add content for the first section.', 'accessible-tabs-accordion-blocks') },
    { title: __('Second section', 'accessible-tabs-accordion-blocks'), content: __('Add content for the second section.', 'accessible-tabs-accordion-blocks') },
  ];

  const replaceItem = (items, index, changes) =>
    items.map((item, itemIndex) => (itemIndex === index ? { ...item, ...changes } : item));

  const itemEditor = (item, index, items, setAttributes, activeIndex, setActiveIndex) =>
    el(
      'div',
      { className: `atab-editor-item${activeIndex === index ? ' is-active' : ''}`, key: index },
      el(RichText, {
        tagName: 'h3',
        className: 'atab-editor-item__title',
        value: item.title,
        allowedFormats: [],
        placeholder: __('Section title…', 'accessible-tabs-accordion-blocks'),
        onFocus: () => setActiveIndex(index),
        onChange: (title) => setAttributes({ items: replaceItem(items, index, { title }) }),
      }),
      el(RichText, {
        tagName: 'div',
        className: 'atab-editor-item__content',
        value: item.content,
        placeholder: __('Section content…', 'accessible-tabs-accordion-blocks'),
        onFocus: () => setActiveIndex(index),
        onChange: (content) => setAttributes({ items: replaceItem(items, index, { content }) }),
      }),
      el(
        'div',
        { className: 'atab-editor-item__actions' },
        el(Button, {
          variant: 'secondary',
          size: 'small',
          disabled: index === 0,
          onClick: () => {
            const reordered = [...items];
            [reordered[index - 1], reordered[index]] = [reordered[index], reordered[index - 1]];
            setAttributes({ items: reordered });
            setActiveIndex(index - 1);
          },
          children: __('Move up', 'accessible-tabs-accordion-blocks'),
        }),
        el(Button, {
          variant: 'secondary',
          size: 'small',
          disabled: index === items.length - 1,
          onClick: () => {
            const reordered = [...items];
            [reordered[index], reordered[index + 1]] = [reordered[index + 1], reordered[index]];
            setAttributes({ items: reordered });
            setActiveIndex(index + 1);
          },
          children: __('Move down', 'accessible-tabs-accordion-blocks'),
        }),
        el(Button, {
          variant: 'tertiary',
          size: 'small',
          isDestructive: true,
          disabled: items.length === 1,
          onClick: () => {
            setAttributes({ items: items.filter((unused, itemIndex) => itemIndex !== index) });
            setActiveIndex(Math.max(0, index - 1));
          },
          children: __('Remove', 'accessible-tabs-accordion-blocks'),
        })
      )
    );

  const collectionEditor = (attributes, setAttributes, activeIndex, setActiveIndex, className) =>
    el(
      'div',
      useBlockProps({ className }),
      attributes.items.map((item, index) =>
        itemEditor(item, index, attributes.items, setAttributes, activeIndex, setActiveIndex)
      ),
      el(Button, {
        className: 'atab-editor-add',
        variant: 'primary',
        onClick: () => {
          const items = [
            ...attributes.items,
            {
              title: __('New section', 'accessible-tabs-accordion-blocks'),
              content: __('Add section content.', 'accessible-tabs-accordion-blocks'),
            },
          ];
          setAttributes({ items });
          setActiveIndex(items.length - 1);
        },
        children: __('Add section', 'accessible-tabs-accordion-blocks'),
      })
    );

  registerBlockType('accessible-tabs-accordion/tabs', {
    title: __('Accessible Tabs', 'accessible-tabs-accordion-blocks'),
    description: __('Keyboard-friendly horizontal or vertical tabs.', 'accessible-tabs-accordion-blocks'),
    icon: 'index-card',
    category: 'design',
    attributes: {
      items: { type: 'array', default: starterItems },
      orientation: { type: 'string', default: 'horizontal' },
      activeTab: { type: 'number', default: 0 },
    },
    edit({ attributes, setAttributes }) {
      const activeIndex = Math.min(attributes.activeTab, attributes.items.length - 1);
      const setActiveIndex = (activeTab) => setAttributes({ activeTab });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Tab settings', 'accessible-tabs-accordion-blocks') },
            el(SelectControl, {
              label: __('Orientation', 'accessible-tabs-accordion-blocks'),
              value: attributes.orientation,
              options: [
                { label: __('Horizontal', 'accessible-tabs-accordion-blocks'), value: 'horizontal' },
                { label: __('Vertical', 'accessible-tabs-accordion-blocks'), value: 'vertical' },
              ],
              onChange: (orientation) => setAttributes({ orientation }),
            }),
            el(SelectControl, {
              label: __('Initially active tab', 'accessible-tabs-accordion-blocks'),
              value: String(activeIndex),
              options: attributes.items.map((item, index) => ({ label: item.title || __('Untitled', 'accessible-tabs-accordion-blocks'), value: String(index) })),
              onChange: (value) => setActiveIndex(Number(value)),
            })
          )
        ),
        collectionEditor(attributes, setAttributes, activeIndex, setActiveIndex, `atab-editor atab-editor--tabs atab-tabs--${attributes.orientation}`)
      );
    },
    save() {
      return null;
    },
  });

  registerBlockType('accessible-tabs-accordion/accordion', {
    title: __('Accessible Accordion', 'accessible-tabs-accordion-blocks'),
    description: __('A keyboard-friendly accordion with optional FAQ structured data.', 'accessible-tabs-accordion-blocks'),
    icon: 'menu-alt3',
    category: 'design',
    attributes: {
      items: { type: 'array', default: starterItems },
      allowMultiple: { type: 'boolean', default: false },
      openItem: { type: 'number', default: 0 },
      faqSchema: { type: 'boolean', default: false },
      headingLevel: { type: 'number', default: 3 },
    },
    edit({ attributes, setAttributes }) {
      const activeIndex = Math.min(attributes.openItem, attributes.items.length - 1);
      const setActiveIndex = (openItem) => setAttributes({ openItem });

      return el(
        Fragment,
        {},
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __('Accordion settings', 'accessible-tabs-accordion-blocks') },
            el(SelectControl, {
              label: __('Initially open section', 'accessible-tabs-accordion-blocks'),
              value: String(activeIndex),
              options: attributes.items.map((item, index) => ({ label: item.title || __('Untitled', 'accessible-tabs-accordion-blocks'), value: String(index) })),
              onChange: (value) => setActiveIndex(Number(value)),
            }),
            el(SelectControl, {
              label: __('Heading level', 'accessible-tabs-accordion-blocks'),
              value: String(attributes.headingLevel),
              options: [2, 3, 4, 5, 6].map((level) => ({ label: `H${level}`, value: String(level) })),
              onChange: (value) => setAttributes({ headingLevel: Number(value) }),
            }),
            el(ToggleControl, {
              label: __('Allow multiple open sections', 'accessible-tabs-accordion-blocks'),
              checked: attributes.allowMultiple,
              onChange: (allowMultiple) => setAttributes({ allowMultiple }),
            }),
            el(ToggleControl, {
              label: __('Add FAQ structured data', 'accessible-tabs-accordion-blocks'),
              help: __('Enable only when every section is a genuine question and answer.', 'accessible-tabs-accordion-blocks'),
              checked: attributes.faqSchema,
              onChange: (faqSchema) => setAttributes({ faqSchema }),
            })
          )
        ),
        collectionEditor(attributes, setAttributes, activeIndex, setActiveIndex, 'atab-editor atab-editor--accordion')
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
