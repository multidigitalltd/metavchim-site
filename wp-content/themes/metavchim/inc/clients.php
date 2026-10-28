<?php
/**
 * "מי כבר איתנו" — קיר הלוגואים של המשרדים והסוכנויות.
 *
 * הלוגואים נבחרים מספריית המדיה במסך ייעודי בלוח הבקרה, ולכן אפשר
 * להוסיף ולהחליף אותם בלי לגעת בקוד. אזור בלי לוגואים לא מוצג.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * הלוגואים השמורים.
 *
 * @return array<int,array{id:int,name:string,link:string}>
 */
function mv_clients() {
	$rows  = get_option( 'mv_clients', array() );
	$clean = array();

	if ( ! is_array( $rows ) ) {
		return $clean;
	}

	foreach ( $rows as $row ) {
		$id = isset( $row['id'] ) ? absint( $row['id'] ) : 0;
		if ( ! $id || ! wp_attachment_is_image( $id ) ) {
			continue;
		}
		$clean[] = array(
			'id'   => $id,
			'name' => isset( $row['name'] ) ? (string) $row['name'] : '',
			'link' => isset( $row['link'] ) ? (string) $row['link'] : '',
		);
	}

	return $clean;
}

/**
 * כותרות האזור, ניתנות לעריכה במסך הלוגואים.
 *
 * @return array{title:string,text:string}
 */
function mv_clients_copy() {
	$copy = get_option( 'mv_clients_copy', array() );

	return array(
		'title' => isset( $copy['title'] ) && '' !== $copy['title'] ? (string) $copy['title'] : 'מי כבר איתנו',
		'text'  => isset( $copy['text'] ) && '' !== $copy['text'] ? (string) $copy['text'] : 'משרדים וסוכנויות תיווך מכל הארץ כבר עובדים במערכת — ומשתפים פעולה ביניהם.',
	);
}

/**
 * קיר הלוגואים: [mv_clients].
 *
 * @return string
 */
function mv_clients_shortcode() {
	$logos = mv_clients();
	if ( ! $logos ) {
		return '';
	}

	$copy = mv_clients_copy();
	ob_start();
	?>
	<section id="clients" class="mv-sec mv-clients-sec" aria-labelledby="mv-clients-title">
		<div class="mv-wrap">
			<div class="mv-sec-head">
				<h2 class="mv-h2" id="mv-clients-title"><?php echo esc_html( $copy['title'] ); ?><span class="mv-dot" aria-hidden="true">.</span></h2>
				<p class="mv-lede"><?php echo esc_html( $copy['text'] ); ?></p>
			</div>
			<ul class="mv-clients">
				<?php
				foreach ( $logos as $logo ) :
					$image = wp_get_attachment_image(
						$logo['id'],
						'medium',
						false,
						array(
							'class'   => 'mv-client-img',
							'alt'     => $logo['name'],
							'loading' => 'lazy',
						)
					);
					if ( ! $image ) {
						continue;
					}
					?>
					<li class="mv-client">
						<?php if ( $logo['link'] ) : ?>
							<a href="<?php echo esc_url( $logo['link'] ); ?>" target="_blank" rel="noopener">
								<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- פלט מוכן של וורדפרס. ?>
							</a>
						<?php else : ?>
							<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- פלט מוכן של וורדפרס. ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'mv_clients', 'mv_clients_shortcode' );

/* -------------------------------------------------------------------------
 * מסך הניהול
 * ---------------------------------------------------------------------- */

/**
 * תת-תפריט בלוח הבקרה.
 */
function mv_clients_admin_menu() {
	add_submenu_page(
		MV_DASH_SLUG,
		'מי כבר איתנו',
		'מי כבר איתנו',
		'manage_options',
		'mv-clients',
		'mv_render_clients_page'
	);
}
add_action( 'admin_menu', 'mv_clients_admin_menu', 12 );

/**
 * טעינת בורר המדיה במסך הלוגואים בלבד.
 *
 * @param string $hook מזהה המסך.
 */
