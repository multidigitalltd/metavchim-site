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
		'security',
		'onboarding',
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
	echo do_blocks( mv_get_home_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Create a page once (by slug) and return its ID.
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title.
 * @param string $content Block content.
 * @return int Page ID (0 on failure).
 */
function mv_install_page( $slug, $title, $content ) {
	$existing = get_page_by_path( $slug );
	if ( $existing instanceof WP_Post ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
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
				'#network'  => 'שיתופי פעולה',
				'#product'  => 'המערכת',
				'#voice'    => 'סוכן קולי',
				'#security' => 'אבטחה',
				'#start'    => 'הטמעה',
				'#plans'    => 'מסלולים',
			);
			foreach ( $anchors as $anchor => $label ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => $label,
						'menu-item-url'    => home_url( '/' ) . $anchor,
						'menu-item-status' => 'publish',
					)
				);
			}
		}
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
 * Install pages + menus on theme activation (idempotent).
 */
function mv_install_content() {
	if ( get_option( 'mv_content_installed' ) ) {
		return;
	}

	$home_id = mv_install_page( 'home', 'בית', mv_get_home_content() );

	$legal_ids = array(
		'terms'         => mv_install_page( 'terms', 'תנאי שימוש', mv_terms_content() ),
		'privacy'       => mv_install_page( 'privacy', 'פרטיות', mv_privacy_content() ),
		'accessibility' => mv_install_page( 'accessibility', 'הצהרת נגישות', mv_accessibility_statement_content() ),
	);

	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	mv_install_menus( $legal_ids );

	update_option( 'mv_content_installed', 1, false ); // No autoload.
}
add_action( 'after_switch_theme', 'mv_install_content' );
