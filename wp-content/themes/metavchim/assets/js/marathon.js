/**
 * דף הנחיתה של המרתון — אימות טופס ההרשמה ושליחה בלי רענון.
 *
 * ההודעות בעברית ומוצגות מתחת לשדה עצמו, ולא בבועות ברירת המחדל של
 * הדפדפן. בכישלון שליחה הטופס נשאר מלא כדי לא לאבד את מה שהוזן.
 */
( function () {
	var form = document.querySelector( '.mv-event-form' );
	if ( ! form ) {
		return;
	}

	var done = document.querySelector( '.mrt-done' );
	var button = form.querySelector( 'button[type="submit"]' );

	var RULES = [
		{ name: 'mv_name', err: 'mrt-name-err', msg: 'צריך למלא שם מלא.' },
		{ name: 'mv_phone', err: 'mrt-phone-err', msg: 'מספר טלפון נייד לא תקין. למשל 050-0000000.', test: function ( v ) {
			return /^0(5\d)[- ]?\d{3}[- ]?\d{4}$/.test( v.replace( /\s/g, '' ) );
		} },
		{ name: 'mv_office', err: 'mrt-office-err', msg: 'צריך למלא שם משרד או סוכנות.' },
		{ name: 'mv_area', err: 'mrt-area-err', msg: 'צריך למלא אזור פעילות.' },
		{ name: 'mv_member', err: 'mrt-member-err', msg: 'צריך לבחור אחת מהאפשרויות.' }
	];

	function value( name ) {
		var field = form.elements[ name ];
		if ( ! field ) {
			return '';
		}
		if ( field.length && 'radio' === field[ 0 ].type ) {
			for ( var i = 0; i < field.length; i++ ) {
				if ( field[ i ].checked ) {
					return field[ i ].value;
				}
			}
			return '';
		}
		return ( field.value || '' ).trim();
	}

	function say( id, message ) {
		var el = document.getElementById( id );
		if ( el ) {
			el.textContent = message;
		}
	}

	function validate() {
		var first = null;

		RULES.forEach( function ( rule ) {
			var v = value( rule.name );
			var ok = '' !== v && ( ! rule.test || rule.test( v ) );
			say( rule.err, ok ? '' : rule.msg );
			if ( ! ok && ! first ) {
				first = form.elements[ rule.name ];
			}
		} );

		if ( first ) {
			var target = first.length ? first[ 0 ] : first;
			target.focus();
		}

		return ! first;
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		if ( ! validate() ) {
			return;
		}

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
		if ( button ) {
			button.disabled = true;
		}

		fetch( endpoint, { method: 'POST', body: data, credentials: 'same-origin' } )
			.then( function ( res ) {
				return res.json().catch( function () {
					return { ok: false, message: 'השליחה נכשלה (שגיאה ' + res.status + '). אפשר להירשם בוואטסאפ.' };
				} );
			} )
			.then( function ( res ) {
				if ( res.ok ) {
					form.hidden = true;
					if ( done ) {
						done.hidden = false;
						done.setAttribute( 'role', 'status' );
					}
					return;
				}
				say( 'mrt-member-err', res.message || 'השליחה נכשלה. אפשר לנסות שוב.' );
				if ( window.turnstile ) {
					window.turnstile.reset();
				}
			} )
			.catch( function () {
				say( 'mrt-member-err', 'לא הצלחנו להתחבר לשרת. אפשר להירשם בוואטסאפ.' );
			} )
			.finally( function () {
				if ( button ) {
					button.disabled = false;
				}
			} );
	} );

	// ניקוי הודעת שגיאה ברגע שמתקנים את השדה.
	form.addEventListener( 'input', function ( e ) {
		var rule = RULES.filter( function ( r ) {
			return r.name === e.target.name;
		} )[ 0 ];
		if ( rule ) {
			say( rule.err, '' );
		}
	} );
	form.addEventListener( 'change', function ( e ) {
		if ( 'mv_member' === e.target.name ) {
			say( 'mrt-member-err', '' );
		}
	} );
}() );
