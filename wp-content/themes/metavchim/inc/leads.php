<?php
/**
 * טופס הרשמה ותיאום הדגמה (ליד) — חלון קופץ עם שם, טלפון ומייל.
 *
 * הטופס נשלח ל-admin-post.php עם בדיקות שדות מלאות, נשמר כסוג תוכן פנימי
 * ונשלח גם במייל למנהל האתר. ההגנה מפני שליחות אוטומטיות היא Cloudflare
 * Turnstile (ראו inc/turnstile.php) יחד עם מלכודת בוטים וחסימת הצפה —
 * ולא nonce, שנשבר מאחורי מטמון עמודים.
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
		'_mv_lead_when'  => 'sanitize_text_field',
		'_mv_lead_office' => 'sanitize_text_field',
		'_mv_lead_area'  => 'sanitize_text_field',
		'_mv_lead_member' => 'sanitize_text_field',
		'_mv_lead_group' => 'sanitize_text_field',
		'_mv_lead_consent' => 'sanitize_text_field',
		'_mv_lead_form'  => 'sanitize_key',
		'_mv_lead_note'  => 'sanitize_textarea_field',
		'_mv_lead_page'  => 'esc_url_raw',
	);
}

/**
 * שם הטופס שממנו הגיעה הפנייה, לתצוגה בניהול ובמייל.
 *
 * @return array<string,string>
 */
function mv_lead_form_labels() {
	return array(
		'demo'     => 'תיאום הדגמה',
		'marathon' => 'הרשמה למרתון השת״פים',
		'waitlist' => 'רשימת המתנה למועד הבא',
		'news'     => 'הרשמה לעדכונים',
	);
}

/**
 * תווית הטופס.
 *
 * @param string $form מזהה הטופס.
 * @return string
 */
function mv_lead_form_label( $form ) {
	$labels = mv_lead_form_labels();
	return isset( $labels[ $form ] ) ? $labels[ $form ] : $labels['demo'];
}

/**
 * ההודעה שמוצגת למבקר אחרי שליחה מוצלחת.
 *
 * @param string $form מזהה הטופס.
 * @return string
 */
