( function () {
	protection();
} )();

function protection () {
	if ( accesswise.copyProtection && shouldApplyCopyProtection() ) {
		disableCopy();
	}

	if ( accesswise.disableRightClick && shouldApplyRightClickProtection() ) {
		disableRightClick();
	}
}

function shouldApplyCopyProtection () {
	return !accesswise.copyProtectionExcludeRoles.includes( accesswise.currentUserRole ) &&
		!accesswise.copyProtectionExcludePosts.includes( accesswise.currentPostType ) &&
		!accesswise.copyProtectionExcludeIds.includes( Number( accesswise.currentPostId || 0 ) );
}

function shouldApplyRightClickProtection () {
	const currentPostId = Number( accesswise.currentPostId || 0 );
	const protectedIds = accesswise.rightClickProtectIds || [];

	if ( accesswise.rightClickExcludeRoles.includes( accesswise.currentUserRole ) ) {
		return false;
	}

	if ( accesswise.rightClickExcludePosts.includes( accesswise.currentPostType ) ) {
		return false;
	}

	if ( protectedIds.length > 0 && !protectedIds.includes( currentPostId ) ) {
		return false;
	}

	return true;
}

function showMessage ( msg ) {
	if ( msg ) {
		createDismissablePopup( msg );
	}
}

function disableRightClick () {
	document.addEventListener( "contextmenu", function ( e ) {
		if ( !shouldDisableContextMenu( e ) ) {
			return;
		}

		e.preventDefault();
		showMessage( accesswise.disableRightClickMsg );
	} );

	if ( accesswise.rightClickDisableLeftClick ) {
		document.addEventListener( "click", function ( e ) {
			if ( shouldIgnoreInteraction( e ) ) {
				return;
			}

			e.preventDefault();
			e.stopPropagation();
			showMessage( accesswise.disableRightClickMsg );
		}, true );
	}

	if ( accesswise.rightClickDisableDragDrop ) {
		[ "dragstart", "drop" ].forEach( function ( eventName ) {
			document.addEventListener( eventName, function ( e ) {
				if ( shouldIgnoreInteraction( e ) ) {
					return;
				}

				e.preventDefault();
				showMessage( accesswise.disableRightClickMsg );
			} );
		} );
	}

	if ( accesswise.rightClickDisableScrollMobile ) {
		document.addEventListener( "touchmove", function ( e ) {
			if ( !isImageTarget( e.target ) ) {
				return;
			}

			e.preventDefault();
			showMessage( accesswise.disableRightClickMsg );
		}, { passive: false } );
	}

	document.addEventListener( "keydown", function ( e ) {
		if ( shouldBlockDevShortcut( e ) || shouldBlockCustomShortcut( e ) ) {
			e.preventDefault();
			showMessage( accesswise.disableRightClickMsg );
		}
	} );
}

function disableCopy () {
	[ "copy", "cut", "paste" ].forEach( function ( eventName ) {
		document.addEventListener( eventName, function ( e ) {
			if ( shouldIgnoreInteraction( e ) ) {
				return;
			}

			e.preventDefault();
			showMessage( accesswise.copyProtectionMsg );
		} );
	} );

	if ( !accesswise.copyProtectionAllowSelect ) {
		document.addEventListener( "selectstart", function ( e ) {
			if ( shouldIgnoreInteraction( e ) ) {
				return;
			}

			e.preventDefault();
			showMessage( accesswise.copyProtectionMsg );
		} );
	}
}

function shouldDisableContextMenu ( e ) {
	if ( shouldIgnoreInteraction( e ) ) {
		return false;
	}

	const target = e.target;

	if ( accesswise.rightClickDisableImages && isImageTarget( target ) ) {
		return true;
	}

	if ( accesswise.rightClickDisableLinks && target.closest( "a" ) ) {
		return true;
	}

	return !accesswise.rightClickDisableImages && !accesswise.rightClickDisableLinks;
}

function shouldIgnoreTarget ( target ) {
	if ( !target ) {
		return false;
	}

	if ( accesswise.copyProtectionExcludeInputs && isEditableTarget( target ) ) {
		return true;
	}

	return matchesExcludedSelector( target );
}

function shouldIgnoreInteraction ( e ) {
	if ( shouldIgnoreTarget( e.target ) ) {
		return true;
	}

	return matchesExcludedProtectionText( e );
}

