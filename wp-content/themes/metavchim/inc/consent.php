<?php
/**
 * מדידה והסכמה — Google Analytics מאחורי באנר הסכמה.
 *
 * הסקריפט של גוגל אינו נטען כלל עד שהמבקר מאשר. דחייה נשמרת ומכובדת,
 * ואפשר לשנות את הבחירה בכל רגע דרך קישור בפוטר. כך המדידה עומדת
 * בדרישת ההסכמה מדעת ולא רק מציגה הודעה.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * מזהה המדידה של גוגל. קבוע גובר על ההגדרה במסד.
 *
 * @return string
 */
function mv_ga_id() {
	$id = defined( 'MV_GA_ID' ) && MV_GA_ID ? (string) MV_GA_ID : (string) get_option( 'mv_ga_id', '' );
	return mv_is_ga_id( $id ) ? $id : '';
}

/**
 * בדיקת תקינות של מזהה מדידה.
 *
 * @param string $id המזהה.
 * @return bool
 */
function mv_is_ga_id( $id ) {
	return (bool) preg_match( '/^(G|GT|AW|UA)-[A-Z0-9\-]{4,}$/i', (string) $id );
}

/**
 * כתובת עמוד הפרטיות.
 *
 * @return string
 */
function mv_privacy_url() {
	$page = get_page_by_path( 'privacy' );
	return $page ? get_permalink( $page ) : home_url( '/privacy/' );
}

/**
 * באנר ההסכמה. מוצג רק כשיש מזהה מדידה מוגדר — אתר בלי מדידה לא
 * מציק למבקרים בבקשה מיותרת.
 */
function mv_render_consent_banner() {
	$ga = mv_ga_id();
	if ( '' === $ga ) {
		return;
	}
	?>
	<div class="mv-consent" id="mv-consent" role="dialog" aria-label="הגדרות פרטיות ומדידה" data-ga="<?php echo esc_attr( $ga ); ?>" hidden>
		<div class="mv-consent-in">
			<p class="mv-consent-text">
				<strong>רגע לפני שממשיכים<span class="mv-dot" aria-hidden="true">.</span></strong>
				אנחנו רוצים למדוד איך משתמשים באתר (Google Analytics) כדי לשפר אותו. המדידה תתחיל רק אם תאשרו — האתר עובד במלואו גם בלי.
				<a href="<?php echo esc_url( mv_privacy_url() ); ?>">מדיניות הפרטיות</a>
			</p>
			<div class="mv-consent-actions">
				<button type="button" class="mv-consent-btn" data-consent="no">דחייה</button>
				<button type="button" class="mv-consent-btn is-yes" data-consent="yes">אישור מדידה</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'mv_render_consent_banner', 6 );

/* -------------------------------------------------------------------------
 * מסך ההגדרות
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט תחת "הגדרות".
 */
function mv_consent_admin_menu() {
	add_options_page(
		'מדידה ופרטיות',
		'מדידה ופרטיות',
		'manage_options',
		'mv-analytics',
		'mv_render_consent_page'
	);
}
add_action( 'admin_menu', 'mv_consent_admin_menu' );

/**
 * מסך ההגדרות.
 */
function mv_render_consent_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$by_const = defined( 'MV_GA_ID' ) && MV_GA_ID;
	$saved    = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	$current  = mv_ga_id();
	?>
	<div class="wrap">
		<h1>מדידה ופרטיות</h1>

		<?php if ( 'yes' === $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>ההגדרות נשמרו.</p></div>
		<?php elseif ( 'bad' === $saved ) : ?>
			<div class="notice notice-error is-dismissible"><p>מזהה המדידה אינו תקין. הוא אמור להיראות כמו <code>G-XXXXXXXXXX</code>.</p></div>
		<?php endif; ?>

		<p>
			<?php if ( $current ) : ?>
				<strong style="color:#116329">המדידה מוגדרת.</strong> באנר ההסכמה מוצג למבקרים, והסקריפט של גוגל נטען רק אחרי אישור.
			<?php else : ?>
				<strong style="color:#8a6d00">אין מדידה.</strong> לא נטען שום כלי מעקב ולא מוצג באנר הסכמה.
			<?php endif; ?>
		</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_save_analytics">
			<?php wp_nonce_field( 'mv_save_analytics' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="mv_ga_id">מזהה Google Analytics</label></th>
					<td>
						<?php if ( $by_const ) : ?>
							<code>מוגדר בקבוע MV_GA_ID</code>
						<?php else : ?>
							<input type="text" class="regular-text" id="mv_ga_id" name="mv_ga_id" value="<?php echo esc_attr( get_option( 'mv_ga_id', '' ) ); ?>" placeholder="G-XXXXXXXXXX" autocomplete="off" dir="ltr">
							<p class="description">להשארה ריקה כדי לכבות את המדידה ואת באנר ההסכמה.</p>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<?php submit_button( 'שמירה' ); ?>
		</form>

		<h2>איך זה עובד</h2>
		<ul style="list-style:disc;margin-inline-start:20px">
			<li>עד לאישור המבקר לא נטען דבר מגוגל — לא סקריפט ולא עוגייה.</li>
			<li>דחייה נשמרת בדפדפן ומכובדת; לא נשאל שוב באותו דפדפן.</li>
			<li>דפדפן ששולח אות פרטיות (Global Privacy Control) נחשב כמסרב אוטומטית.</li>
			<li>קישור "הגדרות פרטיות" בפוטר מאפשר למבקר לשנות את הבחירה בכל רגע.</li>
			<li>המדידה נטענת עם <code>anonymize_ip</code>.</li>
		</ul>

		<p><strong>חשוב:</strong> עמוד <a href="<?php echo esc_url( mv_privacy_url() ); ?>">מדיניות הפרטיות</a> כולל את סעיפי המדידה והדיוור. יש להשלים בו את פרטי החברה ולאשר את הנוסח מול הייעוץ המשפטי.</p>
	</div>
	<?php
}

/**
 * שמירת ההגדרות.
 */
function mv_save_analytics_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_save_analytics' );

	$status = 'yes';

	if ( ! defined( 'MV_GA_ID' ) || ! MV_GA_ID ) {
		$id = isset( $_POST['mv_ga_id'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_ga_id'] ) ) : '';

		if ( '' === $id ) {
			delete_option( 'mv_ga_id' );
		} elseif ( mv_is_ga_id( $id ) ) {
			update_option( 'mv_ga_id', $id, false );
		} else {
			$status = 'bad';
		}
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'mv-analytics',
				'mv_saved' => $status,
			),
			admin_url( 'options-general.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_save_analytics', 'mv_save_analytics_settings' );
