<?php
/**
 * מפת היכולות של המערכת — מקור אחד לאמת.
 *
 * אותה רשימה מזינה גם את האזור "כל מה שהמערכת עושה בשבילך" בעמוד הבית
 * וגם את התפריט הנפתח "פיצ'רים במערכת" בכותרת העליונה, כדי שלא תיווצר
 * כפילות בין השניים.
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
					'text'  => 'כרטיס אחד לכל נכס — פרטים, תמונות ומסמכים.',
				),
				array(
					'icon'  => 'users',
					'title' => 'מאגר קונים',
					'text'  => 'מה כל קונה מחפש, מעודכן אחרי כל שיחה.',
				),
				array(
					'icon'  => 'match',
					'title' => 'מנוע התאמות',
					'text'  => 'המערכת מצליבה נכסים וקונים ברקע.',
				),
				array(
					'icon'  => 'upload',
					'title' => 'ייבוא מאקסל',
					'text'  => 'המאגר הקיים נכנס כמו שהוא.',
				),
				array(
					'icon'  => 'history',
					'title' => 'היסטוריית לקוח',
					'text'  => 'כל שיחה, הצעה וסיור על אותו כרטיס.',
				),
				array(
					'icon'  => 'bell',
					'title' => 'פולואפ אוטומטי',
					'text'  => 'ליד ששכחת עולה לראש הרשימה.',
				),
				array(
					'icon'  => 'calendar',
					'title' => 'יומן סיורים',
					'text'  => 'כל הפגישות של המשרד במקום אחד.',
				),
			),
		),
		array(
			'title' => 'שיחות ובינה מלאכותית',
			'items' => array(
				array(
					'icon'  => 'mic',
					'title' => 'תמלול שיחות',
					'text'  => 'כל שיחה נשמרת מתומללת על הכרטיס.',
				),
				array(
					'icon'  => 'card',
					'title' => 'כרטיס אוטומטי',
					'text'  => 'הדרישות מהשיחה הופכות לכרטיס קונה.',
				),
				array(
					'icon'  => 'sparkle',
					'title' => 'סיכום ותובנות',
					'text'  => 'מה נאמר ומה השלב הבא, בשתי שורות.',
				),
				array(
					'icon'  => 'whatsapp',
					'title' => 'סוכן בווטסאפ',
					'text'  => 'מנהלים את המערכת מהצ\'אט.',
				),
				array(
					'icon'  => 'mentor',
					'title' => 'מנטור AI',
					'text'  => 'יעדים אישיים, מעקב ודחיפה קדימה.',
				),
				array(
					'icon'  => 'phone',
					'title' => 'סוכן קולי',
					'text'  => 'עונה לפניות כשאתה בשטח.',
				),
			),
		),
		array(
			'title' => 'שיווק ומכירה',
			'items' => array(
				array(
					'icon'  => 'page',
					'title' => 'דף נחיתה לנכס',
					'text'  => 'נוצר מהכרטיס, עם המיתוג שלך.',
				),
				array(
					'icon'  => 'offer',
					'title' => 'הצעה ממותגת',
					'text'  => 'דף מעוצב במקום צילומי מסך.',
				),
				array(
					'icon'  => 'eye',
					'title' => 'מעקב צפיות',
					'text'  => 'מי פתח, מתי, וכמה זמן.',
				),
				array(
					'icon'  => 'funnel',
					'title' => 'לידים לפי מקור',
					'text'  => 'רואים מה באמת מביא עסקאות.',
				),
			),
		),
		array(
			'title' => 'רשת שיתופי הפעולה',
			'items' => array(
				array(
					'icon'  => 'partial',
					'title' => 'חשיפה חלקית',
					'text'  => 'השותף רואה רק מה שדרוש להתאמה.',
				),
				array(
					'icon'  => 'handshake',
					'title' => 'עמלה מוסכמת מראש',
					'text'  => 'בכתב, לפני שנחשף פרט מזהה.',
				),
				array(
					'icon'  => 'log',
					'title' => 'יומן חשיפות',
					'text'  => 'מה נחשף, למי ומתי.',
				),
				array(
					'icon'  => 'workspace',
					'title' => 'חלל עבודה לעסקה',
					'text'  => 'שני הצדדים על אותה עסקה.',
				),
				array(
					'icon'  => 'timer',
					'title' => 'תוקף וניתוק',
					'text'  => 'לכל שיתוף תפוגה, וניתוק בלחיצה.',
				),
				array(
					'icon'  => 'chart',
					'title' => 'דוח שיתופי פעולה',
					'text'  => 'כמה עסקאות וכמה עמלות מהרשת.',
				),
				array(
					'icon'  => 'forum',
					'title' => 'פורום אנונימי',
					'text'  => 'מתייעצים בלי שם ובלי משרד.',
				),
			),
		),
		array(
			'title' => 'ניהול משרד',
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
					'icon'  => 'target',
					'title' => 'יעדים ודוחות',
					'text'  => 'מוכן להצגה בישיבת צוות.',
				),
				array(
					'icon'  => 'coins',
					'title' => 'מעקב עמלות',
					'text'  => 'מה נסגר, מה נכנס ומה מתחלק.',
				),
			),
		),
		array(
			'title' => 'אבטחה ותאימות',
			'items' => array(
				array(
					'icon'  => 'lock',
					'title' => 'הצפנה וגיבוי',
					'text'  => 'בהעברה, באחסון, וגיבוי אוטומטי.',
				),
				array(
					'icon'  => 'activity',
					'title' => 'יומן פעילות',
					'text'  => 'כל צפייה ושינוי נרשמים.',
				),
				array(
					'icon'  => 'scale',
					'title' => 'תשתית לחוק הפרטיות',
					'text'  => 'הכלים לעמידה בדין הישראלי.',
				),
				array(
					'icon'  => 'download',
					'title' => 'ייצוא הנתונים',
					'text'  => 'המאגר שלך, בלחיצה אחת.',
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
