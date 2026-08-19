<?php
/**
 * One-time content installer.
 *
 * מותקן בהפעלת התבנית: כל סקשן של עמוד הבית נשמר כתוכן העמוד עצמו
 * (בלוקים ב-post_content) — כך התוכן הוא תוכן עמודים אמיתי שניתן
 * לעריכה בעורך, ולא HTML קשיח שמולבש על הכתובות מתוך קבצי התבנית.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ordered list of homepage section pattern slugs (file names in /patterns).
 *
 * @return string[]
 */
function mv_home_section_slugs() {
	return array(
		'hero',
		'stats',
		'network',
		'product',
		'orbit',
		'voice',
		'features',
		'capabilities',
		'security',
		'plans',
		'cta',
	);
}

/**
 * Read a section pattern file and return its HTML content.
 *
 * @param string $slug Pattern file name without extension.
 * @return string
 */
function mv_get_section_html( $slug ) {
	$file = MV_THEME_DIR . '/patterns/' . sanitize_file_name( $slug . '.php' );
	if ( ! file_exists( $file ) ) {
		return '';
	}
	ob_start();
	include $file;
	return trim( (string) ob_get_clean() );
}

/**
 * Full homepage content assembled from the section patterns.
 *
 * @return string
 */
function mv_get_home_content() {
	$html = '';
	foreach ( mv_home_section_slugs() as $slug ) {
		$section = mv_get_section_html( $slug );
		if ( $section ) {
			$html .= $section . "\n\n";
		}
	}
	return trim( $html );
}

/**
 * Render the bundled sections directly (fallback when the installer has not
 * run yet — the design renders identically either way).
 */
