<?php
/**
 * דף הנחיתה לאירוע — פרטי האירוע וטופס ההרשמה.
 *
 * פרטי האירוע (תאריך, שעה, כתובת, טלפון) מוגדרים במסך אחד ומוצגים בדף
 * דרך שורטקוד, כך ששינוי במקום אחד מתעדכן בכל המופעים בדף.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * שדות האירוע וברירות המחדל שלהם, לפי קובץ העיצוב.
 *
 * @return array<string,array{label:string,default:string,help:string}>
 */
function mv_event_fields() {
	return array(
		'date'        => array(
			'label'   => 'תאריך',
			'default' => '31.08.26',
			'help'    => 'מוצג כמו שנכתב, בכיוון משמאל לימין.',
		),
		'weekday'     => array(
			'label'   => 'יום בשבוע',
			'default' => 'יום שני',
			'help'    => '',
		),
		'time'        => array(
			'label'   => 'שעה',
			'default' => '10:00',
			'help'    => 'למשל 10:00. המילה "בבוקר" מתווספת בעיצוב.',
		),
		'address'     => array(
			'label'   => 'כתובת',
			'default' => 'בית נועה, בר כוכבא 16',
			'help'    => 'השורה הראשונה בכרטיס המיקום.',
		),
		'address_sub' => array(
			'label'   => 'עיר וקומה',
			'default' => 'בני ברק · קומה 10',
			'help'    => 'השורה השנייה בכרטיס המיקום.',
		),
		'phone'       => array(
			'label'   => 'טלפון להרשמה',
			'default' => '050-414-3564',
			'help'    => 'מוצג בדף ומשמש גם לקישור הוואטסאפ.',
		),
	);
}

/**
 * ערך שדה של האירוע.
 *
 * @param string $key שם השדה.
 * @return string
 */
function mv_event( $key ) {
	$fields = mv_event_fields();
	if ( ! isset( $fields[ $key ] ) ) {
		return '';
	}
	$value = get_option( 'mv_event_' . $key, '' );
	return '' !== $value ? $value : $fields[ $key ]['default'];
}

/**
 * ניקוי חד-פעמי: אתר ששמר ערך שהיה בעבר ברירת מחדל עובר לברירת המחדל
 * העדכנית מקובץ העיצוב. ערך שהמנהל הקליד בעצמו נשאר כפי שהוא.
 */
function mv_event_migrate_address() {
	if ( '2' === get_option( 'mv_event_addr_v', '' ) ) {
		return;
	}

	$old = array( 'סוקולוב 41, בני ברק', 'בני ברק' );
	if ( in_array( get_option( 'mv_event_address', '' ), $old, true ) ) {
		delete_option( 'mv_event_address' );
	}

	update_option( 'mv_event_addr_v', '2', false );
}
add_action( 'admin_init', 'mv_event_migrate_address', 9 );

/**
 * לוגואים של שותפי האירוע, כפי שהם מופיעים בקובץ העיצוב.
 *
 * @return array<string,array{file:string,alt:string,w:int,h:int}>
 */
function mv_event_partners() {
	return array(
		'kanko'    => array(
			'file' => 'partner-kanko.png',
			'alt'  => 'Kanko',
			'w'    => 859,
			'h'    => 223,
		),
		'bahadrei' => array(
			'file' => 'partner-bahadrei.svg',
			'alt'  => 'קבוצת בחדרי',
			'w'    => 121,
			'h'    => 91,
		),
	);
}

/**
 * שורטקוד ללוגו שותף: [mv_partner name="kanko" class="…" height="38"].
 *
 * הכתובת נבנית בזמן ההצגה ולא נשמרת בתוכן העמוד, כך שהעברת האתר
 * לדומיין אחר לא שוברת את התמונות.
 *
 * @param array $atts מאפייני השורטקוד.
 * @return string
 */
function mv_partner_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'    => 'kanko',
			'class'   => '',
			'height'  => '38',
			'loading' => 'lazy',
		),
		$atts,
		'mv_partner'
	);

	$list = mv_event_partners();
	$key  = sanitize_key( $atts['name'] );
	if ( ! isset( $list[ $key ] ) ) {
		return '';
	}

	$logo   = $list[ $key ];
	$height = max( 1, (int) $atts['height'] );
	$width  = (int) round( $height * $logo['w'] / $logo['h'] );
	$lazy   = 'eager' === $atts['loading'] ? 'eager' : 'lazy';

	return sprintf(
		'<img src="%s" alt="%s" class="%s" width="%d" height="%d" loading="%s" decoding="async">',
		esc_url( MV_THEME_URI . '/assets/img/' . $logo['file'] ),
		esc_attr( $logo['alt'] ),
		esc_attr( $atts['class'] ),
		$width,
		$height,
		esc_attr( $lazy )
	);
}
add_shortcode( 'mv_partner', 'mv_partner_shortcode' );

