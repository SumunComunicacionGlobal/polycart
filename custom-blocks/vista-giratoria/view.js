( function () {
	const INTERVAL = 500;

	function init( block ) {
		const frames = block.querySelectorAll( '.vista-giratoria__frame' );
		if ( frames.length < 2 ) {
			return;
		}

		let current = 0;
		setInterval( () => {
			frames[ current ].classList.remove( 'is-active' );
			current = ( current + 1 ) % frames.length;
			frames[ current ].classList.add( 'is-active' );
		}, INTERVAL );
	}

	function run() {
		document.querySelectorAll( '.vista-giratoria' ).forEach( init );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', run );
	} else {
		run();
	}
} )();
