(function () {
    const { registerBlockType } = wp.blocks;
    const { useBlockProps } = wp.blockEditor;
    const { useSelect } = wp.data;
    const { createElement: el } = wp.element;
    const ServerSideRender = wp.serverSideRender;

    registerBlockType('smn/product-image-gallery', {
        edit: function (props) {
            const blockProps = useBlockProps();
            const editorPostId = useSelect(function (select) {
                const editor = select('core/editor');
                const currentPost = editor && editor.getCurrentPost ? editor.getCurrentPost() : null;
                const currentId = (currentPost && (currentPost.wp_id || currentPost.id)) ||
                    (editor && editor.getCurrentPostId ? editor.getCurrentPostId() : 0);
                const numericId = Number(currentId);
                return Number.isInteger(numericId) && numericId > 0 ? numericId : 0;
            }, []);
            const contextPostId = Number(props.context && props.context.postId);
            const postId = Number.isInteger(contextPostId) && contextPostId > 0 ? contextPostId : editorPostId;

            return el('div', blockProps, el(ServerSideRender, {
                block: 'smn/product-image-gallery',
                attributes: props.attributes,
                urlQueryArgs: { post_id: postId },
            }));
        },
        save: function () {
            return null;
        },
    });
}());