(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { createElement: el } = wp.element;

    registerBlockType('smn/color-spot', {
        edit: function () {
            const blockProps = useBlockProps({ className: 'wp-block-color-spot' });

            return el(
                'div',
                blockProps,
                el('span', { className: 'color-spot__circle' })
            );
        },
        save: function () {
            return null;
        }
    });
}());
