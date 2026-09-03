<?php
/**
 * לוח הבקרה של האתר.
 *
 * תפריט אחד בשם "מתווכים" שמרכז את כל מה שבעל האתר צריך לערוך:
 * תוכן העמודים, המספרים, הרשתות החברתיות, הלוגואים של הלקוחות,
 * פרטי האירוע, המסלולים והפניות. המסכים עצמם יושבים בקבצים
 * הייעודיים שלהם ונרשמים כתת-תפריטים כאן.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * מזהה תפריט לוח הבקרה.
 */
const MV_DASH_SLUG = 'mv-dashboard';

/**
 * רישום התפריט הראשי. שאר המסכים נרשמים בעדיפות מאוחרת יותר
 * כדי שהתפריט כבר יהיה קיים.
 */
function mv_dashboard_menu() {
	add_menu_page(
		'מתווכים',
		'מתווכים',
		'manage_options',
		MV_DASH_SLUG,
		'mv_render_dashboard',
		'dashicons-admin-home',
		3
	);

	add_submenu_page(
		MV_DASH_SLUG,
		'לוח בקרה',
		'לוח בקרה',
		'manage_options',
		MV_DASH_SLUG,
		'mv_render_dashboard'
	);
}
add_action( 'admin_menu', 'mv_dashboard_menu', 5 );

/**
 * הכרטיסים בלוח הבקרה.
 *
 * @return array<int,array{title:string,text:string,url:string}>
 */
function mv_dashboard_cards() {
	$cards = array(
		array(
			'title' => 'תוכן העמודים',
			'text'  => 'עריכת הטקסטים של עמוד הבית ושאר העמודים, בעורך הרגיל של וורדפרס.',
			'url'   => admin_url( 'admin.php?page=mv-content' ),
		),
		array(
			'title' => 'מספרים באתר',
			'text'  => 'רצועת הנתונים בראש עמוד הבית: כמה מתווכים, משרדים ושיתופי פעולה.',
			'url'   => admin_url( 'admin.php?page=mv-numbers' ),
		),
		array(
			'title' => 'רשתות חברתיות',
			'text'  => 'קישורים לעמוד הפייסבוק, לקהילת הווטסאפ ולשאר הרשתות.',
			'url'   => admin_url( 'admin.php?page=mv-social' ),
		),
		array(
			'title' => 'מי כבר איתנו',
			'text'  => 'הלוגואים של המשרדים והסוכנויות שמוצגים בעמוד הבית.',
			'url'   => admin_url( 'admin.php?page=mv-clients' ),
		),
		array(
			'title' => 'פניות מהאתר',
			'text'  => 'כל מי שהשאיר פרטים: הדגמות, הרשמות לאירוע ורשימת התפוצה.',
			'url'   => admin_url( 'edit.php?post_type=' . MV_LEAD_CPT ),
		),
		array(
			'title' => 'פרטי האירוע',
			'text'  => 'תאריך, שעה, כתובת וטלפון של דף הנחיתה למרתון.',
			'url'   => admin_url( 'edit.php?post_type=' . MV_LEAD_CPT . '&page=mv-event' ),
		),
		array(
			'title' => 'מסלולים',
			'text'  => 'סנכרון החבילות מהמערכת ועריכת התוויות והכפתורים.',
			'url'   => admin_url( 'edit.php?post_type=mv_plan' ),
		),
		array(
			'title' => 'מדידה ופרטיות',
			'text'  => 'מזהה Google Analytics ובאנר ההסכמה.',
			'url'   => admin_url( 'options-general.php?page=mv-analytics' ),
		),
		array(
			'title' => 'הגנה על הטפסים',
			'text'  => 'מפתחות Cloudflare Turnstile לטפסים הציבוריים.',
			'url'   => admin_url( 'options-general.php?page=mv-turnstile' ),
		),
	);

	return $cards;
}

/**
 * מסך לוח הבקרה.
 */
function mv_render_dashboard() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'אין הרשאה לצפות בעמוד זה.' );
	}
	?>
	<div class="wrap mv-dash">
		<h1>לוח הבקרה של מתווכים</h1>
		<p>כל מה שאפשר לערוך באתר נמצא כאן. אין צורך לגעת בקוד — מה שמשנים נשמר ומופיע באתר מיד.</p>

		<div class="mv-dash-grid">
			<?php foreach ( mv_dashboard_cards() as $card ) : ?>
				<a class="mv-dash-card" href="<?php echo esc_url( $card['url'] ); ?>">
					<strong><?php echo esc_html( $card['title'] ); ?></strong>
					<span><?php echo esc_html( $card['text'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<h2>איך התוכן באתר עובד</h2>
		<p>
			העמודים נבנים מהתבנית ונשמרים כעמודים רגילים, כך שאפשר לערוך אותם בעורך.
			עמוד שלא נגעתם בו מתעדכן לבד כשיוצאת גרסה חדשה של התבנית; עמוד שערכתם
			נשאר כפי שהוא, ונקבל הודעה במסך "תוכן העמודים" אם יש בו עדכון ממתין.
		</p>
	</div>
	<?php
}

/**
 * עיצוב קצר למסכי לוח הבקרה.
 */
function mv_dashboard_styles( $hook ) {
	if ( false === strpos( (string) $hook, 'mv-' ) ) {
		return;
	}
	$css = '.mv-dash-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;margin:20px 0 28px}'
		. '.mv-dash-card{display:block;padding:18px 20px;background:#fff;border:1px solid #dcdfd9;border-radius:12px;text-decoration:none;color:inherit;transition:border-color .15s ease,box-shadow .15s ease}'
		. '.mv-dash-card:hover{border-color:#3FBF63;box-shadow:0 6px 18px rgba(16,24,18,.08)}'
		. '.mv-dash-card strong{display:block;font-size:15px;margin-bottom:6px}'
		. '.mv-dash-card span{color:#50575e;font-size:13px;line-height:1.5}'
		. '.mv-rows{margin:18px 0;border-collapse:collapse}'
		. '.mv-rows td{padding:6px 8px 6px 0;vertical-align:middle}'
		. '.mv-rows img{max-width:120px;max-height:46px;width:auto;height:auto;background:#f6f7f7;border:1px solid #dcdfd9;border-radius:8px;padding:6px}'
		. '.mv-row-empty{color:#8a8f8a}';
	wp_add_inline_style( 'common', $css );
}
add_action( 'admin_enqueue_scripts', 'mv_dashboard_styles' );

/**
 * קיצור דרך ללוח הבקרה מסרגל הניהול העליון.
 *
 * @param WP_Admin_Bar $bar סרגל הניהול.
 */
function mv_dashboard_admin_bar( $bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$bar->add_node(
		array(
			'id'    => 'mv-dashboard',
			'title' => 'מתווכים',
			'href'  => admin_url( 'admin.php?page=' . MV_DASH_SLUG ),
		)
	);
}
add_action( 'admin_bar_menu', 'mv_dashboard_admin_bar', 80 );
