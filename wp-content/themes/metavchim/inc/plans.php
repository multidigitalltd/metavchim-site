<?php
/**
 * Plans ("מסלולים") — editable in WordPress, syncable from the app API.
 *
 * The marketing page renders the plan cards from this post type through the
 * [mv_plans] shortcode, so the section always reflects the packages that
 * actually exist. An optional sync pulls them from app.metavchim.co.il.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

const MV_PLAN_CPT       = 'mv_plan';
const MV_PLAN_SYNC_HOOK = 'mv_plans_sync_event';

/**
 * Meta keys handled by the editor screen and the sync, with their sanitizer.
 *
 * @return array<string,string>
 */
function mv_plan_fields() {
	return array(
		'_mv_plan_sub'       => 'sanitize_textarea_field',
		'_mv_plan_price'     => 'sanitize_text_field',
		'_mv_plan_features'  => 'sanitize_textarea_field',
		'_mv_plan_badge'     => 'sanitize_text_field',
		'_mv_plan_cta_label' => 'sanitize_text_field',
		'_mv_plan_cta_url'   => 'esc_url_raw',
		'_mv_plan_dark'      => 'absint',
		'_mv_plan_ext_id'    => 'sanitize_text_field',
	);
}

/**
 * Register the plans post type.
 */
function mv_register_plan_cpt() {
	register_post_type(
		MV_PLAN_CPT,
		array(
			'labels'          => array(
				'name'          => 'מסלולים',
				'singular_name' => 'מסלול',
				'add_new_item'  => 'הוספת מסלול',
				'edit_item'     => 'עריכת מסלול',
				'all_items'     => 'כל המסלולים',
				'menu_name'     => 'מסלולים',
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-tag',
			'menu_position'   => 25,
			'supports'        => array( 'title', 'page-attributes' ),
			'capability_type' => 'page',
			'map_meta_cap'    => true,
		)
	);

	foreach ( mv_plan_fields() as $key => $sanitize ) {
		register_post_meta(
			MV_PLAN_CPT,
			$key,
			array(
				'type'              => '_mv_plan_dark' === $key ? 'integer' : 'string',
				'single'            => true,
				'sanitize_callback' => $sanitize,
				'show_in_rest'      => false,
				'auth_callback'     => static function () {
					return current_user_can( 'edit_pages' );
				},
			)
		);
	}
}
add_action( 'init', 'mv_register_plan_cpt' );

/**
 * Plan editor fields.
 */
function mv_plan_meta_box() {
	add_meta_box(
		'mv-plan-details',
		'פרטי המסלול',
		'mv_render_plan_meta_box',
		MV_PLAN_CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'mv_plan_meta_box' );

/**
 * Render the plan editor fields.
 *
 * @param WP_Post $post Current plan.
 */
function mv_render_plan_meta_box( $post ) {
	wp_nonce_field( 'mv_save_plan', 'mv_plan_nonce' );

	$get = static function ( $key ) use ( $post ) {
		return get_post_meta( $post->ID, $key, true );
	};
	?>
	<style>.mv-pf{margin:0 0 16px}.mv-pf label{display:block;font-weight:600;margin-bottom:4px}.mv-pf input[type=text],.mv-pf input[type=url],.mv-pf textarea{width:100%}.mv-pf .description{margin-top:4px}</style>
	<p class="mv-pf">
		<label for="mv_plan_sub">תיאור קצר</label>
		<textarea id="mv_plan_sub" name="_mv_plan_sub" rows="2"><?php echo esc_textarea( $get( '_mv_plan_sub' ) ); ?></textarea>
	</p>
	<p class="mv-pf">
		<label for="mv_plan_price">מחיר (אופציונלי)</label>
		<input type="text" id="mv_plan_price" name="_mv_plan_price" value="<?php echo esc_attr( $get( '_mv_plan_price' ) ); ?>">
		<span class="description">אם משאירים ריק — לא מוצג מחיר בכרטיס.</span>
	</p>
	<p class="mv-pf">
		<label for="mv_plan_features">מה כלול — שורה לכל פריט</label>
		<textarea id="mv_plan_features" name="_mv_plan_features" rows="6"><?php echo esc_textarea( $get( '_mv_plan_features' ) ); ?></textarea>
	</p>
	<p class="mv-pf">
		<label for="mv_plan_badge">תווית הדגשה (למשל: הכי נבחר)</label>
		<input type="text" id="mv_plan_badge" name="_mv_plan_badge" value="<?php echo esc_attr( $get( '_mv_plan_badge' ) ); ?>">
	</p>
	<p class="mv-pf">
		<label for="mv_plan_cta_label">טקסט הכפתור</label>
		<input type="text" id="mv_plan_cta_label" name="_mv_plan_cta_label" value="<?php echo esc_attr( $get( '_mv_plan_cta_label' ) ); ?>">
	</p>
	<p class="mv-pf">
		<label for="mv_plan_cta_url">קישור הכפתור</label>
		<input type="text" id="mv_plan_cta_url" name="_mv_plan_cta_url" value="<?php echo esc_attr( $get( '_mv_plan_cta_url' ) ); ?>">
		<span class="description">כתובת מלאה, או עוגן: <code>#cta</code> לאזור ההרשמה בעמוד, <code>#demo</code> לפתיחת טופס תיאום הדגמה. ריק = <code>#cta</code>.</span>
	</p>
	<p class="mv-pf">
		<label><input type="checkbox" name="_mv_plan_dark" value="1" <?php checked( '1', (string) $get( '_mv_plan_dark' ) ); ?>> כרטיס בעיצוב כהה (מודגש)</label>
	</p>
	<?php if ( $get( '_mv_plan_ext_id' ) ) : ?>
		<p class="mv-pf"><em>מסונכרן מהמערכת · מזהה: <?php echo esc_html( $get( '_mv_plan_ext_id' ) ); ?></em></p>
	<?php endif; ?>
	<p class="description">הסדר בעמוד נקבע לפי שדה "סדר" בתיבת מאפייני העמוד.</p>
	<?php
}

/**
 * Persist the plan fields.
 *
 * @param int $post_id Plan ID.
 */
function mv_save_plan_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! isset( $_POST['mv_plan_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mv_plan_nonce'] ) ), 'mv_save_plan' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( mv_plan_fields() as $key => $sanitize ) {
		if ( '_mv_plan_dark' === $key ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? 1 : 0 );
			continue;
		}
		if ( '_mv_plan_ext_id' === $key ) {
			continue; // Owned by the sync.
		}
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		update_post_meta( $post_id, $key, call_user_func( $sanitize, $raw ) );
	}
}
add_action( 'save_post_' . MV_PLAN_CPT, 'mv_save_plan_meta' );

