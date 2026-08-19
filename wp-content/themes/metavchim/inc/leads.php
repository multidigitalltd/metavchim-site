<?php
/**
 * טופס הרשמה ותיאום הדגמה (ליד) — חלון קופץ עם שם, טלפון ומייל.
 *
 * הטופס נשלח ל-admin-post.php עם nonce ובדיקות שדות מלאות, נשמר כסוג תוכן
 * פנימי ונשלח גם במייל למנהל האתר. ללא JavaScript החלון נפתח דרך ‎:target‎
 * והשליחה עובדת כטופס רגיל — התנהגות זהה, רק בלי האנימציה.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

const MV_LEAD_CPT = 'mv_lead';

/**
 * שדות הליד וההיגיינה שלהם.
 *
 * @return array<string,string>
 */
function mv_lead_fields() {
	return array(
		'_mv_lead_phone' => 'sanitize_text_field',
		'_mv_lead_email' => 'sanitize_email',
		'_mv_lead_note'  => 'sanitize_textarea_field',
		'_mv_lead_page'  => 'esc_url_raw',
	);
}

/**
 * סוג תוכן פנימי לפניות — לא מוצג באתר, רק בניהול.
 */
function mv_register_lead_cpt() {
	register_post_type(
		MV_LEAD_CPT,
		array(
			'labels'          => array(
				'name'          => 'פניות',
				'singular_name' => 'פנייה',
				'menu_name'     => 'פניות מהאתר',
				'all_items'     => 'כל הפניות',
				'search_items'  => 'חיפוש פניות',
				'not_found'     => 'אין פניות עדיין',
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-email-alt',
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'capabilities'    => array(
				'create_posts' => 'do_not_allow',
			),
			'supports'        => array( 'title' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
		)
	);
}
add_action( 'init', 'mv_register_lead_cpt' );

/**
 * הצגת פרטי הפנייה במסך העריכה.
 */
function mv_add_lead_meta_box() {
	add_meta_box(
		'mv-lead-details',
		'פרטי הפנייה',
		'mv_render_lead_meta_box',
		MV_LEAD_CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'mv_add_lead_meta_box' );

/**
 * תוכן תיבת הפרטים (קריאה בלבד — הליד מגיע מהמבקר).
 *
 * @param WP_Post $post הפנייה.
 */
function mv_render_lead_meta_box( $post ) {
	$phone = (string) get_post_meta( $post->ID, '_mv_lead_phone', true );
	$email = (string) get_post_meta( $post->ID, '_mv_lead_email', true );
	$note  = (string) get_post_meta( $post->ID, '_mv_lead_note', true );
	$page  = (string) get_post_meta( $post->ID, '_mv_lead_page', true );
	?>
	<p><strong>טלפון:</strong>
		<?php if ( $phone ) : ?>
			<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
		<?php else : ?>
			—
		<?php endif; ?>
	</p>
	<p><strong>דוא"ל:</strong>
		<?php if ( $email ) : ?>
			<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
		<?php else : ?>
			—
		<?php endif; ?>
	</p>
	<?php if ( $note ) : ?>
		<p><strong>הודעה:</strong><br><?php echo nl2br( esc_html( $note ) ); ?></p>
	<?php endif; ?>
	<?php if ( $page ) : ?>
		<p><strong>נשלח מהעמוד:</strong> <a href="<?php echo esc_url( $page ); ?>"><?php echo esc_html( $page ); ?></a></p>
	<?php endif; ?>
	<?php
}

/**
 * עמודות רשימת הפניות — טלפון ומייל בלי להיכנס לכל פנייה.
 *
 * @param array<string,string> $columns עמודות קיימות.
 * @return array<string,string>
 */
function mv_lead_columns( $columns ) {
	$date = isset( $columns['date'] ) ? $columns['date'] : '';
	unset( $columns['date'] );
	$columns['mv_phone'] = 'טלפון';
	$columns['mv_email'] = 'דוא"ל';
	if ( $date ) {
		$columns['date'] = $date;
	}
	return $columns;
}
add_filter( 'manage_' . MV_LEAD_CPT . '_posts_columns', 'mv_lead_columns' );

/**
 * תוכן העמודות המותאמות.
 *
 * @param string $column שם העמודה.
 * @param int    $post_id מזהה הפנייה.
 */
function mv_lead_column_content( $column, $post_id ) {
	if ( 'mv_phone' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_mv_lead_phone', true ) );
	} elseif ( 'mv_email' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_mv_lead_email', true ) );
	}
}
add_action( 'manage_' . MV_LEAD_CPT . '_posts_custom_column', 'mv_lead_column_content', 10, 2 );

/* -------------------------------------------------------------------------
 * הצגת הטופס
 * ---------------------------------------------------------------------- */

/**
 * סימון שיש בעמוד קישור שפותח את הטופס.
 *
 * @param bool|null $set קביעת הדגל.
 * @return bool
 */
function mv_demo_form_requested( $set = null ) {
	static $needed = false;
	if ( true === $set ) {
		$needed = true;
	}
	return $needed;
}

/**
 * החלון נטען בעמוד הבית (שם יושבים המסלולים) או בכל עמוד שהפעיל את הדגל.
 *
 * @return bool
 */
function mv_demo_form_needed() {
	return is_front_page() || mv_demo_form_requested();
}

/**
 * חלון ההרשמה. ‎id="demo"‎ כדי שכל קישור ‎href="#demo"‎ יפתח אותו,
 * גם בלי JavaScript.
 */
function mv_render_demo_form() {
	if ( ! mv_demo_form_needed() ) {
		return;
	}

	$sent = isset( $_GET['mv_demo'] ) ? sanitize_key( wp_unslash( $_GET['mv_demo'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	?>
	<div class="mv-modal" id="demo" role="dialog" aria-modal="true" aria-labelledby="mv-demo-title" hidden>
		<div class="mv-modal-backdrop" data-mv-close></div>
		<div class="mv-modal-card" role="document">
			<button type="button" class="mv-modal-x" data-mv-close aria-label="סגירת החלון">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
			</button>

			<h2 class="mv-modal-title" id="mv-demo-title">הרשמה ותיאום הדגמה — חינם</h2>
			<p class="mv-modal-sub">משאירים פרטים, ואנחנו חוזרים אליכם לתיאום הדגמה אישית של המערכת. ללא עלות וללא התחייבות.</p>

			<?php if ( 'ok' === $sent ) : ?>
				<p class="mv-form-note is-ok" role="status">קיבלנו את הפרטים. נחזור אליכם בהקדם לתיאום.</p>
			<?php elseif ( 'err' === $sent ) : ?>
				<p class="mv-form-note is-err" role="alert">חלק מהפרטים חסרים או שגויים. אפשר לנסות שוב.</p>
			<?php endif; ?>

			<form class="mv-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="mv_demo_lead">
				<input type="hidden" name="mv_source" value="<?php echo esc_url( is_singular() ? (string) get_permalink() : home_url( '/' ) ); ?>">
				<?php wp_nonce_field( 'mv_demo_lead', 'mv_demo_nonce' ); ?>

				<p class="mv-hp" aria-hidden="true">
					<label for="mv-website">אל תמלאו שדה זה</label>
					<input type="text" id="mv-website" name="mv_website" tabindex="-1" autocomplete="off">
				</p>

				<p class="mv-field">
					<label for="mv-name">שם מלא <span aria-hidden="true">*</span></label>
					<input type="text" id="mv-name" name="mv_name" required autocomplete="name" maxlength="120">
				</p>
				<p class="mv-field">
					<label for="mv-phone">טלפון <span aria-hidden="true">*</span></label>
					<input type="tel" id="mv-phone" name="mv_phone" required autocomplete="tel" inputmode="tel" maxlength="30">
				</p>
				<p class="mv-field">
					<label for="mv-email">דוא"ל <span aria-hidden="true">*</span></label>
					<input type="email" id="mv-email" name="mv_email" required autocomplete="email" maxlength="120">
				</p>

				<button type="submit" class="mv-form-submit">שליחה ותיאום הדגמה</button>
				<span class="mv-form-legal">הפרטים נשמרים לצורך יצירת קשר בלבד.</span>
			</form>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'mv_render_demo_form', 5 );

/* -------------------------------------------------------------------------
 * קליטת הטופס
 * ---------------------------------------------------------------------- */

/**
 * חזרה למבקר — JSON לשליחה מ-JavaScript, הפניה רגילה לכל השאר.
 *
 * @param bool   $ok      הצליח.
 * @param string $message הודעה למשתמש.
 */
function mv_demo_lead_respond( $ok, $message ) {
	$is_ajax = ! empty( $_POST['mv_ajax'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- ה-nonce נבדק במסלול הקורא.

	if ( $is_ajax ) {
		wp_send_json(
			array(
				'ok'      => (bool) $ok,
				'message' => $message,
			),
			$ok ? 200 : 400
		);
	}

	$back = wp_get_referer();
	if ( ! $back ) {
		$back = home_url( '/' );
	}
	wp_safe_redirect( add_query_arg( 'mv_demo', $ok ? 'ok' : 'err', $back ) . '#demo' );
	exit;
}

/**
 * שמירת הפנייה ושליחת התראה במייל.
 */
function mv_handle_demo_lead() {
	$nonce = isset( $_POST['mv_demo_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_demo_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'mv_demo_lead' ) ) {
		mv_demo_lead_respond( false, 'פג תוקף הטופס. יש לרענן את העמוד ולנסות שוב.' );
	}

	// מלכודת בוטים: שדה שאדם לא רואה ולא ממלא.
	if ( ! empty( $_POST['mv_website'] ) ) {
		mv_demo_lead_respond( true, 'קיבלנו את הפרטים.' );
	}

	// חסימת הצפה מאותו מבקר.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'mv_lead_' . md5( $ip . '|' . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) );
	if ( $ip && get_transient( $key ) ) {
		mv_demo_lead_respond( false, 'הפנייה כבר נשלחה. אם נפלה טעות, אפשר לנסות שוב בעוד רגע.' );
	}

	$name  = isset( $_POST['mv_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_name'] ) ) : '';
	$phone = isset( $_POST['mv_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_phone'] ) ) : '';
	$email = isset( $_POST['mv_email'] ) ? sanitize_email( wp_unslash( $_POST['mv_email'] ) ) : '';
	$page  = isset( $_POST['mv_source'] ) ? esc_url_raw( wp_unslash( $_POST['mv_source'] ) ) : '';

	$digits = preg_replace( '/\D/', '', $phone );

	if ( '' === $name || strlen( $digits ) < 9 || ! is_email( $email ) ) {
		mv_demo_lead_respond( false, 'חסרים פרטים: יש למלא שם, טלפון תקין וכתובת דוא"ל תקינה.' );
	}

	$lead_id = wp_insert_post(
		array(
			'post_type'   => MV_LEAD_CPT,
			'post_status' => 'publish',
			'post_title'  => $name,
		),
		true
	);

	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		mv_demo_lead_respond( false, 'שמירת הפנייה נכשלה. אפשר לנסות שוב בעוד רגע.' );
	}

	update_post_meta( $lead_id, '_mv_lead_phone', $phone );
	update_post_meta( $lead_id, '_mv_lead_email', $email );
	update_post_meta( $lead_id, '_mv_lead_page', $page );

	if ( $ip ) {
		set_transient( $key, 1, 30 );
	}

	$to      = get_option( 'admin_email' );
	$site    = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
	$body    = "פנייה חדשה לתיאום הדגמה\n\n"
		. "שם: {$name}\n"
		. "טלפון: {$phone}\n"
		. "דוא\"ל: {$email}\n"
		. ( $page ? "עמוד: {$page}\n" : '' )
		. "\nניהול הפניות: " . admin_url( 'edit.php?post_type=' . MV_LEAD_CPT );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	if ( $to ) {
		wp_mail( $to, "[{$site}] פנייה חדשה לתיאום הדגמה — {$name}", $body, $headers );
	}

	/**
	 * נקודת חיבור למערכות חיצוניות (CRM, ווטסאפ וכו').
	 *
	 * @param int   $lead_id מזהה הפנייה.
	 * @param array $data    פרטי הפנייה.
	 */
	do_action(
		'mv_demo_lead_saved',
		$lead_id,
		array(
			'name'  => $name,
			'phone' => $phone,
			'email' => $email,
			'page'  => $page,
		)
	);

	mv_demo_lead_respond( true, 'קיבלנו את הפרטים. נחזור אליכם בהקדם לתיאום.' );
}
add_action( 'admin_post_nopriv_mv_demo_lead', 'mv_handle_demo_lead' );
add_action( 'admin_post_mv_demo_lead', 'mv_handle_demo_lead' );