function matchesExcludedProtectionText ( e ) {
	const excludedTexts = getExcludedProtectionTexts();

	if ( !excludedTexts.length ) {
		return false;
	}

	const selectionText = getSelectionText();
	if ( selectionText && excludedTexts.some( function ( text ) {
		return selectionText.includes( text );
	} ) ) {
		return true;
	}

	if ( e.type === "paste" && e.clipboardData ) {
		const clipboardText = e.clipboardData.getData( "text/plain" );
		if ( clipboardText && excludedTexts.some( function ( text ) {
			return clipboardText.includes( text );
		} ) ) {
			return true;
		}
	}

	const targetText = getTargetText( e.target );
	return Boolean( targetText && excludedTexts.some( function ( text ) {
		return targetText.includes( text );
	} ) );
}

function getExcludedProtectionTexts () {
	const texts = [];

	if ( accesswise.rightClickEnableCopyright && accesswise.rightClickCopyrightText ) {
		texts.push( accesswise.rightClickCopyrightText.trim() );
	}

	if ( accesswise.rightClickEnablePasting && accesswise.rightClickPastingText ) {
		texts.push( accesswise.rightClickPastingText.trim() );
	}

	return texts.filter( Boolean );
}

function getSelectionText () {
	return window.getSelection ? window.getSelection().toString().trim() : "";
}

function getTargetText ( target ) {
	if ( !target ) {
		return "";
	}

	if ( typeof target.value === "string" && target.value ) {
		return target.value.trim();
	}

	if ( typeof target.textContent === "string" && target.textContent ) {
		return target.textContent.trim();
	}

	return "";
}

function matchesExcludedSelector ( target ) {
	const selectors = accesswise.copyProtectionExcludeSelectors || [];

	if ( !selectors.length || !target.closest ) {
		return false;
	}

	return selectors.some( function ( selector ) {
		try {
			return Boolean( target.closest( selector ) );
		} catch ( error ) {
			return false;
		}
	} );
}

function isEditableTarget ( target ) {
	return Boolean(
		target.closest( "input, textarea, select, [contenteditable=''], [contenteditable='true']" )
	);
}

function isImageTarget ( target ) {
	return Boolean( target.closest( "img, picture, figure, svg, canvas" ) );
}

function shouldBlockDevShortcut ( e ) {
	if ( !accesswise.rightClickDisableDevKeys ) {
		return false;
	}

	const key = e.key.toUpperCase();
	const hasPrimaryModifier = e.ctrlKey || e.metaKey;

	if ( key === "F12" ) {
		return true;
	}

	return hasPrimaryModifier && e.shiftKey && [ "I", "J", "C" ].includes( key );
}

function shouldBlockCustomShortcut ( e ) {
	const blockedKeys = accesswise.rightClickDisableKeys || [];
	const forbiddenCtrlKeys = [];

	blockedKeys.forEach( function ( key ) {
		if ( key.startsWith( "disable_ctrl_" ) ) {
			forbiddenCtrlKeys.push( key.replace( "disable_ctrl_", "" ).toUpperCase() );
		}
	} );

	if ( ( e.ctrlKey || e.metaKey ) && forbiddenCtrlKeys.includes( e.key.toUpperCase() ) ) {
		return true;
	}

	if ( e.altKey && e.key.toUpperCase() === "D" && blockedKeys.includes( "disable_alt_d" ) ) {
		return true;
	}

	return [ "F3", "F6", "F9", "F12" ].includes( e.key ) &&
		blockedKeys.includes( "disable_" + e.key.toLowerCase() );
}

function createDismissablePopup ( msg ) {
	const existingPopup = document.getElementById( "accesswise-popup" );
	if ( existingPopup ) {
		document.body.removeChild( existingPopup );
	}

	const popup = document.createElement( "div" );
	popup.setAttribute( "id", "accesswise-popup" );
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
		if ( document.body.contains( popup ) ) {
			document.body.removeChild( popup );
		}
	} );

	window.addEventListener( "click", function ( event ) {
		if ( event.target === popup && document.body.contains( popup ) ) {
			document.body.removeChild( popup );
		}
	} );

	setTimeout( function () {
		if ( document.body.contains( popup ) ) {
			document.body.removeChild( popup );
		}
	}, 2000 );
}
