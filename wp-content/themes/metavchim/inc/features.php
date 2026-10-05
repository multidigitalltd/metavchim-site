<?php
/**
 * מפת היכולות של המערכת — מקור אחד לאמת.
 *
 * הרשימה נגזרת מהתיעוד הרשמי של המערכת (app.metavchim.co.il/docs), כדי
 * שהאתר יציג בדיוק את מה שהמערכת עושה. אותה רשימה מזינה גם את האזור
 * "כל מה שהמערכת עושה בשבילך" בעמוד הבית וגם את התפריט הנפתח
 * "פיצ'רים במערכת" בכותרת העליונה, כדי שלא תיווצר כפילות בין השניים.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * קבוצות היכולות: כותרת, ופריטים עם אייקון, שם והסבר קצר.
 *
 * @return array<int,array{title:string,items:array<int,array{icon:string,title:string,text:string}>}>
 */
function mv_feature_groups() {
	return array(
		array(
			'title' => 'מאגר וניהול יומיומי',
			'items' => array(
				array(
					'icon'  => 'building',
					'title' => 'מאגר נכסים',
					'text'  => 'כרטיס אחד לכל נכס — פרטים, תמונות, מסמכים ובעלים.',
				),
				array(
					'icon'  => 'users',
					'title' => 'קונים ושוכרים',
					'text'  => 'מה כל לקוח מחפש, מעודכן אחרי כל שיחה.',
				),
				array(
					'icon'  => 'target',
					'title' => 'דירוג בשלות',
					'text'  => 'חם מאוד עד לא בשל — מי בראש התור היום.',
				),
				array(
					'icon'  => 'gauge',
					'title' => 'מוכנות לשיווק',
					'text'  => 'אחוז לכל נכס, ולחיצה מראה מה עוד חסר.',
				),
				array(
					'icon'  => 'match',
					'title' => 'מנוע התאמות',
					'text'  => 'מצליב נכסים וקונים ברקע, עם אחוז והסבר.',
				),
				array(
					'icon'  => 'sparkle',
					'title' => 'מה לטפל בו עכשיו',
					'text'  => 'הדשבורד אוסף את מה שדוחק, לפי דחיפות.',
				),
				array(
					'icon'  => 'card',
					'title' => 'איחוד כפילויות',
					'text'  => 'שני כרטיסים של אותו אדם מתאחדים בלי לאבד היסטוריה.',
				),
				array(
					'icon'  => 'upload',
					'title' => 'ייבוא מאקסל',
					'text'  => 'המאגר הקיים נכנס כמו שהוא, עם מיפוי עמודות.',
				),
			),
		),
		array(
			'title' => 'לידים, שיחות ותקשורת',
			'items' => array(
				array(
					'icon'  => 'funnel',
					'title' => 'לידים מכל הערוצים',
					'text'  => 'אתר, פייסבוק, דף נחיתה ומייל — ברשימה אחת.',
				),
				array(
					'icon'  => 'link',
					'title' => 'קליטה אוטומטית (API)',
					'text'  => 'כתובת ומפתח לכל ערוץ, כדי לדעת מאיפה הגיע כל ליד.',
				),
				array(
					'icon'  => 'whatsapp',
					'title' => 'וואטסאפ ביזנס לסוכן',
					'text'  => 'המספר העסקי שלכם מחובר — פנייה נכנסת כליד.',
				),
				array(
					'icon'  => 'phone',
					'title' => 'מרכזייה וסופטפון',
					'text'  => 'שיחות נכנסות למערכת, חיוג בלחיצה ומענה מהדפדפן.',
				),
				array(
					'icon'  => 'mic',
					'title' => 'תמלול וסיכום שיחות',
					'text'  => 'כל שיחה נשמרת מתומללת ומסוכמת על הכרטיס.',
				),
				array(
					'icon'  => 'offer',
					'title' => 'מייל מהדומיין שלכם',
					'text'  => 'הצעות והסכמים יוצאים מכתובת המשרד.',
				),
				array(
					'icon'  => 'inbox',
					'title' => 'תיבה נכנסת',
					'text'  => 'תשובות הלקוחות חוזרות לכרטיס, בלי לגעת בדואר הקיים.',
				),
				array(
					'icon'  => 'page',
					'title' => 'טופס „מה אתם מחפשים?”',
					'text'  => 'הלקוח ממלא בעצמו — דרישות, או פרטי הנכס שלו.',
				),
			),
		),
		array(
			'title' => 'סוכנים ובינה מלאכותית',
			'items' => array(
				array(
					'icon'  => 'whatsapp',
					'title' => 'הסוכן בווטסאפ',
					'text'  => 'שואלים ומבצעים מהצ\'אט, בלי להיכנס למערכת.',
				),
				array(
					'icon'  => 'mic',
					'title' => 'הסוכן הקולי',
					'text'  => 'מדברים במקום להקליד — נכס, פגישה או מייל בפקודה.',
				),
				array(
					'icon'  => 'card',
					'title' => 'כרטיס אוטומטי',
					'text'  => 'הדרישות מהשיחה הופכות לכרטיס קונה.',
				),
				array(
					'icon'  => 'search',
					'title' => 'חיפוש במשפט חופשי',
					'text'  => 'כותבים מה מחפשים, והמערכת מבינה ומוצאת.',
				),
				array(
					'icon'  => 'mentor',
					'title' => 'המנטור האישי',
					'text'  => 'יעד שנפרס לשבוע, וציון על ביצוע — לא על מזל.',
				),
				array(
					'icon'  => 'sparkle',
					'title' => 'אישור לפני כל שליחה',
					'text'  => 'כל מה שיוצא ללקוח עובר דרככם קודם.',
				),
			),
		),
		array(
			'title' => 'שיווק, הצעות ובעל הנכס',
			'items' => array(
				array(
					'icon'  => 'page',
					'title' => 'דף נחיתה לנכס',
					'text'  => 'נוצר מהכרטיס, עם המיתוג שלכם.',
				),
				array(
					'icon'  => 'offer',
					'title' => 'הצעה ממותגת',
					'text'  => 'דף מעוצב במקום צילומי מסך בוואטסאפ.',
				),
				array(
					'icon'  => 'bell',
					'title' => 'הצעות שנשלחות מעצמן',
					'text'  => 'קונה מקבל התאמה חדשה במייל, בלי שתזכרו.',
				),
				array(
					'icon'  => 'eye',
					'title' => 'מעקב צפיות',
					'text'  => 'מי פתח את ההצעה, מתי וכמה פעמים.',
				),
				array(
					'icon'  => 'megaphone',
					'title' => 'דוח שיווק לבעל הנכס',
					'text'  => 'מה נעשה עם הנכס — נשלח למוכר בלחיצה.',
				),
				array(
					'icon'  => 'funnel',
					'title' => 'לידים לפי מקור',
					'text'  => 'רואים איזה ערוץ באמת מביא עסקאות.',
				),
			),
		),
		array(
			'title' => 'הסכמים, בלעדיות וחוק',
			'items' => array(
				array(
					'icon'  => 'signature',
					'title' => 'החתמה דיגיטלית',
					'text'  => 'הזמנת שירותי תיווך ובלעדיות בחתימה מהנייד.',
				),
				array(
					'icon'  => 'log',
					'title' => 'תבניות הסכמים',
					'text'  => 'עורכים פעם אחת, וכל הסכם יוצא מוכן.',
				),
				array(
					'icon'  => 'timer',
					'title' => 'בלעדיות ומועד השליש',
					'text'  => 'המערכת שומרת על המועד לפי חוק המתווכים.',
				),
				array(
					'icon'  => 'activity',
					'title' => 'פעולות שיווק נרשמות',
					'text'  => 'הצעה, סיור והצעה למשרדים — נספרות לבד.',
				),
			),
		),
		array(
			'title' => 'רשת שיתופי הפעולה',
			'items' => array(
				array(
					'icon'  => 'partial',
					'title' => 'שיתוף ביקושים ונכסים',
					'text'  => 'נחשף רק מה שאנונימי — אזור, תקציב ודרישות.',
				),
				array(
					'icon'  => 'handshake',
					'title' => 'עמלה מוסכמת מראש',
					'text'  => 'בכתב, לפני שנחשף פרט מזהה.',
				),
				array(
					'icon'  => 'coins',
					'title' => 'הפניית לקוח בקרדיטים',
					'text'  => 'פנייה שלא מתאימה לכם עוברת הלאה — תמורת עמלה.',
				),
				array(
					'icon'  => 'target',
					'title' => 'מוניטין בין משרדים',
					'text'  => 'מצהירים לפני, מאשרים אחרי, והפער בונה דירוג.',
				),
				array(
					'icon'  => 'workspace',
					'title' => 'חלל עבודה לעסקה',
					'text'  => 'שני הצדדים על אותה עסקה, באותו מקום.',
				),
				array(
					'icon'  => 'log',
					'title' => 'יומן חשיפות',
					'text'  => 'מה נחשף, למי ומתי.',
				),
				array(
					'icon'  => 'forum',
					'title' => 'פורום אנונימי',
					'text'  => 'מתייעצים בלי שם ובלי משרד.',
				),
			),
		),
		array(
			'title' => 'ניהול המשרד',
			'items' => array(
				array(
					'icon'  => 'usercog',
					'title' => 'סוכנים והרשאות',
					'text'  => 'מי רואה מה, ברמת הסוכן.',
				),
				array(
					'icon'  => 'split',
					'title' => 'חלוקת לידים',
					'text'  => 'כל פנייה לסוכן הנכון, עם מעקב.',
				),
				array(
					'icon'  => 'tasks',
					'title' => 'לוח משימות',
					'text'  => 'המשימות שלכם ושל המשרד, במקום אחד.',
				),
				array(
					'icon'  => 'activity',
					'title' => 'אוטומציות',
					'text'  => 'ליד שלא נענה או סיור שהיה — המערכת פותחת משימה.',
				),
				array(
					'icon'  => 'calendar',
					'title' => 'יומן וסנכרון Google',
					'text'  => 'סיורים, תזכורות לשני הצדדים ותיעוד תוצאה.',
				),
				array(
					'icon'  => 'bell',
					'title' => 'התראות חכמות',
					'text'  => 'פעמון, דפדפן וּוואטסאפ — ואפשר להשתיק בלי לפספס.',
				),
				array(
					'icon'  => 'chart',
					'title' => 'דוחות המשרד',
					'text'  => 'מצב, תוצאות וביצועי סוכנים — במספרים.',
				),
				array(
					'icon'  => 'coins',
					'title' => 'מעקב עמלות',
					'text'  => 'מה נסגר, מה נכנס ומה מתחלק.',
				),
			),
		),
		array(
			'title' => 'אבטחה, פרטיות ונגישות',
			'items' => array(
				array(
					'icon'  => 'lock',
					'title' => 'הצפנה וגיבוי',
					'text'  => 'פרטי הלקוחות נשמרים מוצפנים, עם גיבוי אוטומטי.',
				),
				array(
					'icon'  => 'shield',
					'title' => 'הפרדה בין משרדים',
					'text'  => 'נאכפת במסד הנתונים עצמו, לא רק בקוד.',
				),
				array(
					'icon'  => 'history',
					'title' => 'יומן פעילות',
					'text'  => 'כל צפייה ושינוי נרשמים.',
				),
				array(
					'icon'  => 'scale',
					'title' => 'תשתית לחוק הפרטיות',
					'text'  => 'הרשאות, זכות עיון ומחיקה ותיעוד גישה.',
				),
				array(
					'icon'  => 'download',
					'title' => 'ייצוא ומחיקה',
					'text'  => 'כל נתוני המשרד בלחיצה — והחשבון נמחק כשמבקשים.',
				),
				array(
					'icon'  => 'access',
					'title' => 'נגישות מובנית',
					'text'  => 'התאמות תצוגה, מקלדת וקורא מסך — גם בדפים ללקוח.',
				),
			),
		),
	);
}

