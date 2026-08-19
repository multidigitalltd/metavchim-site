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
			update_post_meta( $existing->ID, '_mv_content_hash', md5( $content ) );
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

	if ( $page_id ) {
		update_post_meta( $page_id, '_mv_content_hash', md5( $content ) );
	}

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
 * Slugs of the pages the theme owns and keeps populated.
 *
 * Any page added here is created on activation and restored on the next
 * admin request of an existing site — that is what carries new pages into
 * installs that were set up with an earlier version of the theme.
 *
 * @return string[]
 */
function mv_theme_page_slugs() {
	return array_keys( mv_theme_pages() );
}

/**
 * The pages the theme owns: slug => title + content builder.
 *
 * @return array<string,array{title:string,content:callable}>
 */
function mv_theme_pages() {
	return array(
		'home'          => array(
			'title'   => 'בית',
			'content' => 'mv_get_home_content',
		),
		'collaboration' => array(
			'title'   => mv_collab_label(),
			'content' => 'mv_get_collab_content',
		),
		'about'         => array(
			'title'   => 'אודות',
			'content' => 'mv_get_about_content',
		),
	);
}

/**
 * Whether every page the theme owns exists and holds content.
 *
 * @return bool
 */
function mv_theme_pages_ok() {
	foreach ( mv_theme_page_slugs() as $slug ) {
		if ( ! mv_page_content_ok( $slug ) ) {
			return false;
		}
	}
	return true;
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
 * The about page content, assembled from its section pattern.
 *
 * @return string
 */
function mv_get_about_content() {
	return mv_get_section_html( 'about' );
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
 * Make sure the primary menu links to a theme page.
 *
 * Runs on every install/repair so menus created before the page existed
 * gain the link, and an item carrying a previous label is renamed.
 *
 * @param int    $menu_id      Primary menu term ID.
 * @param string $slug         Page slug.
 * @param string $label        Menu label.
 * @param string $before_label Existing item to sit in front of, when present.
 */
function mv_ensure_menu_item( $menu_id, $slug, $label, $before_label = '' ) {
	$page = get_page_by_path( $slug );
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
			if ( $label !== $item->title ) {
				wp_update_nav_menu_item(
					$menu_id,
					$item->ID,
					array(
						'menu-item-title'  => $label,
						'menu-item-url'    => $url,
						'menu-item-status' => 'publish',
					)
				);
			}
			return;
		}
	}

	$new_id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => $label,
			'menu-item-url'    => $url,
			'menu-item-status' => 'publish',
		)
	);

	if ( is_wp_error( $new_id ) || '' === $before_label ) {
		return;
	}

	foreach ( $items as $item ) {
		if ( $before_label === $item->title ) {
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

		// Menus created before these pages existed still need the links.
		mv_ensure_menu_item( (int) $menu_id, 'collaboration', mv_collab_label(), 'מסלולים' );
		mv_ensure_menu_item( (int) $menu_id, 'about', 'אודות' );

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
	if ( ! $force_home && get_option( 'mv_content_installed' ) && mv_theme_pages_ok() ) {
		return;
	}

	$home_id = mv_install_page( 'home', 'בית', mv_get_home_content(), $force_home );

	$collab_id = mv_install_page( 'collaboration', mv_collab_label(), mv_get_collab_content(), $force_home );
	mv_sync_page_title( $collab_id, mv_collab_label() );

	mv_install_page( 'about', 'אודות', mv_get_about_content(), $force_home );

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
function mv_pages_needing_rebuild( $add = null ) {
	static $titles = array();
	if ( null !== $add ) {
		$titles[] = $add;
	}
	return $titles;
}

/**
 * רענון תוכן העמודים כשגרסה חדשה של התבנית מביאה נוסח חדש.
 *
 * עמוד שלא נערך ידנית (התוכן שלו זהה למה שהתבנית כתבה) מתעדכן לבד —
 * אחרת שינוי טקסט בתבנית לא היה מגיע לאתר בלי בנייה מחדש. עמוד שנערך
 * בעורך לא נגענו בו, ובמקום זה מוצגת הודעה בלוח הבקרה. בכל מקרה
 * וורדפרס שומר גרסה קודמת, כך שאפשר לשחזר.
 */
function mv_refresh_theme_pages() {
	if ( wp_doing_ajax() || wp_doing_cron() || ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	foreach ( mv_theme_pages() as $slug => $page ) {
		$post = get_page_by_path( $slug );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		$content = call_user_func( $page['content'] );
		if ( '' === trim( (string) $content ) ) {
			continue;
		}

		$stored = (string) get_post_meta( $post->ID, '_mv_content_hash', true );
		if ( $stored === md5( $content ) ) {
			continue; // כבר מעודכן.
		}

		// נערך ידנית אחרי שהתבנית כתבה אותו — לא דורסים.
		if ( '' !== $stored && md5( $post->post_content ) !== $stored ) {
			mv_pages_needing_rebuild( $page['title'] );
			continue;
		}

		mv_install_page( $slug, $page['title'], $content, true );
	}
}
add_action( 'admin_init', 'mv_refresh_theme_pages', 11 );

/**
 * הודעה על עמודים שנערכו ידנית ולכן לא רועננו.
 */
function mv_rebuild_notice() {
	$titles = mv_pages_needing_rebuild();
	if ( ! $titles || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning">
		<p>
			גרסה חדשה של התבנית כוללת נוסח מעודכן לעמודים:
			<strong><?php echo esc_html( implode( ', ', $titles ) ); ?></strong>.
			העמודים האלה נערכו ידנית ולכן לא עודכנו אוטומטית.
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=mv-content' ) ); ?>">מעבר לבנייה מחדש</a>
			(העריכות הקיימות יישמרו בהיסטוריית הגרסאות של העמוד).
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'mv_rebuild_notice' );

/**
 * Restore missing or emptied theme pages.
 */
function mv_maybe_repair_content() {
	if ( wp_doing_ajax() || wp_doing_cron() || ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( ! mv_theme_pages_ok() ) {
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
