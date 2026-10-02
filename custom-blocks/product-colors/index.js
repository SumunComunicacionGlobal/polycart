(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/product-colors', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'product-colors product-colors--editor' });

            return el(
                'div',
                blockProps,
                el('span', { className: 'product-colors__placeholder' }, 'Product colors')
            );
        },
        save: function () {
            return null;
        }
    });
}());