/**
 * טופס ההרשמה בדף הנחיתה נשמר בתוכן העמוד, ולכן הסקריפט של Turnstile
 * נטען כאן ולא דרך רכיב הטופס עצמו.
 */
function mv_event_enqueue_turnstile() {
	if ( is_page( 'marathon' ) && mv_turnstile_enabled() ) {
		wp_enqueue_script( 'mv-turnstile', MV_TURNSTILE_API_URL, array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- סקריפט צד שלישי בגרסה מתגלגלת.
	}
}
add_action( 'wp_enqueue_scripts', 'mv_event_enqueue_turnstile' );

/**
 * קישור וואטסאפ לפי הטלפון שהוגדר.
 *
 * @return string
 */
function mv_event_whatsapp() {
	$digits = preg_replace( '/\D/', '', mv_event( 'phone' ) );
	if ( 0 === strpos( $digits, '0' ) ) {
		$digits = '972' . substr( $digits, 1 );
	}
	return 'https://wa.me/' . $digits;
}

/**
 * שורטקוד להצגת פרט מפרטי האירוע: [mv_event field="date"].
 *
 * @param array $atts מאפייני השורטקוד.
 * @return string
 */
function mv_event_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'field' => 'date' ), $atts, 'mv_event' );

	if ( 'whatsapp' === $atts['field'] ) {
		return esc_url( mv_event_whatsapp() );
	}

	return esc_html( mv_event( $atts['field'] ) );
}
add_shortcode( 'mv_event', 'mv_event_shortcode' );

/* -------------------------------------------------------------------------
 * מסך ההגדרות
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט תחת "פניות מהאתר".
 */
function mv_event_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=' . MV_LEAD_CPT,
		'פרטי האירוע',
		'פרטי האירוע',
		'manage_options',
		'mv-event',
		'mv_render_event_page'
	);
}
add_action( 'admin_menu', 'mv_event_admin_menu' );

/**
 * מסך עריכת פרטי האירוע.
 */
function mv_render_event_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$saved = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	$page  = get_page_by_path( 'marathon' );
	?>
	<div class="wrap">
		<h1>פרטי האירוע</h1>

		<?php if ( 'yes' === $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>הפרטים נשמרו ומעודכנים בכל מקום בדף.</p></div>
		<?php endif; ?>

		<p>
			הפרטים האלה מוצגים בדף הנחיתה של המרתון — בכותרת, בלוח הזמנים, באזור ההרשמה ובסיום.
			<?php if ( $page ) : ?>
				<a href="<?php echo esc_url( get_permalink( $page ) ); ?>" target="_blank" rel="noopener">פתיחת הדף</a>
			<?php endif; ?>
		</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_save_event">
			<?php wp_nonce_field( 'mv_save_event' ); ?>

			<table class="form-table" role="presentation">
				<?php foreach ( mv_event_fields() as $key => $field ) : ?>
					<tr>
						<th scope="row"><label for="mv_event_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
						<td>
							<input type="text" class="regular-text" id="mv_event_<?php echo esc_attr( $key ); ?>" name="mv_event_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( mv_event( $key ) ); ?>" autocomplete="off">
							<?php if ( $field['help'] ) : ?>
								<p class="description"><?php echo esc_html( $field['help'] ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>

			<?php submit_button( 'שמירה' ); ?>
		</form>

		<p>ההרשמות מהדף נכנסות ל<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . MV_LEAD_CPT ) ); ?>">פניות מהאתר</a>, מסומנות כ"מרתון השת״פים".</p>
	</div>
	<?php
}

/**
 * שמירת פרטי האירוע.
 */
function mv_save_event_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_save_event' );

	foreach ( array_keys( mv_event_fields() ) as $key ) {
		$name  = 'mv_event_' . $key;
		$value = isset( $_POST[ $name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $name ] ) ) : '';
		update_option( $name, $value, false );
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type' => MV_LEAD_CPT,
				'page'      => 'mv-event',
				'mv_saved'  => 'yes',
			),
			admin_url( 'edit.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_save_event', 'mv_save_event_settings' );

