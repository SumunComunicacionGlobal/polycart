(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/product-colors-categorized', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'product-colors-categorized product-colors-categorized--editor' });

            return el(
                'div',
                blockProps,
                el('span', { className: 'product-colors-categorized__placeholder' }, 'Product colors by category')
            );
        },
        save: function () {
            return null;
        }
    });
}());
