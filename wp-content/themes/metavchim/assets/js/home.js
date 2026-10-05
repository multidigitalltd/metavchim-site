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

/**
 * הדגמת השיחה עם הסוכן בווטסאפ.
 *
 * ההודעות כתובות ב-HTML ומוצגות כרגיל; הסקריפט רק מסתיר אותן ומחזיר
 * אותן אחת-אחת עם סימן הקלדה, כדי שמי שאין לו JS עדיין רואה את כל
 * השיחה. ההנפשה רצה רק כשהאזור על המסך, ולא רצה כלל למי שביקש
 * תנועה מופחתת.
 */
( function () {
	'use strict';

	var log = document.querySelector( '.mv-wa-log' );
	if ( ! log ) {
		return;
	}

	var still = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' );
	if ( still && still.matches ) {
		return;
	}

	var steps = Array.prototype.slice.call( log.querySelectorAll( '.mv-wa-msg' ) );
	var typing = log.querySelector( '.mv-wa-typing' );
	if ( ! steps.length || ! typing ) {
		return;
	}

	var timer = null;
	var index = 0;
	var running = false;

	log.classList.add( 'is-anim' );

	function later( fn, ms ) {
		timer = window.setTimeout( fn, ms );
	}

	function toBottom() {
		log.scrollTop = log.scrollHeight;
	}

	function reset() {
		steps.forEach( function ( step ) {
			step.classList.remove( 'is-in' );
		} );
		typing.hidden = true;
		index = 0;
		log.scrollTop = 0;
	}

	function next() {
		if ( ! running ) {
			return;
		}

		if ( index >= steps.length ) {
			later( function () {
				reset();
				next();
			}, 4200 );
			return;
		}

		var step = steps[ index ];
		var fromAgent = step.classList.contains( 'is-bot' );

		function reveal() {
			typing.hidden = true;
			step.classList.add( 'is-in' );
			toBottom();
			index += 1;
			later( next, fromAgent ? 1500 : 900 );
		}

		if ( fromAgent ) {
			typing.hidden = false;
			toBottom();
			later( reveal, 1100 );
			return;
		}

		reveal();
	}

	function start() {
		if ( running ) {
			return;
		}
		running = true;
		next();
	}

	function stop() {
		running = false;
		window.clearTimeout( timer );
	}

	if ( ! ( 'IntersectionObserver' in window ) ) {
		start();
		return;
	}

	new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			if ( entry.isIntersecting ) {
				start();
			} else {
				stop();
			}
		} );
	}, { threshold: 0.25 } ).observe( log );
}() );
