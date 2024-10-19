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
		createDismissablePopup( msg );
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

function createDismissablePopup ( msg ) {
	const existingPopup = document.getElementById( "accesswise-popup" );
	if ( existingPopup ) {
		document.body.removeChild( existingPopup );
	}

	const popup = document.createElement( "div" );
	popup.setAttribute( 'id', 'accesswise-popup' );
	popup.style.position = "fixed";
	popup.style.left = "50%";
	popup.style.top = "98%";
	popup.style.transform = "translate(-50%, -100%)";
	popup.style.backgroundColor = "black";
	popup.style.color = "white";
	popup.style.border = "1px solid #ccc";
	popup.style.borderRadius = "5px";
	popup.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.1)";
	popup.style.zIndex = "1000";
	popup.style.padding = "10px";
	popup.style.display = "none";

	const popupContent = document.createElement( "div" );
	popupContent.style.display = "flex";
	popupContent.style.flexDirection = "row";
	popupContent.style.justifyContent = "center";
	popupContent.style.alignItems = "center";
	popupContent.style.gap = "10px";
	const closeBtn = document.createElement( "span" );
	closeBtn.textContent = "×";
	closeBtn.style.cursor = "pointer";
	closeBtn.style.fontSize = "20px";

	const message = document.createElement( "p" );
	message.style.margin = 0;
	message.textContent = msg;

	popupContent.appendChild( message );
	popupContent.appendChild( closeBtn );
	popup.appendChild( popupContent );
	document.body.appendChild( popup );

	popup.style.display = "block";

	closeBtn.addEventListener( "click", function () {
		document.body.removeChild( popup );
	} );

	window.addEventListener( "click", function ( event ) {
		if ( event.target === popup ) {
			document.body.removeChild( popup );
		}
	} );

	setTimeout( function () {
		if ( document.body.contains( popup ) ) {
			document.body.removeChild( popup );
		}
	}, 2000 );
}