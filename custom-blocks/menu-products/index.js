(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/menu-products', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'wp-block-menu-products' });

            return el(
                'div',
                blockProps,
                el('div', { className: 'menu-products' }, 'Menú Productos'
                )
            );
        },
        save: function () {
            return null;
        }
    });
}());
