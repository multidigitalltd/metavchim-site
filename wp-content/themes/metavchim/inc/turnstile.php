<?php
/**
 * Cloudflare Turnstile — אימות אנושי לטפסים באתר.
 *
 * מחליף את בדיקת ה-nonce בטופס הציבורי: nonce נשבר מאחורי מטמון עמודים
 * (המבקר מקבל HTML שמור עם טוקן שפג) ולא מוסיף הגנה אמיתית מול בוטים.
 * Turnstile מאמת מול שרתי Cloudflare בכל שליחה.
 *
 * המפתחות נקראים קודם מקבועים (wp-config.php / משתני סביבה) ורק אחר כך
 * מהמסד — כך אפשר להחזיק את הסוד מחוץ למסד הנתונים.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

const MV_TURNSTILE_VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
const MV_TURNSTILE_API_URL    = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=mvTurnstileReady';

/**
 * מפתח האתר (ציבורי, מוטמע ב-HTML).
 *
 * @return string
 */
function mv_turnstile_site_key() {
	if ( defined( 'MV_TURNSTILE_SITE_KEY' ) && MV_TURNSTILE_SITE_KEY ) {
		return (string) MV_TURNSTILE_SITE_KEY;
	}
	return (string) get_option( 'mv_turnstile_site_key', '' );
}

/**
 * המפתח הסודי (שרת בלבד, לעולם לא נשלח לדפדפן).
 *
 * @return string
 */
function mv_turnstile_secret_key() {
	if ( defined( 'MV_TURNSTILE_SECRET_KEY' ) && MV_TURNSTILE_SECRET_KEY ) {
		return (string) MV_TURNSTILE_SECRET_KEY;
	}
	return (string) get_option( 'mv_turnstile_secret_key', '' );
}

/**
 * האם ההגנה פעילה — כלומר שני המפתחות קיימים.
 *
 * @return bool
 */
function mv_turnstile_enabled() {
	return '' !== mv_turnstile_site_key() && '' !== mv_turnstile_secret_key();
}

/**
 * הטמעת הווידג'ט בתוך טופס. כשאין מפתחות — לא מודפס כלום,
 * והטופס ממשיך לעבוד עם מלכודת הבוטים וחסימת ההצפה בלבד.
 *
 * הרינדור מפורש (render=explicit) ומתבצע ברגע פתיחת החלון, כי ווידג'ט
 * שנטען בתוך אלמנט מוסתר מקבל רוחב אפס.
 */
function mv_turnstile_widget() {
	if ( ! mv_turnstile_enabled() ) {
		return;
	}

	wp_enqueue_script( 'mv-turnstile', MV_TURNSTILE_API_URL, array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- סקריפט צד שלישי בגרסה מתגלגלת.

	$theme = get_option( 'mv_turnstile_theme', 'light' );
	$theme = in_array( $theme, array( 'light', 'dark', 'auto' ), true ) ? $theme : 'light';
	?>
	<div class="mv-turnstile"
		data-sitekey="<?php echo esc_attr( mv_turnstile_site_key() ); ?>"
		data-theme="<?php echo esc_attr( $theme ); ?>"></div>
	<?php
}

/**
 * טעינת הסקריפט של Cloudflare בסוף העמוד, בלי לחסום רינדור.
 *
 * @param string $tag    תגית הסקריפט.
 * @param string $handle מזהה הסקריפט.
 * @return string
 */
function mv_turnstile_script_tag( $tag, $handle ) {
	if ( 'mv-turnstile' !== $handle ) {
		return $tag;
	}
	return str_replace( ' src=', ' async defer src=', $tag );
}
add_filter( 'script_loader_tag', 'mv_turnstile_script_tag', 10, 2 );

/**
 * אימות הטוקן מול Cloudflare.
 *
 * כשאין מפתחות מוגדרים — מחזיר הצלחה, כדי שהטופס יעבוד גם לפני
 * שהוגדרה ההגנה.
 *
 * @return array{ok:bool,message:string}
 */
function mv_turnstile_verify() {
	if ( ! mv_turnstile_enabled() ) {
		return array(
			'ok'      => true,
			'message' => '',
		);
	}

	$token = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- טופס ציבורי המוגן ב-Turnstile.

	if ( '' === $token ) {
		return array(
			'ok'      => false,
			'message' => 'יש להשלים את אימות האבטחה לפני השליחה.',
		);
	}

	$body = array(
		'secret'   => mv_turnstile_secret_key(),
		'response' => $token,
	);

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( $ip && rest_is_ip_address( $ip ) ) {
		$body['remoteip'] = $ip;
	}

	$response = wp_remote_post(
		MV_TURNSTILE_VERIFY_URL,
		array(
			'timeout' => 10,
			'body'    => $body,
		)
	);

	if ( is_wp_error( $response ) ) {
		return array(
			'ok'      => false,
			'message' => 'אימות האבטחה לא זמין כרגע. אפשר לנסות שוב בעוד רגע.',
		);
	}

	$data = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) || empty( $data['success'] ) ) {
		return array(
			'ok'      => false,
			'message' => 'אימות האבטחה נכשל. יש לסמן שוב את תיבת האימות ולשלוח.',
		);
	}

	return array(
		'ok'      => true,
		'message' => '',
	);
}

/* -------------------------------------------------------------------------
 * מסך ההגדרות
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט תחת "פניות מהאתר".
 */
