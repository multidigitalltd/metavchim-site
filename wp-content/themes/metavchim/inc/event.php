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
		'date'    => array(
			'label'   => 'תאריך',
			'default' => '31.08.26',
			'help'    => 'מוצג כמו שנכתב, בכיוון משמאל לימין.',
		),
		'weekday' => array(
			'label'   => 'יום בשבוע',
			'default' => 'יום שני',
			'help'    => '',
		),
		'time'    => array(
			'label'   => 'שעה',
			'default' => '10:00',
			'help'    => 'למשל 10:00. המילה "בבוקר" מתווספת בעיצוב.',
		),
		'address' => array(
			'label'   => 'כתובת',
			'default' => 'סוקולוב 41, בני ברק',
			'help'    => 'הכתובת המדויקת של האירוע.',
		),
		'phone'   => array(
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
 * דף הנחיתה עומד בפני עצמו — אין בו את חלון תיאום ההדגמה של האתר.
 *
 * @param bool $needed האם להציג.
 * @return bool
 */
function mv_event_hide_demo_modal( $needed ) {
	return is_page( 'marathon' ) ? false : $needed;
}
add_filter( 'mv_demo_form_needed', 'mv_event_hide_demo_modal' );
