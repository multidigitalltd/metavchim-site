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
		var targets = document.querySelectorAll( '.mv-reveal section > div, .mv-footer > div' );
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

/* ---------------------------------------------------------------------------
 * טופס הרשמה ותיאום הדגמה — פתיחה, מלכודת פוקוס ושליחה ללא רענון.
 * ללא JavaScript החלון עדיין נפתח דרך :target והטופס נשלח רגיל.
 * ------------------------------------------------------------------------ */
( function () {
	var modal = document.getElementById( 'demo' );
	if ( ! modal ) {
		return;
	}

	var card = modal.querySelector( '.mv-modal-card' );
	var form = modal.querySelector( '.mv-form' );
	var opener = null;
	var SELECTOR = 'a[href],button:not([disabled]),input:not([type="hidden"]),textarea,select';
	var shield = modal.querySelector( '.mv-turnstile' );
	var shieldId = null;

	// Turnstile מרונדר רק כשהחלון נפתח: ווידג'ט שנטען בתוך אלמנט מוסתר
	// מקבל רוחב אפס. הפונקציה נקראת גם מה-onload של הסקריפט.
	function mountShield() {
		if ( ! shield || null !== shieldId || ! window.turnstile || modal.hidden ) {
			return;
		}
		shieldId = window.turnstile.render( shield, {
			sitekey: shield.getAttribute( 'data-sitekey' ),
			theme: shield.getAttribute( 'data-theme' ) || 'light',
			language: 'he'
		} );
	}
	window.mvTurnstileReady = mountShield;

	function open( trigger ) {
		opener = trigger || null;
		modal.hidden = false;
		modal.classList.add( 'is-open' );
		document.documentElement.style.overflow = 'hidden';
		mountShield();
		var first = card.querySelector( '#mv-name' ) || card.querySelector( SELECTOR );
		if ( first ) {
			first.focus();
		}
	}

	function close() {
		modal.classList.remove( 'is-open' );
		modal.hidden = true;
		document.documentElement.style.overflow = '';
		if ( location.hash === '#demo' ) {
			history.replaceState( null, '', location.pathname + location.search );
		}
		if ( opener && document.contains( opener ) ) {
			opener.focus();
		}
		opener = null;
	}

	// כל קישור אל ‎#demo‎ פותח את החלון.
	document.addEventListener( 'click', function ( e ) {
		var link = e.target.closest( 'a[href$="#demo"]' );
		if ( link ) {
			e.preventDefault();
			open( link );
			return;
		}
		if ( e.target.closest( '[data-mv-close]' ) ) {
			e.preventDefault();
			close();
		}
	} );

	modal.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key ) {
			close();
			return;
		}
		if ( 'Tab' !== e.key ) {
			return;
		}
		var items = Array.prototype.filter.call(
			card.querySelectorAll( SELECTOR ),
			function ( el ) { return el.offsetParent !== null; }
		);
		if ( ! items.length ) {
			return;
		}
		var first = items[ 0 ];
		var last = items[ items.length - 1 ];
		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		}
	} );

	// פתיחה אוטומטית כשמגיעים עם ‎#demo‎ בכתובת.
	if ( location.hash === '#demo' ) {
		open( null );
	}

	// שליחה ללא רענון עמוד.
	if ( form ) {
		form.addEventListener( 'submit', function ( e ) {
			if ( ! form.reportValidity() ) {
				return;
			}
			e.preventDefault();

			var button = form.querySelector( '.mv-form-submit' );
			var data = new FormData( form );
			var endpoint = form.getAttribute( 'data-mv-endpoint' ) || form.action;

			// כתובת האתר השמורה בוורדפרס יכולה להיות עם www או ב-http בזמן
			// שהגולש נמצא בכתובת אחרת. שליחה לכתובת אחרת נחסמת כבקשה
			// חוצת-מקורות, ולכן מיישרים את הנתיב למקור הנוכחי.
			try {
				var target = new URL( endpoint, location.href );
				target.protocol = location.protocol;
				target.host = location.host;
				endpoint = target.toString();
			} catch ( err ) {
				endpoint = form.action;
			}
			data.append( 'mv_ajax', '1' );
			if ( button ) {
				button.disabled = true;
			}

			fetch( endpoint, {
				method: 'POST',
				body: data,
				credentials: 'same-origin'
			} ).then( function ( res ) {
				return res.json().catch( function () {
					// השרת החזיר משהו שאינו JSON — בדרך כלל חומת אש או שגיאת PHP.
					return {
						ok: false,
						message: 'השליחה נכשלה (שגיאה ' + res.status + '). אפשר להתקשר אלינו או לנסות שוב.'
					};
				} );
			} ).then( function ( res ) {
				note( res.ok, res.message );
				if ( res.ok ) {
					form.remove();
				} else if ( null !== shieldId && window.turnstile ) {
					// הטוקן חד-פעמי — אחרי כישלון צריך אימות חדש.
					window.turnstile.reset( shieldId );
				}
			} ).catch( function () {
				note( false, 'לא הצלחנו להתחבר לשרת. כדאי לבדוק את החיבור ולנסות שוב.' );
			} ).finally( function () {
				if ( button ) {
					button.disabled = false;
				}
			} );
		} );
	}

	function note( ok, message ) {
		var el = card.querySelector( '.mv-form-note' );
		if ( ! el ) {
			el = document.createElement( 'p' );
			card.insertBefore( el, form );
		}
		el.className = 'mv-form-note ' + ( ok ? 'is-ok' : 'is-err' );
		el.setAttribute( 'role', ok ? 'status' : 'alert' );
		el.textContent = message || ( ok ? 'קיבלנו את הפרטים. נחזור אליכם בהקדם.' : 'השליחה נכשלה.' );
	}
}() );
