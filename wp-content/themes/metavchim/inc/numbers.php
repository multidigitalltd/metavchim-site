<?php
/**
 * רצועת המספרים בראש עמוד הבית.
 *
 * הערכים נערכים בלוח הבקרה, והרצועה מוצגת רק כשיש בה מספרים — כדי
 * שלא יופיעו באתר נתונים שלא אושרו, ושלא תישאר רצועה ריקה.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * מספר השדות במסך העריכה.
 */
const MV_NUMBERS_MAX = 6;

/**
 * השורות ששמורות בהגדרות (רק אלה שיש בהן ערך ותווית).
 *
 * @return array<int,array{value:string,label:string}>
 */
function mv_numbers_saved() {
	$rows  = get_option( 'mv_numbers', array() );
	$clean = array();

	if ( ! is_array( $rows ) ) {
		return $clean;
	}

	foreach ( $rows as $row ) {
		$value = isset( $row['value'] ) ? trim( (string) $row['value'] ) : '';
		$label = isset( $row['label'] ) ? trim( (string) $row['label'] ) : '';
		if ( '' !== $value && '' !== $label ) {
			$clean[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
	}

	return $clean;
}

/**
 * השורות להצגה. אין מספרים — אין רצועה.
 *
 * @return array<int,array{value:string,label:string}>
 */
function mv_numbers() {
	return mv_numbers_saved();
}

/**
 * רצועת הנתונים: [mv_numbers].
 *
 * @return string
 */
function mv_numbers_shortcode() {
	$rows = mv_numbers();
	if ( ! $rows ) {
		return '';
	}

	ob_start();
	?>
	<section class="mv-stats-sec" aria-label="נתוני המערכת בקצרה">
		<div class="mv-stats" data-count="<?php echo (int) count( $rows ); ?>">
			<?php foreach ( $rows as $row ) : ?>
				<div class="mv-stat">
					<div class="mv-stat-v"><bdi><?php echo esc_html( $row['value'] ); ?></bdi></div>
					<div class="mv-stat-t"><?php echo esc_html( $row['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'mv_numbers', 'mv_numbers_shortcode' );

/* -------------------------------------------------------------------------
 * מסך הניהול
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט בלוח הבקרה.
 */
function mv_numbers_admin_menu() {
	add_submenu_page(
		MV_DASH_SLUG,
		'מספרים באתר',
		'מספרים באתר',
		'manage_options',
		'mv-numbers',
		'mv_render_numbers_page'
	);
}
add_action( 'admin_menu', 'mv_numbers_admin_menu', 12 );

/**
 * מסך המספרים.
 */
function mv_render_numbers_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$rows = mv_numbers_saved();
	$done = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.

	$examples = array(
		array( '1,200+', 'מתווכים ברשת' ),
		array( '180', 'משרדים פעילים' ),
		array( '3,400', 'שיתופי פעולה שנסגרו' ),
		array( '12,000', 'נכסים וקונים במאגר' ),
	);
	?>
	<div class="wrap">
		<h1>מספרים באתר</h1>

		<?php if ( 'yes' === $done ) : ?>
			<div class="notice notice-success is-dismissible"><p>המספרים נשמרו.</p></div>
		<?php endif; ?>

		<p>
			הרצועה מוצגת מתחת לכותרת הראשית בעמוד הבית. שורה שחסר בה מספר או תיאור לא מוצגת.
			<?php if ( ! $rows ) : ?>
				<strong>כרגע הרצועה אינה מוצגת באתר כלל</strong> — היא תופיע ברגע שתמלאו כאן לפחות שורה אחת.
			<?php endif; ?>
		</p>
		<p class="description">חשוב: המספרים מוצגים לגולשים כפי שתכתבו אותם, ולכן כדאי להזין רק נתונים אמיתיים ומעודכנים.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_save_numbers">
			<?php wp_nonce_field( 'mv_save_numbers' ); ?>

			<table class="form-table" role="presentation">
				<?php
				for ( $i = 0; $i < MV_NUMBERS_MAX; $i++ ) :
					$value   = isset( $rows[ $i ]['value'] ) ? $rows[ $i ]['value'] : '';
					$label   = isset( $rows[ $i ]['label'] ) ? $rows[ $i ]['label'] : '';
					$example = isset( $examples[ $i ] ) ? $examples[ $i ] : array( '', '' );
					?>
					<tr>
						<th scope="row">שורה <?php echo (int) ( $i + 1 ); ?></th>
						<td>
							<input type="text" name="mv_numbers[<?php echo (int) $i; ?>][value]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $example[0] ); ?>" class="regular-text" style="max-width:160px">
							<input type="text" name="mv_numbers[<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php echo esc_attr( $example[1] ); ?>" class="regular-text">
						</td>
					</tr>
				<?php endfor; ?>
			</table>

			<?php submit_button( 'שמירה' ); ?>
		</form>
	</div>
	<?php
}

/**
 * שמירת המספרים.
 */
function mv_save_numbers_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_save_numbers' );

	$rows  = isset( $_POST['mv_numbers'] ) && is_array( $_POST['mv_numbers'] ) ? wp_unslash( $_POST['mv_numbers'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- מנוקה בלולאה.
	$clean = array();

	foreach ( $rows as $row ) {
		$value = isset( $row['value'] ) ? sanitize_text_field( (string) $row['value'] ) : '';
		$label = isset( $row['label'] ) ? sanitize_text_field( (string) $row['label'] ) : '';
		if ( '' !== $value && '' !== $label ) {
			$clean[] = array(
				'value' => $value,
				'label' => $label,
			);
		}
	}

	update_option( 'mv_numbers', $clean, false );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'mv-numbers',
				'mv_saved' => 'yes',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_save_numbers', 'mv_save_numbers_settings' );
