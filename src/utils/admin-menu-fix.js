/**
 * As we are using hash based navigation, hack fix
 * to highlight the current selected menu
 *
 * Requires jQuery
 */
function menuFix ( slug ) {
	var $ = jQuery;

	let menuRoot = $( '#toplevel_page_' + slug );
	let currentUrl = window.location.href;
	let currentPath = currentUrl.substring( currentUrl.indexOf( 'admin.php' ) );

	menuRoot.off( 'click', 'a' ).on( 'click', 'a', function () {
		var self = $( this );

		$( 'ul.wp-submenu li', menuRoot ).removeClass( 'current' );

		if ( self.hasClass( 'wp-has-submenu' ) ) {
			$( 'li.wp-first-item', menuRoot ).addClass( 'current' );
		} else {
			self.parents( 'li' ).addClass( 'current' );
		}
	} );

	$( 'ul.wp-submenu li', menuRoot ).removeClass( 'current' );

	let matchFound = false;

	// Exact match check first
	$( 'ul.wp-submenu a', menuRoot ).each( function ( index, el ) {
		if ( $( el ).attr( 'href' ) === currentPath ) {
			$( el ).parent().addClass( 'current' );
			matchFound = true;
			return false; // break the each loop
		}
	} );

	// Sub-route match check (e.g. /settings/general -> /settings)
	if ( !matchFound ) {
		$( 'ul.wp-submenu a', menuRoot ).each( function ( index, el ) {
			let href = $( el ).attr( 'href' );
			
			// Exclude the root route #/ from partial matches
			if ( href.endsWith( '#/' ) ) {
				return;
			}

			if ( currentPath.startsWith( href + '/' ) ) {
				$( el ).parent().addClass( 'current' );
				matchFound = true;
				return false; // break the each loop
			}
		} );
	}

	if ( !matchFound ) {
		$( 'li.wp-first-item', menuRoot ).addClass( 'current' );
	}
}

export default menuFix;