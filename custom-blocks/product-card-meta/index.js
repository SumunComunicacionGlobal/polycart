(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/product-card-meta', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'product-card-meta product-card-meta--editor' });

            return el(
                'div',
                blockProps,
                el('span', { className: 'product-card-meta__placeholder' }, 'Product card meta')
            );
        },
        save: function () {
            return null;
        }
    });
}());