/**
 * Published plans, in menu order.
 *
 * @return WP_Post[]
 */
function mv_get_plans() {
	return get_posts(
		array(
			'post_type'              => MV_PLAN_CPT,
			'post_status'            => 'publish',
			'numberposts'            => 12,
			'orderby'                => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);
}

/**
 * Render the plan cards. Used by the [mv_plans] shortcode in the page content.
 *
 * @return string
 */
function mv_render_plans() {
	$plans = mv_get_plans();
	if ( ! $plans ) {
		return '';
	}

	ob_start();
	echo '<div class="mv-plans">';

	foreach ( $plans as $plan ) {
		$meta      = get_post_meta( $plan->ID );
		$value     = static function ( $key ) use ( $meta ) {
			return isset( $meta[ $key ][0] ) ? $meta[ $key ][0] : '';
		};
		$dark      = '1' === $value( '_mv_plan_dark' );
		$badge     = $value( '_mv_plan_badge' );
		$price     = $value( '_mv_plan_price' );
		$cta_label = $value( '_mv_plan_cta_label' );
		$cta_url   = $value( '_mv_plan_cta_url' );
		$features  = array_filter( array_map( 'trim', explode( "\n", (string) $value( '_mv_plan_features' ) ) ) );

		// כפתור שמצביע ל-#demo פותח את טופס ההרשמה — נדאג שהחלון ייטען בעמוד.
		if ( '#demo' === $cta_url && function_exists( 'mv_demo_form_requested' ) ) {
			mv_demo_form_requested( true );
		}
		?>
		<div class="mv-plan<?php echo $dark ? ' is-dark' : ''; ?>">
			<div class="mv-plan-head">
				<h3 class="mv-plan-title"><?php echo esc_html( get_the_title( $plan ) ); ?></h3>
				<?php if ( $badge ) : ?>
					<span class="mv-plan-badge"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $price ) : ?>
				<div class="mv-plan-price"><?php echo esc_html( $price ); ?></div>
			<?php endif; ?>
			<?php if ( $value( '_mv_plan_sub' ) ) : ?>
				<p class="mv-plan-sub"><?php echo esc_html( $value( '_mv_plan_sub' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $features ) : ?>
				<ul class="mv-plan-list">
					<?php foreach ( $features as $feature ) : ?>
						<li><?php echo esc_html( $feature ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<a class="mv-plan-cta<?php echo $dark ? ' is-green' : ''; ?>" href="<?php echo esc_url( $cta_url ? $cta_url : '#cta' ); ?>">
				<?php echo esc_html( $cta_label ? $cta_label : 'קביעת הדגמה' ); ?>
			</a>
		</div>
		<?php
	}

	echo '</div>';
	return (string) ob_get_clean();
}
add_shortcode( 'mv_plans', 'mv_render_plans' );

/* -------------------------------------------------------------------------
 * Sync from the app API
 * ---------------------------------------------------------------------- */

/**
 * Configured API endpoint. A constant wins over the stored option so the
 * URL can live outside the database.
 *
 * @return string
 */
function mv_plans_api_url() {
	if ( defined( 'MV_PLANS_API_URL' ) && MV_PLANS_API_URL ) {
		return (string) MV_PLANS_API_URL;
	}
	return (string) get_option( 'mv_plans_api_url', '' );
}

/**
 * API key, if the endpoint needs one. Prefer the constant (wp-config.php or
 * an environment variable) over storing it in the database.
 *
 * @return string
 */
function mv_plans_api_key() {
	if ( defined( 'MV_PLANS_API_KEY' ) && MV_PLANS_API_KEY ) {
		return (string) MV_PLANS_API_KEY;
	}
	return (string) get_option( 'mv_plans_api_key', '' );
}

/**
 * Pull the packages from the app and mirror them into the post type.
 *
 * Accepts a bare array, or an object wrapping the list in `data`, `plans`
 * or `items`. Per item it reads the first key it recognises, so the app can
 * name things its own way.
 *
 * @return array{ok:bool,message:string,count:int}
 */
function mv_sync_plans_from_api() {
	$url = mv_plans_api_url();
	if ( ! $url ) {
		return array(
			'ok'      => false,
			'message' => 'לא הוגדרה כתובת API.',
			'count'   => 0,
		);
	}

	$args = array(
		'timeout' => 10,
		'headers' => array( 'Accept' => 'application/json' ),
	);
	$key  = mv_plans_api_key();
	if ( $key ) {
		$args['headers']['Authorization'] = 'Bearer ' . $key;
	}

	$response = wp_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		return array(
			'ok'      => false,
			'message' => 'הבקשה נכשלה: ' . $response->get_error_message(),
			'count'   => 0,
		);
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( 200 !== $code ) {
		return array(
			'ok'      => false,
			'message' => 'המערכת החזירה קוד ' . $code . '.',
			'count'   => 0,
		);
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $data ) ) {
		return array(
			'ok'      => false,
			'message' => 'התשובה אינה JSON תקין.',
			'count'   => 0,
		);
	}

	foreach ( array( 'data', 'plans', 'items', 'packages' ) as $wrapper ) {
		if ( isset( $data[ $wrapper ] ) && is_array( $data[ $wrapper ] ) ) {
			$data = $data[ $wrapper ];
			break;
		}
	}
	if ( ! $data || ! is_array( reset( $data ) ) ) {
		return array(
			'ok'      => false,
			'message' => 'לא נמצאה רשימת חבילות בתשובה.',
			'count'   => 0,
		);
	}

	$seen  = array();
	$order = 0;

	foreach ( $data as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$ext_id = (string) mv_pick( $item, array( 'id', 'code', 'slug', 'key' ) );
		$title  = (string) mv_pick( $item, array( 'name', 'title', 'label' ) );
		if ( '' === $title ) {
			continue;
		}
		if ( '' === $ext_id ) {
			$ext_id = sanitize_title( $title );
		}

		$features = mv_pick( $item, array( 'features', 'includes', 'benefits' ) );
		if ( is_array( $features ) ) {
			$features = implode(
				"\n",
				array_map(
					static function ( $feature ) {
						return is_array( $feature ) ? (string) mv_pick( $feature, array( 'name', 'title', 'label', 'text' ) ) : (string) $feature;
					},
					$features
				)
			);
		}

		$existing = get_posts(
			array(
				'post_type'              => MV_PLAN_CPT,
				'post_status'            => array( 'publish', 'draft' ),
				'numberposts'            => 1,
				'meta_key'               => '_mv_plan_ext_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'             => $ext_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			)
		);

		$postarr = array(
			'post_type'   => MV_PLAN_CPT,
			'post_status' => 'publish',
			'post_title'  => sanitize_text_field( $title ),
			'menu_order'  => ++$order,
		);
		if ( $existing ) {
			$postarr['ID'] = $existing[0]->ID;
			$plan_id       = wp_update_post( $postarr );
		} else {
			$plan_id = wp_insert_post( $postarr );
		}

		if ( is_wp_error( $plan_id ) || ! $plan_id ) {
			continue;
		}

		$popular = mv_pick( $item, array( 'popular', 'is_popular', 'featured', 'recommended' ) );

		update_post_meta( $plan_id, '_mv_plan_ext_id', sanitize_text_field( $ext_id ) );
		update_post_meta( $plan_id, '_mv_plan_sub', sanitize_textarea_field( (string) mv_pick( $item, array( 'description', 'subtitle', 'tagline', 'summary' ) ) ) );
		update_post_meta( $plan_id, '_mv_plan_price', sanitize_text_field( (string) mv_pick( $item, array( 'price_label', 'price', 'amount' ) ) ) );
		update_post_meta( $plan_id, '_mv_plan_features', sanitize_textarea_field( (string) $features ) );
		update_post_meta( $plan_id, '_mv_plan_badge', $popular ? 'הכי נבחר' : '' );
		update_post_meta( $plan_id, '_mv_plan_dark', $popular ? 1 : 0 );
		update_post_meta( $plan_id, '_mv_plan_cta_url', esc_url_raw( (string) mv_pick( $item, array( 'cta_url', 'url', 'signup_url', 'link' ) ) ) );

		$seen[] = (int) $plan_id;
	}

	if ( ! $seen ) {
		return array(
			'ok'      => false,
			'message' => 'לא נמצאו חבילות תקינות בתשובה.',
			'count'   => 0,
		);
	}

	// Packages removed from the system go to draft rather than being deleted,
	// so manual edits are never lost silently.
	foreach ( mv_get_plans() as $plan ) {
		if ( get_post_meta( $plan->ID, '_mv_plan_ext_id', true ) && ! in_array( (int) $plan->ID, $seen, true ) ) {
			wp_update_post(
				array(
					'ID'          => $plan->ID,
					'post_status' => 'draft',
				)
			);
		}
	}

	update_option( 'mv_plans_synced_at', time(), false );

	return array(
		'ok'      => true,
		'message' => 'סונכרנו ' . count( $seen ) . ' חבילות.',
		'count'   => count( $seen ),
	);
}

/**
 * First present, non-empty value among a list of candidate keys.
 *
 * @param array    $item Source array.
 * @param string[] $keys Candidate keys.
 * @return mixed Empty string when nothing matches.
 */
function mv_pick( array $item, array $keys ) {
	foreach ( $keys as $key ) {
		if ( isset( $item[ $key ] ) && '' !== $item[ $key ] && null !== $item[ $key ] ) {
			return $item[ $key ];
		}
	}
	return '';
}

/**
 * Twice-daily background sync, scheduled only while an endpoint is set.
 */
function mv_schedule_plans_sync() {
	if ( mv_plans_api_url() && ! wp_next_scheduled( MV_PLAN_SYNC_HOOK ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'twicedaily', MV_PLAN_SYNC_HOOK );
	}
	if ( ! mv_plans_api_url() ) {
		$next = wp_next_scheduled( MV_PLAN_SYNC_HOOK );
		if ( $next ) {
			wp_unschedule_event( $next, MV_PLAN_SYNC_HOOK );
		}
	}
}
add_action( 'admin_init', 'mv_schedule_plans_sync' );
add_action( MV_PLAN_SYNC_HOOK, 'mv_sync_plans_from_api' );

/**
 * Drop the scheduled sync when the theme is switched away.
 */
function mv_unschedule_plans_sync() {
	$next = wp_next_scheduled( MV_PLAN_SYNC_HOOK );
	if ( $next ) {
		wp_unschedule_event( $next, MV_PLAN_SYNC_HOOK );
	}
}
add_action( 'switch_theme', 'mv_unschedule_plans_sync' );

/**
 * Sync settings screen under the plans menu.
 */
function mv_plans_sync_page() {
	add_submenu_page(
		'edit.php?post_type=' . MV_PLAN_CPT,
		'סנכרון מהמערכת',
		'סנכרון מהמערכת',
		'manage_options',
		'mv-plans-sync',
		'mv_render_plans_sync_page'
	);
}
add_action( 'admin_menu', 'mv_plans_sync_page' );

/**
 * Render the sync settings screen.
 */
function mv_render_plans_sync_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$synced   = (int) get_option( 'mv_plans_synced_at', 0 );
	$by_const = defined( 'MV_PLANS_API_URL' ) && MV_PLANS_API_URL;
	$notice   = isset( $_GET['mv_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['mv_msg'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only.
	$ok       = isset( $_GET['mv_ok'] ) && '1' === $_GET['mv_ok']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only.
	?>
	<div class="wrap">
		<h1>סנכרון מסלולים מהמערכת</h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-<?php echo $ok ? 'success' : 'error'; ?> is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<p>המסלולים באתר נבנים מתוך רשימת המסלולים שבתפריט הצד. אפשר לערוך אותם ידנית, ובנוסף לחבר כתובת API של המערכת — ואז הרשימה מתעדכנת אוטומטית פעמיים ביום.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'mv_plans_settings' ); ?>
			<input type="hidden" name="action" value="mv_plans_settings">
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="mv_plans_api_url">כתובת ה-API</label></th>
					<td>
						<input type="url" class="regular-text" id="mv_plans_api_url" name="mv_plans_api_url" value="<?php echo esc_attr( get_option( 'mv_plans_api_url', '' ) ); ?>" <?php disabled( $by_const ); ?>>
						<p class="description">
							<?php if ( $by_const ) : ?>
								מוגדר בקוד דרך הקבוע <code>MV_PLANS_API_URL</code>.
							<?php else : ?>
								למשל <code>https://app.metavchim.co.il/api/plans</code>. צריך להחזיר JSON עם רשימת חבילות.
							<?php endif; ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="mv_plans_api_key">מפתח API (אם נדרש)</label></th>
					<td>
						<input type="password" class="regular-text" id="mv_plans_api_key" name="mv_plans_api_key" value="" autocomplete="off" <?php disabled( defined( 'MV_PLANS_API_KEY' ) && MV_PLANS_API_KEY ); ?>>
						<p class="description">
							<?php if ( defined( 'MV_PLANS_API_KEY' ) && MV_PLANS_API_KEY ) : ?>
								מוגדר בקוד דרך הקבוע <code>MV_PLANS_API_KEY</code>.
							<?php else : ?>
								מומלץ להגדיר ב-<code>wp-config.php</code> כ-<code>MV_PLANS_API_KEY</code> במקום כאן. השארה ריקה לא מוחקת מפתח קיים.
							<?php endif; ?>
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'שמירה' ); ?>
		</form>

		<hr>
		<h2>סנכרון עכשיו</h2>
		<p>
			<?php if ( $synced ) : ?>
				סנכרון אחרון: <?php echo esc_html( wp_date( 'd.m.Y H:i', $synced ) ); ?>.
			<?php else : ?>
				טרם בוצע סנכרון.
			<?php endif; ?>
		</p>
		<p>חבילה שנעלמה מהמערכת עוברת לטיוטה ולא נמחקת, כדי לא לאבד עריכות ידניות.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'mv_plans_sync' ); ?>
			<input type="hidden" name="action" value="mv_plans_sync">
			<?php submit_button( 'משיכת החבילות מהמערכת', 'secondary' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Save the sync settings.
 */
function mv_handle_plans_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לבצע פעולה זו.', 'metavchim' ) );
	}
	check_admin_referer( 'mv_plans_settings' );

	if ( ! defined( 'MV_PLANS_API_URL' ) || ! MV_PLANS_API_URL ) {
		$url = isset( $_POST['mv_plans_api_url'] ) ? esc_url_raw( wp_unslash( $_POST['mv_plans_api_url'] ) ) : '';
		update_option( 'mv_plans_api_url', $url, false );
	}

	// An empty field leaves the stored key untouched.
	if ( ( ! defined( 'MV_PLANS_API_KEY' ) || ! MV_PLANS_API_KEY ) && ! empty( $_POST['mv_plans_api_key'] ) ) {
		update_option( 'mv_plans_api_key', sanitize_text_field( wp_unslash( $_POST['mv_plans_api_key'] ) ), false );
	}

	mv_schedule_plans_sync();
	mv_redirect_to_sync_page( true, 'ההגדרות נשמרו.' );
}
add_action( 'admin_post_mv_plans_settings', 'mv_handle_plans_settings' );

