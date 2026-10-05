/**
 * מסך "מי כבר איתנו" — בחירת לוגואים מספריית המדיה.
 *
 * שורה חדשה נוצרת מתבנית ב-HTML, כך שהמסך עובד גם כשמוסיפים כמה
 * לוגואים ברצף בלי לרענן.
 */
( function () {
	'use strict';

	var table = document.getElementById( 'mv-clients-rows' );
	var add = document.getElementById( 'mv-clients-add' );
	var tpl = document.getElementById( 'mv-client-row' );

	if ( ! table || ! add || ! tpl ) {
		return;
	}

	var body = table.querySelector( 'tbody' );
	var next = body.querySelectorAll( 'tr' ).length;

	function pick( row ) {
		var frame = window.wp.media( {
			title: 'בחירת לוגו',
			library: { type: 'image' },
			button: { text: 'שימוש בתמונה' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var image = frame.state().get( 'selection' ).first().toJSON();
			var thumb = image.sizes && image.sizes.medium ? image.sizes.medium.url : image.url;

			row.querySelector( '.mv-row-id' ).value = image.id;

			var img = row.querySelector( 'img' );
			if ( img ) {
				img.src = thumb;
				img.hidden = false;
			}
		} );

		frame.open();
	}

	add.addEventListener( 'click', function () {
		var html = tpl.innerHTML.replace( /__i__/g, String( next ) );
		var holder = document.createElement( 'tbody' );
		holder.innerHTML = html;
		var row = holder.querySelector( 'tr' );
		body.appendChild( row );
		next += 1;
		pick( row );
	} );

	table.addEventListener( 'click', function ( e ) {
		var row = e.target.closest( '.mv-row' );
		if ( ! row ) {
			return;
		}
		if ( e.target.closest( '.mv-row-pick' ) ) {
			pick( row );
		}
		if ( e.target.closest( '.mv-row-del' ) ) {
			row.remove();
		}
	} );
}() );