function mv_render_default_sections() {
	// Pattern files contain static, theme-authored markup only.
	echo do_shortcode( do_blocks( mv_get_home_content() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Create (or repair) a page by slug and return its ID.
 *
 * התוכן הוא HTML שנכתב בקבצי התבנית עצמה, ולכן נשמר תוך עקיפת מסנני
 * kses — אחרת הפעלה ללא משתמש בעל unfiltered_html (WP-CLI, פאנל אחסון)
 * מוחקת את ה-SVG ורוב המרקאפ ומשאירה עמוד ריק.
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title.
 * @param string $content Block content (theme-authored, trusted).
 * @param bool   $force   Overwrite existing content even when not empty.
 * @return int Page ID (0 on failure).
 */
function mv_install_page( $slug, $title, $content, $force = false ) {
	$existing = get_page_by_path( $slug );

	if ( $existing instanceof WP_Post ) {
		if ( $force || '' === trim( $existing->post_content ) ) {
			kses_remove_filters();
			wp_update_post(
				array(
					'ID'           => $existing->ID,
					'post_content' => $content,
					'post_status'  => 'publish',
				)
			);
			kses_init_filters();
		}
		return (int) $existing->ID;
	}

	kses_remove_filters();
	$page_id = (int) wp_insert_post(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_name'      => $slug,
			'post_title'     => $title,
			'post_content'   => $content,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		)
	);
	kses_init_filters();

	return $page_id;
}

/**
 * Whether an installed page exists and actually holds content.
 *
 * @param string $slug Page slug.
 * @return bool
 */
function mv_page_content_ok( $slug ) {
	$page = get_page_by_path( $slug );
	return $page instanceof WP_Post && '' !== trim( $page->post_content );
}

/**
 * Whether the installed home page exists and actually holds content.
 *
 * @return bool
 */
function mv_home_page_ok() {
	return mv_page_content_ok( 'home' );
}

/**
 * The collaboration page content, assembled from its section pattern.
 *
 * @return string
 */
function mv_get_collab_content() {
	return mv_get_section_html( 'collaboration' );
}

/**
 * Keep an installed page's title in sync when the theme renames it.
 *
 * @param int    $page_id Page ID.
 * @param string $title   Expected title.
 */
function mv_sync_page_title( $page_id, $title ) {
	if ( ! $page_id ) {
		return;
	}
	$page = get_post( $page_id );
	if ( $page instanceof WP_Post && $page->post_title !== $title ) {
		wp_update_post(
			array(
				'ID'         => $page_id,
				'post_title' => $title,
			)
		);
	}
}

/**
 * Wrap plain paragraphs/headings into core block markup.
 *
 * @param array $blocks List of [ tag, text ] pairs.
 * @return string
 */
function mv_blocks( array $blocks ) {
	$out = '';
	foreach ( $blocks as $block ) {
		list( $tag, $text ) = $block;
		if ( 'h2' === $tag ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $text . "</h2>\n<!-- /wp:heading -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return trim( $out );
}

/**
 * Accessibility statement content (ת"י 5568 skeleton — fill org details).
 *
 * @return string
 */
function mv_accessibility_statement_content() {
	return mv_blocks(
		array(
			array( 'p', 'אנו במתווכים. רואים חשיבות עליונה במתן שירות שוויוני ונגיש לכלל הציבור, לרבות אנשים עם מוגבלות, בהתאם לחוק שוויון זכויות לאנשים עם מוגבלות התשנ"ח-1998, לתקנות שהותקנו מכוחו ולתקן הישראלי ת"י 5568 המבוסס על הנחיות WCAG 2.2 ברמה AA.' ),
			array( 'h2', 'התאמות הנגישות באתר' ),
			array( 'p', 'האתר נבנה עם HTML סמנטי, תמיכה מלאה בניווט מקלדת, מצבי פוקוס ברורים, קישור דילוג לתוכן, טקסט חלופי לתמונות, ניגודיות צבעים תקינה ותמיכה בקוראי מסך (NVDA, JAWS, VoiceOver, TalkBack). האתר מכבד את העדפת המשתמש להפחתת תנועה (prefers-reduced-motion).' ),
			array( 'p', 'בנוסף פועל באתר סרגל נגישות המאפשר: הגדלת והקטנת טקסט, ניגודיות גבוהה, היפוך צבעים, גווני אפור, הדגשת קישורים, פונט קריא, עצירת אנימציות, הבלטת כותרות וקישורים וסרגל קריאה. ההעדפות נשמרות בדפדפן המשתמש.' ),
			array( 'h2', 'הסדרי נגישות' ),
			array( 'p', 'השירות ניתן באופן מקוון. ככל שנתקלת בבעיה או תקלה בנושא נגישות, נשמח שתעדכן אותנו ונפעל לתקנה בהקדם.' ),
			array( 'h2', 'פניות בנושא נגישות' ),
			array( 'p', 'רכז הנגישות: [שם הרכז] · טלפון: [טלפון] · דוא"ל: [כתובת דוא"ל]. אנא צרפו תיאור של הבעיה, כתובת העמוד והטכנולוגיה המסייעת שבה השתמשתם.' ),
			array( 'p', 'הצהרת הנגישות עודכנה לאחרונה בתאריך: ' . wp_date( 'd.m.Y' ) . '.' ),
		)
	);
}

/**
 * Terms page placeholder content.
 *
 * @return string
 */
function mv_terms_content() {
	return mv_blocks(
		array(
			array( 'p', 'תנאי השימוש במערכת מתווכים. יפורסמו בעמוד זה. יש להשלים את הנוסח המשפטי המלא מול הייעוץ המשפטי של החברה.' ),
		)
	);
}

/**
 * Privacy page placeholder content.
 *
 * @return string
 */
function mv_privacy_content() {
	return mv_blocks(
		array(
			array( 'p', 'מדיניות הפרטיות של מתווכים. תפורסם בעמוד זה, בהתאם לחוק הגנת הפרטיות התשמ"א-1981 ותקנותיו. יש להשלים את הנוסח המלא מול הייעוץ המשפטי של החברה.' ),
		)
	);
}

/**
 * Make sure the primary menu links to the collaboration page.
 *
 * Runs on every install/repair so menus created before the page existed
 * gain the link, and an item carrying the previous label is renamed.
 *
 * @param int $menu_id Primary menu term ID.
 */
function mv_ensure_collab_menu_item( $menu_id ) {
	$page = get_page_by_path( 'collaboration' );
	if ( ! $menu_id || ! $page instanceof WP_Post ) {
		return;
	}

	$url   = get_permalink( $page );
	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! is_array( $items ) ) {
		$items = array();
	}

	foreach ( $items as $item ) {
		$is_page_item = ( 'post_type' === $item->type && (int) $item->object_id === (int) $page->ID );
		$is_url_item  = ( untrailingslashit( $item->url ) === untrailingslashit( $url ) );

		if ( $is_page_item || $is_url_item ) {
			if ( mv_collab_label() !== $item->title ) {
				wp_update_nav_menu_item(
					$menu_id,
					$item->ID,
					array(
						'menu-item-title'  => mv_collab_label(),
						'menu-item-url'    => $url,
						'menu-item-status' => 'publish',
					)
				);
			}
			return;
		}
	}

	// Not present yet — add it just before "מסלולים" when that item exists.
	$new_id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => mv_collab_label(),
			'menu-item-url'    => $url,
			'menu-item-status' => 'publish',
		)
	);

	if ( is_wp_error( $new_id ) ) {
		return;
	}

	foreach ( $items as $item ) {
		if ( 'מסלולים' === $item->title ) {
			wp_update_post(
				array(
					'ID'         => $new_id,
					'menu_order' => (int) $item->menu_order,
				)
			);
			wp_update_post(
				array(
					'ID'         => $item->ID,
					'menu_order' => (int) $item->menu_order + 1,
				)
			);
			break;
		}
	}
}

/**
 * Create the primary + footer menus once.
 *
 * @param array $legal_ids Page IDs keyed by slug.
 */
function mv_install_menus( array $legal_ids ) {
	// Primary anchors menu — reuse an existing menu of the same name.
	$existing = wp_get_nav_menu_object( 'ראשי' );
	$menu_id  = $existing ? (int) $existing->term_id : wp_create_nav_menu( 'ראשי' );
	if ( ! is_wp_error( $menu_id ) ) {
		if ( ! $existing ) {
			$anchors = array(
				'#product'       => 'המערכת',
				'#voice'         => 'סוכן קולי',
				'#security'      => 'אבטחה',
				'collaboration/' => mv_collab_label(),
				'#plans'         => 'מסלולים',
			);
			foreach ( $anchors as $target => $label ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => $label,
						'menu-item-url'    => home_url( '/' ) . $target,
						'menu-item-status' => 'publish',
					)
				);
			}
		}

		// Menus created before this page existed still need the link.
		mv_ensure_collab_menu_item( (int) $menu_id );

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = (int) $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	// Footer legal menu — same reuse-or-create logic.
	$existing = wp_get_nav_menu_object( 'פוטר' );
	$menu_id  = $existing ? (int) $existing->term_id : wp_create_nav_menu( 'פוטר' );
	if ( ! is_wp_error( $menu_id ) ) {
		if ( ! $existing ) {
			foreach ( $legal_ids as $page_id ) {
				if ( $page_id ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-object-id' => $page_id,
							'menu-item-object'    => 'page',
							'menu-item-type'      => 'post_type',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
		}
		$locations           = get_theme_mod( 'nav_menu_locations', array() );
		$locations['footer'] = (int) $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * Install pages + menus (idempotent, self-healing).
 *
 * @param bool $force_home Overwrite the home page content from the theme
 *                         patterns even when the page already has content.
 */
function mv_install_content( $force_home = false ) {
	if ( ! $force_home && get_option( 'mv_content_installed' ) && mv_home_page_ok() && mv_page_content_ok( 'collaboration' ) ) {
		return;
	}

	$home_id = mv_install_page( 'home', 'בית', mv_get_home_content(), $force_home );

	$collab_id = mv_install_page( 'collaboration', mv_collab_label(), mv_get_collab_content(), $force_home );
	mv_sync_page_title( $collab_id, mv_collab_label() );

	$legal_ids = array(
		'terms'         => mv_install_page( 'terms', 'תנאי שימוש', mv_terms_content() ),
		'privacy'       => mv_install_page( 'privacy', 'פרטיות', mv_privacy_content() ),
		'accessibility' => mv_install_page( 'accessibility', 'הצהרת נגישות', mv_accessibility_statement_content() ),
	);

	if ( $home_id && mv_home_page_ok() ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	mv_install_menus( $legal_ids );
	mv_maybe_seed_plans();

	update_option( 'mv_content_installed', 1, false ); // No autoload.
}
add_action( 'after_switch_theme', 'mv_install_content' );

/**
 * Self-heal: if the home page is missing or was stripped empty (e.g. the
 * theme was activated without a privileged user), reinstall on the next
 * admin visit. Cheap when healthy — a single cached page lookup.
 */
function mv_maybe_repair_content() {
	if ( wp_doing_ajax() || wp_doing_cron() || ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( ! mv_home_page_ok() || ! mv_page_content_ok( 'collaboration' ) ) {
		mv_install_content();
	}
}
add_action( 'admin_init', 'mv_maybe_repair_content' );

/**
 * Admin screen: עיצוב ← תוכן התבנית — rebuild the home page from the
 * theme's section patterns on demand (e.g. after a theme update).
 */
function mv_register_content_admin_page() {
	add_theme_page(
		'תוכן התבנית',
		'תוכן התבנית',
		'manage_options',
		'mv-content',
		'mv_render_content_admin_page'
	);
}
add_action( 'admin_menu', 'mv_register_content_admin_page' );

/**
 * Render the content tools screen.
 */
function mv_render_content_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$rebuilt = isset( $_GET['mv_done'] ) ? absint( $_GET['mv_done'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag.
	$home    = get_page_by_path( 'home' );
	?>
	<div class="wrap">
		<h1>תוכן התבנית — מתווכים</h1>
		<?php if ( $rebuilt ) : ?>
			<div class="notice notice-success is-dismissible"><p>עמוד הבית נבנה מחדש מהסקשנים של התבנית.</p></div>
		<?php endif; ?>
		<p>
			מצב עמוד הבית:
			<?php if ( mv_home_page_ok() && $home instanceof WP_Post ) : ?>
				<strong style="color:#0B6E35">תקין</strong> —
				<a href="<?php echo esc_url( get_edit_post_link( $home->ID ) ); ?>">עריכת העמוד</a> ·
				<a href="<?php echo esc_url( get_permalink( $home->ID ) ); ?>">צפייה</a>
			<?php else : ?>
				<strong style="color:#b32d2e">חסר או ריק</strong>
			<?php endif; ?>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'mv_rebuild_content' ); ?>
			<input type="hidden" name="action" value="mv_rebuild_content">
			<p>בנייה מחדש שומרת את כל הסקשנים העדכניים של התבנית לתוך תוכן עמוד הבית, יוצרת עמודים משפטיים חסרים ומגדירה את העמוד כעמוד הבית הסטטי.</p>
			<p><strong>שים לב:</strong> הפעולה דורסת עריכות ידניות שבוצעו בתוכן עמוד הבית.</p>
			<?php submit_button( 'בנייה מחדש של עמוד הבית מהתבנית' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Handle the rebuild action (nonce + capability checked).
 */
function mv_handle_rebuild_content() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לבצע פעולה זו.', 'metavchim' ) );
	}
	check_admin_referer( 'mv_rebuild_content' );

	mv_install_content( true );

	wp_safe_redirect( add_query_arg( array( 'page' => 'mv-content', 'mv_done' => 1 ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_mv_rebuild_content', 'mv_handle_rebuild_content' );