/**
 * סך כל היכולות ברשימה.
 *
 * @return int
 */
function mv_feature_count() {
	$count = 0;
	foreach ( mv_feature_groups() as $group ) {
		$count += count( $group['items'] );
	}
	return $count;
}

/**
 * קישור לאזור היכולות בעמוד הבית.
 *
 * @param string $anchor העוגן.
 * @return string
 */
function mv_home_anchor( $anchor ) {
	return ( is_front_page() ? '' : esc_url( home_url( '/' ) ) ) . '#' . $anchor;
}

/**
 * התפריט הנפתח "פיצ'רים במערכת" בכותרת העליונה.
 */
function mv_mega_menu() {
	?>
	<div class="mv-mega-wrap">
		<button type="button" class="mv-mega-btn" aria-expanded="false" aria-controls="mv-mega">
			פיצ'רים במערכת
			<svg class="mv-mega-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="m6 9.5 6 6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>

		<div class="mv-mega" id="mv-mega" hidden>
			<div class="mv-mega-in">
				<div class="mv-mega-head">
					<p class="mv-mega-title"><?php echo esc_html( mv_feature_count() ); ?> יכולות במערכת אחת<span class="mv-dot" aria-hidden="true">.</span></p>
					<a class="mv-mega-all" href="<?php echo esc_url( mv_home_anchor( 'capabilities' ) ); ?>">לכל היכולות</a>
				</div>

				<div class="mv-mega-groups">
					<?php foreach ( mv_feature_groups() as $group ) : ?>
						<div class="mv-mega-group">
							<p class="mv-mega-group-title"><?php echo esc_html( $group['title'] ); ?></p>
							<ul class="mv-mega-list">
								<?php foreach ( $group['items'] as $item ) : ?>
									<li>
										<a class="mv-mega-item" href="<?php echo esc_url( mv_home_anchor( 'capabilities' ) ); ?>">
											<span class="mv-mega-ico"><?php mv_icon( $item['icon'], 18 ); ?></span>
											<span class="mv-mega-name"><?php echo esc_html( $item['title'] ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
