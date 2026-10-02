(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/product-data', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'wp-block-product-data' });

            return el(
                'div',
                blockProps,
                el('div', { className: 'product-data__grid' },
                    el('div', { className: 'product-data__item' },
                        el('span', { className: 'product-data__label' }, 'Label'),
                        el('span', { className: 'product-data__value' }, 'Valor')
                    ),
                    el('div', { className: 'product-data__item' },
                        el('span', { className: 'product-data__label' }, 'Label'),
                        el('span', { className: 'product-data__value' }, 'Valor')
                    ),
                    el('div', { className: 'product-data__item' },
                        el('span', { className: 'product-data__label' }, 'Label'),
                        el('span', { className: 'product-data__value' }, 'Valor')
                    )
                )
            );
        },
        save: function () {
            return null;
        }
    });
}());
