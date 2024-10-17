( function ( $, w ) {
	protection();
} )( jQuery, window );

function protection () {
	if ( accesswise.disableCopy ) {
		disableCopy();
	}
	if ( accesswise.disableRightClick ) {
		disableRightClick();
	}
}

function showMessage ( msg ) {
	if ( msg && msg !== '' ) {
		alert( msg );
	}
}

function disableRightClick () {
	document.addEventListener( 'contextmenu', function ( e ) {
		e.preventDefault();
		showMessage( accesswise.disableRightClickMsg );
	} );
}

function disableCopy () {
	document.addEventListener( 'selectstart', function ( e ) {
		e.preventDefault();
		showMessage( accesswise.disableCopyMsg );
	} );

	document.addEventListener( 'dragstart', function ( e ) {
		e.preventDefault();
		showMessage( accesswise.disableCopyMsg );
	} );

	document.addEventListener( 'keydown', function ( e ) {
		const forbiddenKeys = [ 'U', 'S', 'C', 'X' ];

		if ( ( e.ctrlKey || e.metaKey ) && forbiddenKeys.includes( e.key.toUpperCase() ) ) {
			e.preventDefault();
			showMessage( accesswise.disableCopyMsg );
		}

		if ( e.key === 'F12' ) {
			e.preventDefault();
			showMessage( accesswise.disableCopyMsg );
		}
	} );
}