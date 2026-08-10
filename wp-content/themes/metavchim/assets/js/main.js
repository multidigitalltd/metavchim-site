/**
 * Metavchim — site-wide behavior (vanilla JS, no dependencies, deferred).
 * 1. Mobile nav toggle
 * 2. Scroll reveal via IntersectionObserver (content visible without JS)
 * 3. Accessibility toolbar (persists preferences in localStorage)
 */
( function () {
	'use strict';

	var doc = document.documentElement;
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ---------- 1. Mobile nav ---------- */
	var navToggle = document.querySelector( '.mv-nav-toggle' );
	var nav = document.getElementById( 'mv-nav' );
	if ( navToggle && nav ) {
		navToggle.addEventListener( 'click', function () {
			var open = nav.classList.toggle( 'is-open' );
			navToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
		nav.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( 'a' ) ) {
				nav.classList.remove( 'is-open' );
				navToggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key && nav.classList.contains( 'is-open' ) ) {
				nav.classList.remove( 'is-open' );
				navToggle.setAttribute( 'aria-expanded', 'false' );
				navToggle.focus();
			}
		} );
	}

	/* ---------- 2. Scroll reveal ---------- */
	if ( ! reduceMotion && 'IntersectionObserver' in window ) {
		var targets = document.querySelectorAll( '.mv-home section > div, .mv-footer > div' );
		var io = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'in' );
						io.unobserve( entry.target );
					}
				} );
			},
			{ rootMargin: '0px 0px -8% 0px' }
		);
		targets.forEach( function ( el ) {
			if ( el.getBoundingClientRect().top >= window.innerHeight * 0.92 ) {
				el.classList.add( 'rv' );
				io.observe( el );
			}
		} );
	}

	/* ---------- 3. Accessibility toolbar ---------- */
	var wrap = document.getElementById( 'mv-a11y' );
	if ( ! wrap ) {
		return;
	}

	var openBtn = document.getElementById( 'mv-a11y-open' );
	var closeBtn = document.getElementById( 'mv-a11y-close' );
	var panel = document.getElementById( 'mv-a11y-panel' );
	var guide = document.getElementById( 'mv-reading-guide' );
	var STORE = 'mvA11y';

	// key -> html class. Font size is a counter handled separately.
	var toggles = {
		contrast: 'a11y-contrast',
		invert: 'a11y-invert',
		grayscale: 'a11y-grayscale',
		underline: 'a11y-underline',
		readable: 'a11y-readable',
		noanim: 'a11y-noanim',
		headings: 'a11y-headings',
		links: 'a11y-links',
		guide: 'a11y-guide'
	};

	var state = { font: 0 };
	try {
		state = Object.assign( state, JSON.parse( window.localStorage.getItem( STORE ) || '{}' ) );
	} catch ( err ) {
		state = { font: 0 };
	}

	function save() {
		try {
			window.localStorage.setItem( STORE, JSON.stringify( state ) );
		} catch ( err ) { /* storage unavailable — non-fatal */ }
	}

	function applyFont() {
		doc.classList.remove( 'a11y-fs1', 'a11y-fs2', 'a11y-fs-1' );
		if ( 1 === state.font ) {
			doc.classList.add( 'a11y-fs1' );
		} else if ( 2 === state.font ) {
			doc.classList.add( 'a11y-fs2' );
		} else if ( -1 === state.font ) {
			doc.classList.add( 'a11y-fs-1' );
		}
	}

	function applyAll() {
		applyFont();
		Object.keys( toggles ).forEach( function ( key ) {
			doc.classList.toggle( toggles[ key ], !! state[ key ] );
		} );
		wrap.querySelectorAll( '.mv-a11y-act' ).forEach( function ( btn ) {
			var key = btn.getAttribute( 'data-a11y' );
			if ( 'fontplus' === key ) {
				btn.setAttribute( 'aria-pressed', state.font > 0 ? 'true' : 'false' );
			} else if ( 'fontminus' === key ) {
				btn.setAttribute( 'aria-pressed', state.font < 0 ? 'true' : 'false' );
			} else {
				btn.setAttribute( 'aria-pressed', state[ key ] ? 'true' : 'false' );
			}
		} );
	}

	function setOpen( open ) {
		panel.hidden = ! open;
		openBtn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		if ( open ) {
			panel.querySelector( 'button, a' ).focus();
		}
	}

	openBtn.addEventListener( 'click', function () {
		setOpen( panel.hidden );
	} );
	closeBtn.addEventListener( 'click', function () {
		setOpen( false );
		openBtn.focus();
	} );
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! panel.hidden ) {
			setOpen( false );
			openBtn.focus();
		}
	} );
	document.addEventListener( 'click', function ( e ) {
		if ( ! panel.hidden && ! wrap.contains( e.target ) ) {
			setOpen( false );
		}
	} );

	wrap.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '[data-a11y]' );
		if ( ! btn ) {
			return;
		}
		var key = btn.getAttribute( 'data-a11y' );

		if ( 'reset' === key ) {
			state = { font: 0 };
		} else if ( 'fontplus' === key ) {
			state.font = Math.min( 2, ( state.font || 0 ) + 1 );
		} else if ( 'fontminus' === key ) {
			state.font = Math.max( -1, ( state.font || 0 ) - 1 );
		} else {
			state[ key ] = ! state[ key ];
		}
		save();
		applyAll();
	} );

	// Reading guide follows the pointer.
	if ( guide ) {
		document.addEventListener( 'mousemove', function ( e ) {
			if ( doc.classList.contains( 'a11y-guide' ) ) {
				guide.style.top = ( e.clientY + 18 ) + 'px';
			}
		}, { passive: true } );
	}

	applyAll();
}() );
