(function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps } = wp.blockEditor;
	const { useSelect } = wp.data;
	const { createElement: el } = wp.element;
	const ServerSideRender = wp.serverSideRender;

	registerBlockType('smn/related-content', {
		edit: function () {
			const blockProps = useBlockProps();
			const postId = useSelect(function (select) {
				const editor = select('core/editor');
				const currentPost = editor.getCurrentPost();

				return (currentPost && (currentPost.wp_id || currentPost.id)) || editor.getCurrentPostId();
			}, []);

			return el(
				'div',
				blockProps,
				el(ServerSideRender, {
					block: 'smn/related-content',
					urlQueryArgs: { post_id: postId }
				})
			);
		},
		save: function () {
			return null;
		}
	});
}());