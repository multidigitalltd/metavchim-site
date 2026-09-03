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
		'clients',
		'network',
		'product',
		'orbit',
		'voice',
		'whatsapp',
		'companion',
		'capabilities',
		'security',
		'plans',
		'community',
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
 * @param string $excerpt Optional description used for search results and sharing.
 * @return int Page ID (0 on failure).
 */
function mv_install_page( $slug, $title, $content, $force = false, $excerpt = '' ) {
	$existing = get_page_by_path( $slug );

	if ( $existing instanceof WP_Post ) {
		if ( $force || '' === trim( $existing->post_content ) ) {
			$update = array(
				'ID'           => $existing->ID,
				'post_content' => $content,
				'post_status'  => 'publish',
			);
			if ( '' !== $excerpt ) {
				$update['post_excerpt'] = $excerpt;
			}

			kses_remove_filters();
			wp_update_post( $update );
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
			'post_excerpt'   => $excerpt,
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
		'marathon'      => array(
			'title'   => 'מרתון השת״פים',
			'content' => 'mv_get_event_content',
			'excerpt' => 'בוקר עבודה אחד: מעלים למערכת את הנכסים והקונים שיש לכם ביד, המערכת מצליבה בין כל מי שנמצא בחדר, ואתם סוגרים שיתופי פעולה בלייב — עם הסכם עמלה כתוב.',
		),
		'privacy'       => array(
			'title'   => 'פרטיות',
			'content' => 'mv_privacy_content',
		),
		'terms'         => array(
			'title'   => 'תנאי שימוש',
			'content' => 'mv_terms_content',
		),
		'accessibility' => array(
			'title'   => 'הצהרת נגישות',
			'content' => 'mv_accessibility_statement_content',
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
 * The event landing page content.
 *
 * @return string
 */
function mv_get_event_content() {
	return mv_get_section_html( 'marathon' );
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
			array( 'p', 'מדיניות זו מסבירה איזה מידע נאסף באתר מתווכים., לאיזו מטרה הוא משמש, עם מי הוא נחלק ואילו זכויות עומדות לך. המדיניות נכתבה בהתאם לחוק הגנת הפרטיות, התשמ"א-1981 ולתקנות שהותקנו מכוחו.' ),
			array( 'p', '<strong>בעל המאגר והאחראי על המידע:</strong> [שם החברה המלא], ח.פ. [מספר], [כתובת], דוא"ל: [כתובת דוא"ל ליצירת קשר].' ),

			array( 'h2', 'איזה מידע נאסף' ),
			array( 'p', '<strong>מידע שאתה מוסר ביוזמתך.</strong> בטופס תיאום ההדגמה שבאתר נאספים שם מלא, מספר טלפון, כתובת דוא"ל, ומועד מועדף להדגמה אם בחרת למלא אותו. בנוסף נשמרת כתובת העמוד שממנו נשלחה הפנייה. שדות אלה נדרשים כדי שנוכל לחזור אליך.' ),
			array( 'p', '<strong>מידע טכני שנאסף אוטומטית.</strong> בעת גלישה באתר עשויים להיאסף נתוני שימוש כלליים: סוג הדפדפן והמכשיר, מערכת ההפעלה, שפת הממשק, העמודים שנצפו, משך השהייה, המקור שממנו הגעת לאתר וכתובת IP מקוצרת. איסוף זה מתבצע רק לאחר שנתת הסכמה לכך, כמפורט בסעיף המדידה.' ),

			array( 'h2', 'למה המידע משמש' ),
			array( 'p', 'פרטי הקשר שאתה מוסר באתר נאספים ומשמשים אותנו למטרות הבאות:' ),
			array( 'p', '· יצירת קשר איתך, מענה לפנייתך ותיאום הדגמה של מערכת מתווכים.<br>· <strong>מכירה ושיווק</strong> של מערכת מתווכים והשירותים הנלווים לה, לרבות שיחות מכירה ומעקב.<br>· <strong>משלוח הצעות פרסומיות ודברי פרסומת</strong> בנוגע למערכת מתווכים ולעדכוני מוצר, בדוא"ל, במסרונים, בהודעות ווטסאפ או בשיחת טלפון.<br>· שיפור המוצר, השירות והאתר, וניתוח סטטיסטי של הביקושים.' ),
			array( 'p', 'מסירת הפרטים בטופס מהווה הסכמה לקבלת פניות ודברי פרסומת כאמור, בהתאם לסעיף 30א לחוק התקשורת (בזק ושידורים), התשמ"ב-1982. <strong>אפשר לבקש להסיר את פרטיך בכל עת</strong> — בהודעת חזרה, בקישור ההסרה שבתחתית כל דיוור, או בפנייה לכתובת הדוא"ל שבראש מדיניות זו — ונחדל מפנייה שיווקית אליך.' ),
			array( 'p', 'איננו מוכרים ואיננו משכירים את פרטיך לצדדים שלישיים, ואיננו מעבירים אותם לשימוש שיווקי של גורם אחר.' ),

			array( 'h2', 'מדידה וסטטיסטיקה — Google Analytics' ),
			array( 'p', 'באתר פועל שירות המדידה Google Analytics של Google Ireland Limited. השירות מסייע לנו להבין אילו עמודים מעניינים את המבקרים וכיצד לשפר את האתר. לצורך כך נשמרות בדפדפן שלך עוגיות (בהן <code>_ga</code> ו-<code>_ga_*</code>) המכילות מזהה אקראי, ולא את שמך.' ),
			array( 'p', '<strong>המדידה אינה נטענת עד לאישורך.</strong> בכניסה הראשונה לאתר מוצג באנר שבו אפשר לאשר או לדחות. עד לאישור לא נטען כל רכיב של גוגל ולא נשמרת אף עוגיית מדידה. דחייה נשמרת ומכובדת, ואפשר לשנות את הבחירה בכל רגע בקישור "הגדרות פרטיות" שבתחתית האתר. דפדפן המשדר אות פרטיות (Global Privacy Control) נחשב כמסרב אוטומטית.' ),
			array( 'p', 'המדידה מופעלת עם קיצור כתובת ה-IP. הנתונים מעובדים בשרתי גוגל ועשויים להיות מועברים אל מחוץ לישראל. פרטים על אופן העיבוד אצל גוגל: policies.google.com/privacy. אפשר גם להתקין את תוסף הביטול הרשמי של גוגל: tools.google.com/dlpage/gaoptout.' ),

			array( 'h2', 'אימות אנושי בטפסים' ),
			array( 'p', 'כדי למנוע שליחות אוטומטיות מבוטים, טופס יצירת הקשר עשוי להשתמש בשירות Cloudflare Turnstile. לצורך האימות נשלחים לשרתי Cloudflare כתובת ה-IP שלך ונתוני דפדפן בסיסיים. השירות אינו משמש למעקב פרסומי.' ),

			array( 'h2', 'עוגיות ואחסון מקומי' ),
			array( 'p', '· <strong>העדפות נגישות</strong> — סרגל הנגישות שומר בדפדפן שלך את הבחירות שביצעת (גודל טקסט, ניגודיות וכדומה). המידע נשאר במכשירך בלבד ואינו נשלח אלינו.<br>· <strong>בחירת ההסכמה למדידה</strong> — נשמרת בדפדפן כדי שלא נשאל אותך שוב.<br>· <strong>עוגיות מדידה</strong> — נכתבות רק לאחר אישורך, כמפורט לעיל.' ),
			array( 'p', 'אפשר למחוק עוגיות ואחסון מקומי דרך הגדרות הדפדפן בכל עת. מחיקה תאפס גם את בחירת ההסכמה ואת העדפות הנגישות.' ),

			array( 'h2', 'העברת מידע לצדדים שלישיים' ),
			array( 'p', 'מידע עשוי להיחשף לספקי שירות הפועלים עבורנו ובהתאם להוראותינו — שירותי אחסון האתר, שירותי דיוור, כלי ניהול לקוחות וספקי המדידה שלעיל. בנוסף, מידע יימסר אם נידרש לכך על פי דין, צו שיפוטי, או לצורך הגנה על זכויותינו.' ),

			array( 'h2', 'תקופת שמירת המידע' ),
			array( 'p', 'פרטי פנייה נשמרים כל עוד הם נדרשים למטרה שלשמה נמסרו ולתקופה נוספת הנדרשת על פי דין, ולכל היותר [מספר] שנים ממועד הפנייה או מסיום ההתקשרות. בקשת מחיקה תטופל בכפוף לחובות שמירה שבדין.' ),

			array( 'h2', 'הזכויות שלך' ),
			array( 'p', 'על פי חוק הגנת הפרטיות עומדת לך הזכות לעיין במידע המוחזק עליך במאגר, לבקש את תיקונו אם אינו נכון, שלם או מעודכן, ולבקש את מחיקתו. כמו כן אפשר לחזור בך מהסכמה לקבלת דיוור פרסומי בכל עת. לפנייה בעניין זה: [כתובת דוא"ל ליצירת קשר]. נשיב לפנייתך בתוך הזמן הקבוע בדין.' ),

			array( 'h2', 'אבטחת מידע' ),
			array( 'p', 'אנו נוקטים אמצעי אבטחה מקובלים להגנה על המידע, ובכללם הצפנת התעבורה לאתר, ניהול הרשאות גישה וגיבויים. עם זאת, אין באפשרותנו להבטיח חסינות מוחלטת מפני חדירה בלתי מורשית.' ),

			array( 'h2', 'קטינים' ),
			array( 'p', 'האתר מיועד לבעלי עסקים ואנשי מקצוע בתחום התיווך. איננו אוספים ביודעין מידע על קטינים מתחת לגיל 16.' ),

			array( 'h2', 'עדכוני מדיניות' ),
			array( 'p', 'מדיניות זו עשויה להתעדכן מעת לעת. הנוסח המחייב הוא הנוסח המפורסם בעמוד זה. עודכן לאחרונה: ' . wp_date( 'd.m.Y' ) . '.' ),
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

	// עמוד אחד לכל ערך ב-mv_theme_pages(), כך שהוספת עמוד לרשימה מספיקה.
	$ids = array();
	foreach ( mv_theme_pages() as $slug => $page ) {
		$ids[ $slug ] = mv_install_page(
			$slug,
			$page['title'],
			call_user_func( $page['content'] ),
			$force_home,
			isset( $page['excerpt'] ) ? $page['excerpt'] : ''
		);
		mv_sync_page_title( $ids[ $slug ], $page['title'] );
	}

	$home_id   = isset( $ids['home'] ) ? $ids['home'] : 0;
	$legal_ids = array(
		'terms'         => isset( $ids['terms'] ) ? $ids['terms'] : 0,
		'privacy'       => isset( $ids['privacy'] ) ? $ids['privacy'] : 0,
		'accessibility' => isset( $ids['accessibility'] ) ? $ids['accessibility'] : 0,
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

		mv_install_page( $slug, $page['title'], $content, true, isset( $page['excerpt'] ) ? $page['excerpt'] : '' );
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
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mv-content' ) ); ?>">מעבר לבנייה מחדש</a>
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
 * מסך "תוכן העמודים" בלוח הבקרה — רשימת העמודים של התבנית עם קישורי
 * עריכה, ובנייה מחדש מהתבנית בעת הצורך.
 */
function mv_register_content_admin_page() {
	add_submenu_page(
		MV_DASH_SLUG,
		'תוכן העמודים',
		'תוכן העמודים',
		'manage_options',
		'mv-content',
		'mv_render_content_admin_page'
	);
}
add_action( 'admin_menu', 'mv_register_content_admin_page', 12 );

/**
 * Render the content tools screen.
 */
function mv_render_content_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}

	$rebuilt = isset( $_GET['mv_done'] ) ? absint( $_GET['mv_done'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag.
	?>
	<div class="wrap">
		<h1>תוכן העמודים</h1>
		<?php if ( $rebuilt ) : ?>
			<div class="notice notice-success is-dismissible"><p>העמודים נבנו מחדש מהתבנית.</p></div>
		<?php endif; ?>

		<p>
			כל עמוד כאן הוא עמוד וורדפרס רגיל — לוחצים על "עריכה" ומשנים טקסטים ישירות בעורך.
			אזורים שמתעדכנים לבד (מספרים, לוגואים, רשתות חברתיות, פרטי האירוע והמסלולים)
			נערכים במסכים הייעודיים שלהם בלוח הבקרה.
		</p>

		<table class="widefat striped" style="max-width:820px">
			<thead>
				<tr><th>עמוד</th><th>מצב</th><th>פעולות</th></tr>
			</thead>
			<tbody>
				<?php foreach ( mv_theme_pages() as $slug => $page ) : ?>
					<?php $post = get_page_by_path( $slug ); ?>
					<tr>
						<td><strong><?php echo esc_html( $page['title'] ); ?></strong><br><code><?php echo esc_html( $slug ); ?></code></td>
						<td>
							<?php if ( $post instanceof WP_Post && '' !== trim( (string) $post->post_content ) ) : ?>
								<?php
								$stored = (string) get_post_meta( $post->ID, '_mv_content_hash', true );
								$edited = '' !== $stored && md5( (string) $post->post_content ) !== $stored;
								?>
								<?php if ( $edited ) : ?>
									<span style="color:#8a6d00">נערך ידנית</span>
								<?php else : ?>
									<span style="color:#116329">מסונכרן עם התבנית</span>
								<?php endif; ?>
							<?php else : ?>
								<span style="color:#b32d2e">חסר</span>
							<?php endif; ?>
						</td>
						<td>
							<?php if ( $post instanceof WP_Post ) : ?>
								<a href="<?php echo esc_url( (string) get_edit_post_link( $post->ID ) ); ?>">עריכה</a> ·
								<a href="<?php echo esc_url( (string) get_permalink( $post->ID ) ); ?>" target="_blank" rel="noopener">צפייה</a>
							<?php else : ?>
								—
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<h2>בנייה מחדש מהתבנית</h2>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'mv_rebuild_content' ); ?>
			<input type="hidden" name="action" value="mv_rebuild_content">
			<p>הפעולה כותבת מחדש את כל העמודים לפי הגרסה העדכנית של התבנית ומשלימה עמודים חסרים.</p>
			<p><strong>שימו לב:</strong> עריכות ידניות בעמודים האלה יידרסו.</p>
			<?php submit_button( 'בנייה מחדש של העמודים' ); ?>
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

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'    => 'mv-content',
				'mv_done' => 1,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_mv_rebuild_content', 'mv_handle_rebuild_content' );