function mv_turnstile_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=' . MV_LEAD_CPT,
		'הגנת טפסים — Cloudflare Turnstile',
		'הגנת טפסים',
		'manage_options',
		'mv-turnstile',
		'mv_render_turnstile_page'
	);
}
add_action( 'admin_menu', 'mv_turnstile_admin_menu' );

/**
 * מסך ההגדרות. טופס הניהול מוגן ב-nonce ובבדיקת הרשאה — זו פעולה
 * משנת-נתונים בתוך לוח הבקרה, להבדיל מהטופס הציבורי באתר.
 */
function mv_render_turnstile_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$site_const   = defined( 'MV_TURNSTILE_SITE_KEY' ) && MV_TURNSTILE_SITE_KEY;
	$secret_const = defined( 'MV_TURNSTILE_SECRET_KEY' ) && MV_TURNSTILE_SECRET_KEY;
	$saved        = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	$theme        = (string) get_option( 'mv_turnstile_theme', 'light' );
	?>
	<div class="wrap">
		<h1>הגנת טפסים — Cloudflare Turnstile</h1>

		<?php if ( 'yes' === $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p>ההגדרות נשמרו.</p></div>
		<?php endif; ?>

		<p>
			<?php if ( mv_turnstile_enabled() ) : ?>
				<strong style="color:#116329">ההגנה פעילה.</strong> תיבת האימות מוצגת בטופס תיאום ההדגמה, וכל שליחה מאומתת מול Cloudflare.
			<?php else : ?>
				<strong style="color:#8a6d00">ההגנה כבויה.</strong> עד שיוגדרו שני המפתחות, הטופס מוגן במלכודת בוטים ובחסימת שליחות חוזרות בלבד.
			<?php endif; ?>
		</p>

		<p>את המפתחות יוצרים בחינם בלוח הבקרה של Cloudflare תחת <em>Turnstile</em> → <em>Add site</em>, ומזינים שם את הדומיין של האתר.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_turnstile_settings">
			<?php wp_nonce_field( 'mv_turnstile_settings' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="mv_turnstile_site_key">Site Key</label></th>
					<td>
						<?php if ( $site_const ) : ?>
							<code>מוגדר בקבוע MV_TURNSTILE_SITE_KEY</code>
						<?php else : ?>
							<input type="text" class="regular-text" id="mv_turnstile_site_key" name="mv_turnstile_site_key" value="<?php echo esc_attr( get_option( 'mv_turnstile_site_key', '' ) ); ?>" autocomplete="off">
							<p class="description">מפתח ציבורי — מוטמע בעמוד.</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="mv_turnstile_secret_key">Secret Key</label></th>
					<td>
						<?php if ( $secret_const ) : ?>
							<code>מוגדר בקבוע MV_TURNSTILE_SECRET_KEY</code>
						<?php else : ?>
							<input type="password" class="regular-text" id="mv_turnstile_secret_key" name="mv_turnstile_secret_key" value="" autocomplete="new-password" placeholder="<?php echo esc_attr( get_option( 'mv_turnstile_secret_key' ) ? '••••••••  (שמור — אפשר להשאיר ריק)' : '' ); ?>">
							<p class="description">מפתח סודי — נשלח רק משרת האתר ל-Cloudflare. השארה ריקה תשמור על המפתח הקיים.</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="mv_turnstile_theme">מראה התיבה</label></th>
					<td>
						<select id="mv_turnstile_theme" name="mv_turnstile_theme">
							<option value="light" <?php selected( 'light', $theme ); ?>>בהיר</option>
							<option value="dark" <?php selected( 'dark', $theme ); ?>>כהה</option>
							<option value="auto" <?php selected( 'auto', $theme ); ?>>לפי העדפת המשתמש</option>
						</select>
					</td>
				</tr>
			</table>

			<?php submit_button( 'שמירת ההגדרות' ); ?>
		</form>

		<h2>העדפה: החזקת המפתחות מחוץ למסד</h2>
		<p>אפשר להגדיר את המפתחות בקובץ <code>wp-config.php</code> במקום כאן. במצב כזה השדות למעלה ננעלים:</p>
		<pre style="background:#f6f7f7;padding:12px;border:1px solid #dcdcde;max-width:640px;overflow:auto"><code>define( 'MV_TURNSTILE_SITE_KEY', getenv( 'TURNSTILE_SITE_KEY' ) );
define( 'MV_TURNSTILE_SECRET_KEY', getenv( 'TURNSTILE_SECRET_KEY' ) );</code></pre>
	</div>
	<?php
}

/**
 * שמירת ההגדרות.
 */
function mv_save_turnstile_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_turnstile_settings' );

	update_option(
		'mv_turnstile_site_key',
		isset( $_POST['mv_turnstile_site_key'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_turnstile_site_key'] ) ) : ''
	);

	// שדה ריק = לא לגעת במפתח השמור.
	$secret = isset( $_POST['mv_turnstile_secret_key'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_turnstile_secret_key'] ) ) : '';
	if ( '' !== $secret ) {
		update_option( 'mv_turnstile_secret_key', $secret );
	}

	$theme = isset( $_POST['mv_turnstile_theme'] ) ? sanitize_key( wp_unslash( $_POST['mv_turnstile_theme'] ) ) : 'light';
	update_option( 'mv_turnstile_theme', in_array( $theme, array( 'light', 'dark', 'auto' ), true ) ? $theme : 'light' );

	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type' => MV_LEAD_CPT,
				'page'      => 'mv-turnstile',
				'mv_saved'  => 'yes',
			),
			admin_url( 'edit.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_turnstile_settings', 'mv_save_turnstile_settings' );