function mv_clients_admin_assets( $hook ) {
	if ( false === strpos( (string) $hook, 'mv-clients' ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'mv-admin-clients',
		MV_THEME_URI . '/assets/js/admin-clients.js',
		array(),
		MV_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'mv_clients_admin_assets' );

/**
 * מסך הלוגואים.
 */
function mv_render_clients_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$logos = mv_clients();
	$copy  = mv_clients_copy();
	$done  = isset( $_GET['mv_saved'] ) ? sanitize_key( wp_unslash( $_GET['mv_saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- הודעת סטטוס בלבד.
	?>
	<div class="wrap">
		<h1>מי כבר איתנו</h1>

		<?php if ( 'yes' === $done ) : ?>
			<div class="notice notice-success is-dismissible"><p>הלוגואים נשמרו.</p></div>
		<?php endif; ?>

		<p>
			הלוגואים מוצגים בעמוד הבית ברצועה אחת. מומלץ קובץ PNG או SVG עם רקע שקוף.
			אזור בלי לוגואים אינו מוצג באתר.
		</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="mv_save_clients">
			<?php wp_nonce_field( 'mv_save_clients' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="mv_clients_title">כותרת האזור</label></th>
					<td><input type="text" class="regular-text" id="mv_clients_title" name="mv_clients_title" value="<?php echo esc_attr( $copy['title'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="mv_clients_text">משפט הסבר</label></th>
					<td><textarea class="large-text" rows="2" id="mv_clients_text" name="mv_clients_text"><?php echo esc_textarea( $copy['text'] ); ?></textarea></td>
				</tr>
			</table>

			<h2>הלוגואים</h2>
			<table class="mv-rows" id="mv-clients-rows">
				<tbody>
					<?php foreach ( $logos as $i => $logo ) : ?>
						<tr class="mv-row">
							<td><?php echo wp_get_attachment_image( $logo['id'], 'medium', false, array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- פלט מוכן של וורדפרס. ?></td>
							<td>
								<input type="hidden" name="mv_clients[<?php echo (int) $i; ?>][id]" value="<?php echo (int) $logo['id']; ?>" class="mv-row-id">
								<input type="text" name="mv_clients[<?php echo (int) $i; ?>][name]" value="<?php echo esc_attr( $logo['name'] ); ?>" placeholder="שם המשרד" class="regular-text">
							</td>
							<td>
								<input type="url" dir="ltr" name="mv_clients[<?php echo (int) $i; ?>][link]" value="<?php echo esc_attr( $logo['link'] ); ?>" placeholder="https:// (לא חובה)" class="regular-text">
							</td>
							<td>
								<button type="button" class="button mv-row-pick">החלפת תמונה</button>
								<button type="button" class="button-link delete mv-row-del">הסרה</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p><button type="button" class="button button-secondary" id="mv-clients-add">הוספת לוגו</button></p>

			<?php submit_button( 'שמירה' ); ?>
		</form>

		<template id="mv-client-row">
			<tr class="mv-row">
				<td><img src="" alt="" class="mv-row-img" hidden></td>
				<td>
					<input type="hidden" name="mv_clients[__i__][id]" value="" class="mv-row-id">
					<input type="text" name="mv_clients[__i__][name]" value="" placeholder="שם המשרד" class="regular-text">
				</td>
				<td>
					<input type="url" dir="ltr" name="mv_clients[__i__][link]" value="" placeholder="https:// (לא חובה)" class="regular-text">
				</td>
				<td>
					<button type="button" class="button mv-row-pick">בחירת תמונה</button>
					<button type="button" class="button-link delete mv-row-del">הסרה</button>
				</td>
			</tr>
		</template>
	</div>
	<?php
}

/**
 * שמירת הלוגואים.
 */
function mv_save_clients_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לבצע פעולה זו.' );
	}
	check_admin_referer( 'mv_save_clients' );

	$rows  = isset( $_POST['mv_clients'] ) && is_array( $_POST['mv_clients'] ) ? wp_unslash( $_POST['mv_clients'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- מנוקה בלולאה.
	$clean = array();

	foreach ( $rows as $row ) {
		$id = isset( $row['id'] ) ? absint( $row['id'] ) : 0;
		if ( ! $id || ! wp_attachment_is_image( $id ) ) {
			continue;
		}
		$clean[] = array(
			'id'   => $id,
			'name' => isset( $row['name'] ) ? sanitize_text_field( (string) $row['name'] ) : '',
			'link' => isset( $row['link'] ) ? esc_url_raw( trim( (string) $row['link'] ), array( 'http', 'https' ) ) : '',
		);
	}

	update_option( 'mv_clients', $clean, false );

	update_option(
		'mv_clients_copy',
		array(
			'title' => isset( $_POST['mv_clients_title'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_clients_title'] ) ) : '',
			'text'  => isset( $_POST['mv_clients_text'] ) ? sanitize_text_field( wp_unslash( $_POST['mv_clients_text'] ) ) : '',
		),
		false
	);

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'mv-clients',
				'mv_saved' => 'yes',
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_save_clients', 'mv_save_clients_settings' );
