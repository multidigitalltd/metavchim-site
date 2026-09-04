/**
 * Metavchim — front-end behaviour (vanilla JS, no dependencies).
 */

/**
 * רישום ווידג'טים של Turnstile. הסקריפט נטען עם onload יחיד, ולכן כל
 * חלון רושם כאן את פונקציית ההרכבה שלו במקום לדרוס את הקודמת.
 */
var mvTurnstileWaiting = [];
function mvTurnstileRegister( mount ) {
	mvTurnstileWaiting.push( mount );
	if ( window.turnstile ) {
		mount();
	}
}
window.mvTurnstileReady = function () {
	mvTurnstileWaiting.forEach( function ( mount ) {
		mount();
	} );
};
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
	mvTurnstileRegister( mountShield );

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

/* ---------------------------------------------------------------------------
 * סימון הסקשן הנוכחי בתפריט. קישורי עמודים מסומנים על ידי וורדפרס,
 * וכאן מטופלים קישורי העוגן של עמוד הבית.
 * ------------------------------------------------------------------------ */
( function () {
	var links = [];

	Array.prototype.forEach.call(
		document.querySelectorAll( '.mv-nav-list a[href*="#"]' ),
		function ( link ) {
			var hash = link.href.slice( link.href.indexOf( '#' ) );
			var section = hash.length > 1 ? document.querySelector( hash ) : null;
			if ( section ) {
				links.push( { link: link, section: section } );
			}
		}
	);

	if ( ! links.length ) {
		return;
	}

	var ticking = false;

	function mark() {
		ticking = false;
		var line = window.innerHeight * 0.28; // מעט מתחת לכותרת העליונה.
		var current = null;

		links.forEach( function ( item ) {
			var box = item.section.getBoundingClientRect();
			if ( box.top <= line && box.bottom > line ) {
				current = item.link;
			}
		} );

		links.forEach( function ( item ) {
			item.link.classList.toggle( 'is-active', item.link === current );
		} );
	}

	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			ticking = true;
			window.requestAnimationFrame( mark );
		}
	}, { passive: true } );

	mark();
}() );

/* ---------------------------------------------------------------------------
 * מסלולים: מתג חיוב חודשי/שנתי ומסילה נגללת עם חיצים.
 * ------------------------------------------------------------------------ */
( function () {
	var box = document.querySelector( '[data-mv-plans]' );
	if ( ! box ) {
		return;
	}

	var rail = box.querySelector( '.mv-plans' );
	var prev = box.querySelector( '.mv-rail-btn.is-prev' );
	var next = box.querySelector( '.mv-rail-btn.is-next' );
	var rtl = 'rtl' === getComputedStyle( rail ).direction;

	/* ----- מתג החיוב ----- */
	box.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '.mv-cycle-btn' );
		if ( ! btn ) {
			return;
		}

		var cycle = btn.getAttribute( 'data-cycle' );

		box.querySelectorAll( '.mv-cycle-btn' ).forEach( function ( item ) {
			var on = item === btn;
			item.classList.toggle( 'is-on', on );
			item.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
		} );

		box.querySelectorAll( '[data-' + cycle + ']' ).forEach( function ( item ) {
			item.textContent = item.getAttribute( 'data-' + cycle );
		} );

		box.querySelectorAll( '.mv-plan-save' ).forEach( function ( item ) {
			item.hidden = 'year' !== cycle || '' === item.textContent.trim();
		} );
	} );

	/* ----- מסילת הגלילה ----- */
	function step() {
		var card = rail.querySelector( '.mv-plan' );
		return card ? card.getBoundingClientRect().width + 18 : rail.clientWidth;
	}

	function refresh() {
		var max = rail.scrollWidth - rail.clientWidth;
		var at = Math.abs( rail.scrollLeft );
		var scrollable = max > 4;

		prev.hidden = ! scrollable || at <= 2;
		next.hidden = ! scrollable || at >= max - 2;
	}

	function scrollBy( dir ) {
		rail.scrollBy( { left: dir * step() * ( rtl ? -1 : 1 ), behavior: 'smooth' } );
	}

	prev.addEventListener( 'click', function () {
		scrollBy( -1 );
	} );
	next.addEventListener( 'click', function () {
		scrollBy( 1 );
	} );

	rail.addEventListener( 'scroll', refresh, { passive: true } );
	window.addEventListener( 'resize', refresh );
	refresh();
}() );

