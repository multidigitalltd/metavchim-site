<?php
/**
 * רשתות חברתיות וקהילה.
 *
 * הקישורים נשמרים בהגדרות ומוצגים בשני מקומות: אזור ההצטרפות בעמוד
 * הבית ואייקונים בפוטר. אזור שאין בו אף קישור פשוט לא מוצג, כדי שלא
 * יופיעו באתר כפתורים ריקים.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * הרשתות הנתמכות: תווית, אייקון וטקסט הכפתור באזור ההצטרפות.
 *
 * @return array<string,array{label:string,icon:string,cta:string,help:string}>
 */
function mv_social_networks() {
	return array(
		'whatsapp'  => array(
			'label' => 'קהילת הווטסאפ',
			'icon'  => 'whatsapp',
			'cta'   => 'הצטרפות לקהילה',
			'help'  => 'קישור הזמנה לקבוצה או לקהילה (chat.whatsapp.com/…).',
		),
		'facebook'  => array(
			'label' => 'עמוד הפייסבוק',
			'icon'  => 'facebook',
			'cta'   => 'לעמוד בפייסבוק',
			'help'  => 'הכתובת המלאה של העמוד.',
		),
		'instagram' => array(
			'label' => 'אינסטגרם',
			'icon'  => 'instagram',
			'cta'   => 'לאינסטגרם',
			'help'  => '',
		),
		'youtube'   => array(
			'label' => 'יוטיוב',
			'icon'  => 'youtube',
			'cta'   => 'לערוץ',
			'help'  => '',
		),
		'linkedin'  => array(
			'label' => 'לינקדאין',
			'icon'  => 'linkedin',
			'cta'   => 'ללינקדאין',
			'help'  => '',
		),
		'tiktok'    => array(
			'label' => 'טיקטוק',
			'icon'  => 'tiktok',
			'cta'   => 'לטיקטוק',
			'help'  => '',
		),
	);
}

/**
 * כל הקישורים שהוגדרו, לפי סדר הרשתות.
 *
 * @return array<string,string>
 */
function mv_social_links() {
	$saved = get_option( 'mv_social', array() );
	$out   = array();

	foreach ( mv_social_networks() as $key => $network ) {
		$url = isset( $saved[ $key ] ) ? (string) $saved[ $key ] : '';
		if ( '' !== $url ) {
			$out[ $key ] = $url;
		}
	}

	return $out;
}

/**
 * קישור יחיד.
 *
 * @param string $key מזהה הרשת.
 * @return string
 */
function mv_social_link( $key ) {
	$links = mv_social_links();
	return isset( $links[ $key ] ) ? $links[ $key ] : '';
}

/**
 * אייקוני הרשתות בפוטר. מודפס ישירות, ולכן תמיד מציג את המצב העדכני.
 */
function mv_social_icons() {
	$links = mv_social_links();
	if ( ! $links ) {
		return;
	}
	$networks = mv_social_networks();
	?>
	<ul class="mv-social" aria-label="הרשתות החברתיות שלנו">
		<?php foreach ( $links as $key => $url ) : ?>
			<li>
				<a class="mv-social-a" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
					<?php mv_icon( $networks[ $key ]['icon'], 18 ); ?>
					<span class="screen-reader-text"><?php echo esc_html( $networks[ $key ]['label'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * אזור ההצטרפות לעדכונים: [mv_community].
 *
 * @return string
 */
function mv_community_shortcode() {
	$links = mv_social_links();
	if ( ! $links ) {
		return '';
	}

	$networks = mv_social_networks();
	ob_start();
	?>
	<section id="join" class="mv-sec mv-join-sec" aria-labelledby="mv-join-title">
		<div class="mv-join">
			<div class="mv-join-copy">
				<p class="mv-pill-tint"><?php mv_icon( 'bell', 15 ); ?>נשארים מעודכנים</p>
				<h2 class="mv-h2" id="mv-join-title">כל יכולת חדשה — קודם כל אצלנו<span class="mv-dot" aria-hidden="true">.</span></h2>
				<p class="mv-lede">בקהילה ובעמוד אנחנו מעלים כל עדכון במערכת, טיפים מהשטח ומועדים של מפגשי שיתופי פעולה. בלי ספאם, רק מה שרלוונטי למתווכים.</p>
			</div>
			<div class="mv-join-btns">
				<?php foreach ( $links as $key => $url ) : ?>
					<a class="mv-join-btn is-<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
						<span class="mv-join-ico"><?php mv_icon( $networks[ $key ]['icon'], 20 ); ?></span>
						<span class="mv-join-body">
							<span class="mv-join-name"><?php echo esc_html( $networks[ $key ]['label'] ); ?></span>
							<span class="mv-join-cta"><?php echo esc_html( $networks[ $key ]['cta'] ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'mv_community', 'mv_community_shortcode' );

/* -------------------------------------------------------------------------
 * מסך ההגדרות
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט בלוח הבקרה.
 */
function mv_social_admin_menu() {
	add_submenu_page(
		MV_DASH_SLUG,
		'רשתות חברתיות',
		'רשתות חברתיות',
		'manage_options',
		'mv-social',
		'mv_render_social_page'
	);
}
add_action( 'admin_menu', 'mv_social_admin_menu', 12 );

/**
 * מסך הקישורים.
 */
function mv_render_social_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$saved = get_option( 'mv_social', array() );
	$done  = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	?>
	<div class="wrap">
		<h1>רשתות חברתיות</h1>

		<?php if ( 'yes' === $done ) : ?>
			<div class="notice notice-success is-dismissible"><p>הקישורים נשמרו.</p></div>
		<?php endif; ?>

		<p>
			הקישורים מופיעים בשני מקומות: אזור "נשארים מעודכנים" בעמוד הבית ואייקונים בפוטר.
			שדה שנשאר ריק פשוט לא מוצג. אם כל השדות ריקים — האזור לא מופיע כלל.
		</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_save_social">
			<?php wp_nonce_field( 'mv_save_social' ); ?>

			<table class="form-table" role="presentation">
				<?php foreach ( mv_social_networks() as $key => $network ) : ?>
					<tr>
						<th scope="row"><label for="mv_social_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $network['label'] ); ?></label></th>
						<td>
							<input type="url" class="regular-text ltr" dir="ltr" id="mv_social_<?php echo esc_attr( $key ); ?>"
								name="mv_social[<?php echo esc_attr( $key ); ?>]"
								value="<?php echo esc_attr( isset( $saved[ $key ] ) ? $saved[ $key ] : '' ); ?>"
								placeholder="https://" autocomplete="off">
							<?php if ( $network['help'] ) : ?>
								<p class="description"><?php echo esc_html( $network['help'] ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>

			<?php submit_button( 'שמירה' ); ?>
		</form>
	</div>
	<?php
}

/**
 * שמירת הקישורים.
 */
function mv_save_social_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_save_social' );

	$input = isset( $_POST['mv_social'] ) && is_array( $_POST['mv_social'] ) ? wp_unslash( $_POST['mv_social'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- מנוקה בלולאה.
	$clean = array();

	foreach ( array_keys( mv_social_networks() ) as $key ) {
		$url = isset( $input[ $key ] ) ? esc_url_raw( trim( (string) $input[ $key ] ), array( 'http', 'https' ) ) : '';
		if ( '' !== $url ) {
			$clean[ $key ] = $url;
		}
	}

	update_option( 'mv_social', $clean, false );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'mv-social',
				'mv_saved' => 'yes',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_save_social', 'mv_save_social_settings' );