function mv_lead_success_message( $form ) {
	$messages = array(
		'marathon' => 'נשמר לכם מקום. נחזור אליכם עם אישור והכתובת המדויקת.',
		'waitlist' => 'רשמנו אתכם. נעדכן ברגע שייפתח מועד נוסף.',
		'news'     => 'נרשמתם. כל יכולת חדשה תגיע אליכם למייל.',
	);
	return isset( $messages[ $form ] ) ? $messages[ $form ] : 'קיבלנו את הפרטים. נחזור אליכם בהקדם לתיאום.';
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
	$when  = (string) get_post_meta( $post->ID, '_mv_lead_when', true );
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
	<?php $form = (string) get_post_meta( $post->ID, '_mv_lead_form', true ); ?>
	<p><strong>מקור:</strong> <?php echo esc_html( mv_lead_form_label( $form ) ); ?></p>
	<?php $consent = (string) get_post_meta( $post->ID, '_mv_lead_consent', true ); ?>
	<?php if ( $consent ) : ?>
		<p><strong>אישור מדיניות פרטיות ותנאי שימוש:</strong> <?php echo esc_html( $consent ); ?></p>
	<?php endif; ?>
	<?php if ( 'marathon' === $form ) : ?>
		<?php $group = (string) get_post_meta( $post->ID, '_mv_lead_group', true ); ?>
		<?php if ( $group ) : // נשמר בפניות מהתקופה שבה היו שני מפגשים. ?>
			<p><strong>מפגש:</strong> <?php echo esc_html( $group ); ?></p>
		<?php endif; ?>
		<p><strong>משרד / סוכנות:</strong> <?php echo esc_html( (string) get_post_meta( $post->ID, '_mv_lead_office', true ) ); ?></p>
		<p><strong>אזור פעילות:</strong> <?php echo esc_html( (string) get_post_meta( $post->ID, '_mv_lead_area', true ) ); ?></p>
		<p><strong>כבר במערכת:</strong> <?php echo esc_html( (string) get_post_meta( $post->ID, '_mv_lead_member', true ) ); ?></p>
	<?php endif; ?>
	<p><strong>מועד מועדף:</strong> <?php echo $when ? esc_html( mv_format_lead_when( $when ) ) : 'לא צוין'; ?></p>
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
	$columns['mv_form']  = 'מקור';
	$columns['mv_phone'] = 'טלפון';
	$columns['mv_email'] = 'דוא"ל';
	$columns['mv_when']  = 'מועד מועדף';
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
	if ( 'mv_form' === $column ) {
		echo esc_html( mv_lead_form_label( (string) get_post_meta( $post_id, '_mv_lead_form', true ) ) );
	} elseif ( 'mv_phone' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_mv_lead_phone', true ) );
	} elseif ( 'mv_email' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_mv_lead_email', true ) );
	} elseif ( 'mv_when' === $column ) {
		$when = (string) get_post_meta( $post_id, '_mv_lead_when', true );
		echo esc_html( $when ? mv_format_lead_when( $when ) : '—' );
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
	// הכפתור בכותרת העליונה פותח את הטופס בכל עמוד, ולכן החלון נטען תמיד.
	// עדיין אפשר לכבות אותו למקרי קצה דרך המסנן.
	return (bool) apply_filters( 'mv_demo_form_needed', true );
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

			<h2 class="mv-modal-title" id="mv-demo-title">תיאום הדגמה</h2>
			<p class="mv-modal-sub">משאירים פרטים ומועד שנוח לכם, ואנחנו חוזרים אליכם לתיאום הדגמה אישית של המערכת. ללא עלות וללא התחייבות.</p>

			<?php if ( 'ok' === $sent ) : ?>
				<p class="mv-form-note is-ok" role="status">קיבלנו את הפרטים. נחזור אליכם בהקדם לתיאום.</p>
			<?php elseif ( 'err' === $sent ) : ?>
				<p class="mv-form-note is-err" role="alert">חלק מהפרטים חסרים או שגויים. אפשר לנסות שוב.</p>
			<?php endif; ?>

			<form class="mv-form" method="post"
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
				data-mv-endpoint="<?php echo esc_url( rest_url( 'metavchim/v1/lead' ) ); ?>">
				<input type="hidden" name="action" value="mv_demo_lead">
				<input type="hidden" name="mv_source" value="<?php echo esc_url( is_singular() ? (string) get_permalink() : home_url( '/' ) ); ?>">

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

				<fieldset class="mv-field-pair">
					<legend>מועד מועדף להדגמה <span class="mv-field-opt">(לא חובה)</span></legend>
					<div class="mv-field-row">
						<p class="mv-field">
							<label for="mv-date">תאריך</label>
							<input type="date" id="mv-date" name="mv_date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>">
						</p>
						<p class="mv-field">
							<label for="mv-time">שעה</label>
							<input type="time" id="mv-time" name="mv_time" step="900">
						</p>
					</div>
				</fieldset>

				<?php mv_turnstile_widget(); ?>

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
 * הצגת המועד המועדף כפי שהמבקר הזין אותו.
 *
 * @param string $when ערך שמור בפורמט Y-m-d[ H:i].
 * @return string
 */
function mv_format_lead_when( $when ) {
	$parts = explode( ' ', trim( (string) $when ) );
	$date  = isset( $parts[0] ) ? $parts[0] : '';
	$time  = isset( $parts[1] ) ? $parts[1] : '';

	$object = DateTime::createFromFormat( 'Y-m-d', $date );
	$out    = $object ? $object->format( 'd.m.Y' ) : $date;

	return $time ? $out . ' · ' . $time : $out;
}

/**
 * עיבוד פנייה שהגיעה מהטופס. משמש גם את נתיב ה-REST וגם את admin-post,
 * ולכן מחזיר תוצאה במקום להדפיס אותה.
 *
 * @return array{ok:bool,message:string}
 */
function mv_process_demo_lead() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- טופס ציבורי; ההגנה היא Turnstile, מלכודת בוטים וחסימת הצפה.

	// אימות אנושי של Cloudflare Turnstile, כשמוגדרים מפתחות.
	$human = mv_turnstile_verify();
	if ( ! $human['ok'] ) {
		return array(
			'ok'      => false,
			'message' => $human['message'],
		);
	}

	// מלכודת בוטים: שדה שאדם לא רואה ולא ממלא.
	if ( ! empty( $_POST['mv_website'] ) ) {
		return array(
			'ok'      => true,
			'message' => 'קיבלנו את הפרטים.',
		);
	}

	// חסימת הצפה מאותו מבקר.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'mv_lead_' . md5( $ip . '|' . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) );
	if ( $ip && get_transient( $key ) ) {
		return array(
			'ok'      => false,
			'message' => 'הפנייה כבר נשלחה. אם נפלה טעות, אפשר לנסות שוב בעוד רגע.',
		);
	}

	$name  = isset( $_POST['mv_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_name'] ) ) : '';
	$phone = isset( $_POST['mv_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_phone'] ) ) : '';
	$email = isset( $_POST['mv_email'] ) ? sanitize_email( wp_unslash( $_POST['mv_email'] ) ) : '';
	$page  = isset( $_POST['mv_source'] ) ? esc_url_raw( wp_unslash( $_POST['mv_source'] ) ) : '';
	$form   = isset( $_POST['mv_form'] ) ? sanitize_key( wp_unslash( $_POST['mv_form'] ) ) : 'demo';
	$office = isset( $_POST['mv_office'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_office'] ) ) : '';
	$area   = isset( $_POST['mv_area'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_area'] ) ) : '';
	$member = isset( $_POST['mv_member'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_member'] ) ) : '';
	$note   = isset( $_POST['mv_note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mv_note'] ) ) : '';
	$group  = isset( $_POST['mv_group'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_group'] ) ) : '';
	$date  = isset( $_POST['mv_date'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_date'] ) ) : '';
	$time  = isset( $_POST['mv_time'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_time'] ) ) : '';
	$agreed = ! empty( $_POST['mv_consent'] );

	$digits = preg_replace( '/\D/', '', $phone );

	// טפסי האירוע אינם מבקשים דוא"ל, ולכן הוא נדרש רק בטופס שכולל אותו.
	$needs_email = ! in_array( $form, array( 'marathon', 'waitlist' ), true );
	// ברשימת התפוצה הדוא"ל הוא העיקר, והטלפון אינו חובה.
	$needs_phone = 'news' !== $form;

	if ( '' === $name || ( $needs_phone && strlen( $digits ) < 9 ) || ( $needs_email && ! is_email( $email ) ) ) {
		if ( ! $needs_phone ) {
			$missing = 'חסרים פרטים: יש למלא שם וכתובת דוא"ל תקינה.';
		} elseif ( $needs_email ) {
			$missing = 'חסרים פרטים: יש למלא שם, טלפון תקין וכתובת דוא"ל תקינה.';
		} else {
			$missing = 'חסרים פרטים: יש למלא שם וטלפון תקין.';
		}

		return array(
			'ok'      => false,
			'message' => $missing,
		);
	}

	// הרשמה לדיוור מחייבת אישור מפורש, והוא נשמר עם חותמת זמן.
	if ( 'news' === $form && ! $agreed ) {
		return array(
			'ok'      => false,
			'message' => 'צריך לאשר את מדיניות הפרטיות ותנאי השימוש לפני השליחה.',
		);
	}

	if ( 'marathon' === $form && ( '' === $office || '' === $area || '' === $member ) ) {
		return array(
			'ok'      => false,
			'message' => 'חסרים פרטים: יש למלא את כל השדות בטופס.',
		);
	}

	// המועד אינו חובה, אבל אם הוזן — הוא חייב להיות תקין ולא בעבר.
	$when = '';
	if ( '' !== $date ) {
		$object = DateTime::createFromFormat( 'Y-m-d', $date );
		if ( ! $object || $object->format( 'Y-m-d' ) !== $date ) {
			return array(
				'ok'      => false,
				'message' => 'התאריך שהוזן אינו תקין.',
			);
		}
		if ( $date < wp_date( 'Y-m-d' ) ) {
			return array(
				'ok'      => false,
				'message' => 'אפשר לבחור רק מועד עתידי.',
			);
		}
		$when = $date;

		if ( '' !== $time && preg_match( '/^([01]\d|2[0-3]):[0-5]\d$/', $time ) ) {
			$when .= ' ' . $time;
		}
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
		return array(
			'ok'      => false,
			'message' => 'שמירת הפנייה נכשלה. אפשר לנסות שוב בעוד רגע.',
		);
	}

	update_post_meta( $lead_id, '_mv_lead_phone', $phone );
	update_post_meta( $lead_id, '_mv_lead_email', $email );
	update_post_meta( $lead_id, '_mv_lead_when', $when );
	update_post_meta( $lead_id, '_mv_lead_page', $page );
	update_post_meta( $lead_id, '_mv_lead_form', $form );
	update_post_meta( $lead_id, '_mv_lead_office', $office );
	update_post_meta( $lead_id, '_mv_lead_area', $area );
	update_post_meta( $lead_id, '_mv_lead_member', $member );
	update_post_meta( $lead_id, '_mv_lead_note', $note );
	update_post_meta( $lead_id, '_mv_lead_group', $group );
	update_post_meta( $lead_id, '_mv_lead_consent', $agreed ? wp_date( 'd.m.Y H:i' ) : '' );

	if ( $ip ) {
		set_transient( $key, 1, 30 );
	}

	mv_notify_new_lead( $lead_id, $name, $phone, $email, $when, $page, $form, $office, $area, $member, $note, $group );

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
			'when'   => $when,
			'page'   => $page,
			'form'   => $form,
			'office' => $office,
			'area'   => $area,
			'member' => $member,
			'note'   => $note,
			'group'  => $group,
		)
	);

	return array(
		'ok'      => true,
		'message' => mv_lead_success_message( $form ),
	);
	// phpcs:enable WordPress.Security.NonceVerification.Missing
}

/**
 * התראה במייל למנהל האתר. כישלון שליחה לא מפיל את הפנייה — היא כבר שמורה.
 *
 * @param int    $lead_id מזהה הפנייה.
 * @param string $name    שם.
 * @param string $phone   טלפון.
 * @param string $email   דוא"ל.
 * @param string $when    מועד מועדף.
 * @param string $page    העמוד שממנו נשלח.
 */
function mv_notify_new_lead( $lead_id, $name, $phone, $email, $when, $page, $form = 'demo', $office = '', $area = '', $member = '', $note = '', $group = '' ) {
	$to = get_option( 'admin_email' );
	if ( ! $to ) {
		return;
	}

	$site  = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
	$title = 'פנייה חדשה · ' . mv_lead_form_label( $form );
	$body = $title . "\n\n"
		. "שם: {$name}\n"
		. "טלפון: {$phone}\n"
		. "דוא\"ל: {$email}\n"
		. ( $group ? "מפגש: {$group}\n" : '' )
		. ( $office ? "משרד: {$office}\n" : '' )
		. ( $area ? "אזור פעילות: {$area}\n" : '' )
		. ( $member ? "כבר במערכת: {$member}\n" : '' )
		. ( $note ? "הערות: {$note}\n" : '' )
		. ( $when ? 'מועד מועדף: ' . mv_format_lead_when( $when ) . "\n" : '' )
		. ( $page ? "עמוד: {$page}\n" : '' )
		. "\nניהול הפניות: " . admin_url( 'post.php?action=edit&post=' . (int) $lead_id );

	try {
		wp_mail(
			$to,
			"[{$site}] {$title} — {$name}",
			$body,
			array( 'Content-Type: text/plain; charset=UTF-8' )
		);
	} catch ( Exception $e ) {
		// שליחת המייל נכשלה; הפנייה שמורה ומוצגת בלוח הבקרה.
		return;
	}
}

/**
 * נתיב REST לשליחת הטופס. זהו המסלול שבו משתמש הדפדפן, כי הוא מחזיר
 * תמיד JSON — להבדיל מ-admin-post.php, שחומות אש של אחסון נוטות לחסום
 * לגולשים לא מחוברים.
 */
function mv_register_lead_route() {
	register_rest_route(
		'metavchim/v1',
		'/lead',
		array(
			'methods'             => 'POST',
			'callback'            => 'mv_rest_demo_lead',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'mv_register_lead_route' );

/**
 * תשובת ה-REST. תמיד 200 עם דגל הצלחה, כדי ששכבות ביניים לא יחליפו
 * גוף תשובה של שגיאה בדף HTML.
 *
 * @return WP_REST_Response
 */
function mv_rest_demo_lead() {
	// אזהרת PHP או פלט אחר שנדפס תוך כדי העיבוד היה הופך את התשובה
	// למשהו שאינו JSON. הבידוד מבטיח שהדפדפן תמיד יקבל תשובה תקינה.
	ob_start();
	$result = mv_process_demo_lead();
	ob_end_clean();

	return new WP_REST_Response(
		array(
			'ok'      => (bool) $result['ok'],
			'message' => (string) $result['message'],
		),
		200
	);
}

/**
 * המסלול ללא JavaScript: שליחה רגילה של הטופס וחזרה לעמוד עם הודעה.
 */
function mv_handle_demo_lead() {
	ob_start();
	$result = mv_process_demo_lead();
	ob_end_clean();

	$back = wp_get_referer();
	if ( ! $back ) {
		$back = home_url( '/' );
	}

	wp_safe_redirect( add_query_arg( 'mv_demo', $result['ok'] ? 'ok' : 'err', $back ) . '#demo' );
	exit;
}
add_action( 'admin_post_nopriv_mv_demo_lead', 'mv_handle_demo_lead' );
add_action( 'admin_post_mv_demo_lead', 'mv_handle_demo_lead' );