/* ---------------------------------------------------------------------------
 * הסכמה למדידה. Google Analytics נטען רק אחרי אישור מפורש, והבחירה
 * נשמרת בדפדפן של המבקר בלבד.
 * ------------------------------------------------------------------------ */
( function () {
	var box = document.getElementById( 'mv-consent' );
	var KEY = 'mv-consent-v1';
	var choice = null;

	try {
		choice = window.localStorage.getItem( KEY );
	} catch ( e ) {
		choice = null;
	}

	// דפדפן ששולח אות פרטיות נחשב כמסרב, בלי לשאול.
	if ( ! choice && true === navigator.globalPrivacyControl ) {
		choice = 'no';
	}

	function loadAnalytics() {
		if ( ! box || window.mvAnalyticsLoaded ) {
			return;
		}
		var id = box.getAttribute( 'data-ga' );
		if ( ! id ) {
			return;
		}
		window.mvAnalyticsLoaded = true;

		var tag = document.createElement( 'script' );
		tag.async = true;
		tag.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent( id );
		document.head.appendChild( tag );

		window.dataLayer = window.dataLayer || [];
		window.gtag = function () {
			window.dataLayer.push( arguments );
		};
		window.gtag( 'js', new Date() );
		window.gtag( 'config', id, { anonymize_ip: true } );
	}

	if ( 'yes' === choice ) {
		loadAnalytics();
	} else if ( box && 'no' !== choice ) {
		box.hidden = false;
	}

	if ( ! box ) {
		return;
	}

	box.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '[data-consent]' );
		if ( ! btn ) {
			return;
		}

		var value = btn.getAttribute( 'data-consent' );
		try {
			window.localStorage.setItem( KEY, value );
		} catch ( err ) {
			// דפדפן שחוסם אחסון — הבחירה תקפה לביקור הנוכחי בלבד.
		}

		box.hidden = true;
		if ( 'yes' === value ) {
			loadAnalytics();
		}
	} );

	// קישור בפוטר לשינוי הבחירה בכל רגע.
	document.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( '[data-mv-consent-open]' ) ) {
			e.preventDefault();
			box.hidden = false;
			var first = box.querySelector( '[data-consent]' );
			if ( first ) {
				first.focus();
			}
		}
	} );
}() );

/**
 * חלון ההרשמה לעדכונים.
 *
 * נפתח פעם אחת לדפדפן: אחרי שהייה קצרה בעמוד או כשהסמן יוצא ממנו
 * למעלה. הבחירה נשמרת אצל המבקר בלבד, ולא נשלחת לשרת.
 */