/**
 * Run the sync on demand.
 */
function mv_handle_plans_sync() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לבצע פעולה זו.', 'metavchim' ) );
	}
	check_admin_referer( 'mv_plans_sync' );

	$result = mv_sync_plans_from_api();
	mv_redirect_to_sync_page( $result['ok'], $result['message'] );
}
add_action( 'admin_post_mv_plans_sync', 'mv_handle_plans_sync' );

/**
 * Back to the sync screen with a message.
 *
 * @param bool   $ok      Whether the action succeeded.
 * @param string $message Message to show.
 */
function mv_redirect_to_sync_page( $ok, $message ) {
	wp_safe_redirect(
		add_query_arg(
			array(
				'post_type' => MV_PLAN_CPT,
				'page'      => 'mv-plans-sync',
				'mv_ok'     => $ok ? '1' : '0',
				'mv_msg'    => rawurlencode( $message ),
			),
			admin_url( 'edit.php' )
		)
	);
	exit;
}

/**
 * Seed the two launch plans so the section is populated on a fresh install.
 */
function mv_seed_plans() {
	if ( mv_get_plans() ) {
		return;
	}

	$seed = array(
		array(
			'title'    => 'סוכן עצמאי',
			'sub'      => 'כל מה שצריך כדי לנהל לבד מאגר, לידים והתאמות — כולל חיבור לרשת המשרדים.',
			'features' => "מאגר נכסים וקונים ללא הגבלה\nתמלול שיחות וכרטיס אוטומטי\nהתאמות בתוך המאגר שלך\nדף נחיתה לכל נכס\nחיבור לרשת המשרדים",
			'cta'      => 'התחלת ניסיון',
			'cta_url'  => '#cta',
			'badge'    => '',
			'dark'     => 0,
		),
		array(
			'title'    => 'משרד',
			'sub'      => 'הכול מהמסלול הקודם, ובנוסף שכבת הניהול: סוכנים, הרשאות, יעדים ודוחות.',
			'features' => "ניהול סוכנים והרשאות\nחלוקת לידים ומעקב טיפול\nיעדים ודוחות ביצועים\nניהול שיתופי פעולה ועמלות\nמיתוג המשרד על כל דף שקונה רואה",
			'cta'      => 'קביעת הדגמה',
			'cta_url'  => '#demo',
			'badge'    => 'הכי נבחר',
			'dark'     => 1,
		),
	);

	foreach ( $seed as $i => $plan ) {
		$plan_id = wp_insert_post(
			array(
				'post_type'   => MV_PLAN_CPT,
				'post_status' => 'publish',
				'post_title'  => $plan['title'],
				'menu_order'  => $i + 1,
			)
		);
		if ( is_wp_error( $plan_id ) || ! $plan_id ) {
			continue;
		}
		update_post_meta( $plan_id, '_mv_plan_sub', $plan['sub'] );
		update_post_meta( $plan_id, '_mv_plan_features', $plan['features'] );
		update_post_meta( $plan_id, '_mv_plan_cta_label', $plan['cta'] );
		update_post_meta( $plan_id, '_mv_plan_cta_url', $plan['cta_url'] );
		update_post_meta( $plan_id, '_mv_plan_badge', $plan['badge'] );
		update_post_meta( $plan_id, '_mv_plan_dark', $plan['dark'] );
	}
}

/**
 * זריעה חד-פעמית גם בהתקנות שכבר הכילו את התבנית לפני שהמסלולים
 * הפכו דינמיים — אחרת הסקשן היה נשאר ריק אחרי עדכון.
 */
function mv_maybe_seed_plans() {
	if ( get_option( 'mv_plans_seeded' ) ) {
		return;
	}
	mv_seed_plans();
	update_option( 'mv_plans_seeded', 1, false ); // No autoload.
}
add_action( 'admin_init', 'mv_maybe_seed_plans' );