/**
 * חלון "לא מסתדר לכם התאריך" — רשימת המתנה למועד הבא.
 *
 * מוצג בדף הנחיתה בלבד, ורק אחרי שהייה קצרה או בסימן יציאה, כדי לא
 * לקפוץ למבקר בפנים ברגע הכניסה. הבחירה נשמרת בדפדפן שלו.
 */
function mv_render_waitlist_popup() {
	if ( ! is_page( 'marathon' ) ) {
		return;
	}
	?>
	<div class="mrt-pop" id="mrt-waitlist" role="dialog" aria-modal="true" aria-labelledby="mrt-pop-title" hidden>
		<div class="mrt-pop-back" data-mrt-close></div>
		<div class="mrt-pop-card" role="document">
			<button type="button" class="mrt-pop-x" data-mrt-close aria-label="סגירת החלון">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
			</button>

			<span class="mrt-pop-ico" aria-hidden="true">
				<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.4"></circle><path d="M12 7.6V12l3 1.8"></path></svg>
			</span>

			<h2 class="mrt-pop-title" id="mrt-pop-title">לא מסתדר לכם התאריך<span class="mrt-pop-dot">?</span></h2>
			<p class="mrt-pop-text">השאירו פרטים ונעדכן אתכם ברגע שנפתחת קבוצה חדשה. אפשר לציין בהערות אם נוח לכם יותר בשעות הבוקר או בשעות הערב.</p>

			<form class="mrt-pop-form" method="post" novalidate
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
				data-mv-endpoint="<?php echo esc_url( rest_url( 'metavchim/v1/lead' ) ); ?>">
				<input type="hidden" name="action" value="mv_demo_lead">
				<input type="hidden" name="mv_form" value="waitlist">
				<input type="hidden" name="mv_source" value="<?php echo esc_url( (string) get_permalink() ); ?>">

				<p class="mrt-hp" aria-hidden="true">
					<label for="mrt-pop-website">אל תמלאו שדה זה</label>
					<input type="text" id="mrt-pop-website" name="mv_website" tabindex="-1" autocomplete="off">
				</p>

				<div>
					<label class="mrt-pop-label" for="mrt-pop-name">שם מלא</label>
					<input class="mrt-pop-field" type="text" id="mrt-pop-name" name="mv_name" placeholder="השם שלכם" autocomplete="name" maxlength="120" required aria-describedby="mrt-pop-name-err">
					<span class="mrt-error" id="mrt-pop-name-err" role="alert"></span>
				</div>
				<div>
					<label class="mrt-pop-label" for="mrt-pop-phone">טלפון</label>
					<input class="mrt-pop-field" type="tel" id="mrt-pop-phone" name="mv_phone" dir="ltr" placeholder="050-0000000" autocomplete="tel" inputmode="tel" maxlength="30" required aria-describedby="mrt-pop-phone-err">
					<span class="mrt-error" id="mrt-pop-phone-err" role="alert"></span>
				</div>
				<div>
					<label class="mrt-pop-label" for="mrt-pop-note">הערות <span class="mrt-pop-opt">— בוקר או ערב?</span></label>
					<textarea class="mrt-pop-field" id="mrt-pop-note" name="mv_note" rows="2" maxlength="500" placeholder="למשל: מעדיף שעות ערב"></textarea>
				</div>

				<?php mv_turnstile_widget(); ?>

				<button class="mrt-pop-btn" type="submit">עדכנו אותי במועד הבא</button>
			</form>

			<div class="mrt-pop-done" hidden>
				<span class="mrt-pop-ico" aria-hidden="true">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17.5 19 7"></path></svg>
				</span>
				<p class="mrt-pop-title">רשמנו אתכם<span class="mrt-pop-dot">.</span></p>
				<p class="mrt-pop-text">נעדכן ברגע שייפתח מועד נוסף.</p>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'mv_render_waitlist_popup', 7 );

/**
 * דף הנחיתה עומד בפני עצמו — אין בו את חלון תיאום ההדגמה של האתר.
 *
 * @param bool $needed האם להציג.
 * @return bool
 */
function mv_event_hide_demo_modal( $needed ) {
	return is_page( 'marathon' ) ? false : $needed;
}
add_filter( 'mv_demo_form_needed', 'mv_event_hide_demo_modal' );
