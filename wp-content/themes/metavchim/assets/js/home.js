/**
 * Metavchim — homepage demo tabs (accessible tablist, vanilla JS, deferred).
 */
( function () {
	'use strict';

	var tablist = document.querySelector( '.mv-tabs' );
	if ( ! tablist ) {
		return;
	}

	var tabs = Array.prototype.slice.call( tablist.querySelectorAll( '.mv-tab' ) );
	var panels = tabs.map( function ( tab ) {
		return document.getElementById( tab.getAttribute( 'aria-controls' ) );
	} );

	function select( index, focus ) {
		tabs.forEach( function ( tab, i ) {
			var on = i === index;
			tab.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			tab.tabIndex = on ? 0 : -1;
			if ( panels[ i ] ) {
				panels[ i ].hidden = ! on;
			}
		} );
		if ( focus ) {
			tabs[ index ].focus();
		}
	}

	tabs.forEach( function ( tab, i ) {
		tab.addEventListener( 'click', function () {
			select( i, false );
		} );
		tab.addEventListener( 'keydown', function ( e ) {
			var next = null;
			if ( 'ArrowLeft' === e.key || 'ArrowDown' === e.key ) {
				next = ( i + 1 ) % tabs.length; // RTL: left moves forward.
			} else if ( 'ArrowRight' === e.key || 'ArrowUp' === e.key ) {
				next = ( i - 1 + tabs.length ) % tabs.length;
			} else if ( 'Home' === e.key ) {
				next = 0;
			} else if ( 'End' === e.key ) {
				next = tabs.length - 1;
			}
			if ( null !== next ) {
				e.preventDefault();
				select( next, true );
			}
		} );
	} );

	select( 0, false );
}() );