( function () {
	'use strict';

	var pop = document.getElementById( 'mv-news' );
	if ( ! pop ) {
		return;
	}

	var KEY = 'mv-news-v1';
	var card = pop.querySelector( '.mv-modal-card' );
	var form = pop.querySelector( '.mv-news-form' );
	var done = pop.querySelector( '.mv-news-done' );
	var button = form.querySelector( 'button[type="submit"]' );
	var shield = pop.querySelector( '.mv-turnstile' );
	var shieldId = null;
	var opener = null;
	var shown = false;
	var SELECTOR = 'a[href],button:not([disabled]),input:not([type="hidden"]),textarea,select';

	try {
		if ( window.localStorage.getItem( KEY ) ) {
			return;
		}
	} catch ( e ) {
		// דפדפן שחוסם אחסון — החלון יוצג פעם אחת בביקור.
	}

	function remember() {
		try {
			window.localStorage.setItem( KEY, '1' );
		} catch ( e ) {
			// אין אחסון; אין מה לזכור.
		}
	}

	function mountShield() {
		if ( ! shield || null !== shieldId || ! window.turnstile || pop.hidden ) {
			return;
		}
		shieldId = window.turnstile.render( shield, {
			sitekey: shield.getAttribute( 'data-sitekey' ),
			theme: shield.getAttribute( 'data-theme' ) || 'light',
			language: 'he'
		} );
	}
	mvTurnstileRegister( mountShield );

	function open() {
		var demo = document.getElementById( 'demo' );
		if ( shown || ( demo && ! demo.hidden ) ) {
			return; // המבקר כבר באמצע טופס אחר.
		}
		shown = true;
		opener = document.activeElement;
		pop.hidden = false;
		pop.classList.add( 'is-open' );
		document.documentElement.style.overflow = 'hidden';
		mountShield();
		var first = pop.querySelector( '#mv-news-name' );
		if ( first ) {
			first.focus();
		}
	}

	function close() {
		pop.classList.remove( 'is-open' );
		pop.hidden = true;
		document.documentElement.style.overflow = '';
		remember();
		if ( opener && document.contains( opener ) && opener.focus ) {
			opener.focus();
		}
	}

	var timer = window.setTimeout( open, 14000 );

	document.addEventListener( 'mouseout', function ( e ) {
		if ( ! e.relatedTarget && e.clientY <= 0 ) {
			window.clearTimeout( timer );
			open();
		}
	} );

	pop.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( '[data-mv-news-close]' ) ) {
			close();
		}
	} );

	pop.addEventListener( 'keydown', function ( e ) {
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

	function say( message ) {
		var box = document.getElementById( 'mv-news-err' );
		if ( box ) {
			box.textContent = message;
		}
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		var name = form.elements.mv_name.value.trim();
		var email = form.elements.mv_email.value.trim();
		var okMail = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test( email );

		if ( ! name ) {
			say( 'צריך למלא שם.' );
			form.elements.mv_name.focus();
			return;
		}
		if ( ! okMail ) {
			say( 'כתובת הדוא"ל אינה תקינה.' );
			form.elements.mv_email.focus();
			return;
		}
		if ( ! form.elements.mv_consent.checked ) {
			say( 'צריך לאשר את מדיניות הפרטיות ותנאי השימוש.' );
			form.elements.mv_consent.focus();
			return;
		}
		say( '' );

		form.elements.mv_source.value = location.href;

		var endpoint = form.getAttribute( 'data-mv-endpoint' ) || form.action;
		try {
			var url = new URL( endpoint, location.href );
			url.protocol = location.protocol;
			url.host = location.host;
			endpoint = url.toString();
		} catch ( err ) {
			endpoint = form.action;
		}

		var data = new FormData( form );
		data.append( 'mv_ajax', '1' );
		button.disabled = true;

		fetch( endpoint, { method: 'POST', body: data, credentials: 'same-origin' } )
			.then( function ( res ) {
				return res.json().catch( function () {
					return { ok: false, message: 'השליחה נכשלה (שגיאה ' + res.status + ').' };
				} );
			} )
			.then( function ( res ) {
				if ( res.ok ) {
					form.hidden = true;
					done.hidden = false;
					done.setAttribute( 'role', 'status' );
					remember();
					window.setTimeout( close, 3200 );
					return;
				}
				say( res.message || 'השליחה נכשלה. אפשר לנסות שוב.' );
				if ( window.turnstile && null !== shieldId ) {
					window.turnstile.reset( shieldId );
				}
			} )
			.catch( function () {
				say( 'לא הצלחנו להתחבר לשרת. אפשר לנסות שוב.' );
			} )
			.finally( function () {
				button.disabled = false;
			} );
	} );

	form.addEventListener( 'input', function () {
		say( '' );
	} );
}() );

/**
 * התפריט הנפתח "פיצ'רים במערכת".
 *
 * נפתח בלחיצה (ולא במעבר עכבר בלבד), נסגר ב-Escape, בלחיצה מחוץ לו
 * וכשהפוקוס יוצא ממנו — כך שהוא עובד גם במקלדת וגם במגע.
 */
( function () {
	'use strict';

	var wrap = document.querySelector( '.mv-mega-wrap' );
	if ( ! wrap ) {
		return;
	}

	var button = wrap.querySelector( '.mv-mega-btn' );
	var panel = wrap.querySelector( '.mv-mega' );
	if ( ! button || ! panel ) {
		return;
	}

	function setOpen( open ) {
		panel.hidden = ! open;
		button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	button.addEventListener( 'click', function () {
		setOpen( panel.hidden );
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( ! wrap.contains( e.target ) ) {
			setOpen( false );
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! panel.hidden ) {
			setOpen( false );
			button.focus();
		}
	} );

	wrap.addEventListener( 'focusout', function ( e ) {
		if ( ! wrap.contains( e.relatedTarget ) ) {
			setOpen( false );
		}
	} );

	// בחירת יכולת סוגרת את הפאנל ומשאירה את הגלילה לעוגן.
	panel.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( 'a' ) ) {
			setOpen( false );
		}
	} );
}() );
