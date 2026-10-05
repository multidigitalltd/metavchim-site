<?php
/**
 * חלון ההרשמה לעדכונים.
 *
 * נפתח אחרי שהייה קצרה באתר או כשנראה שהמבקר עוזב, פעם אחת לדפדפן.
 * ההרשמה מחייבת אישור מפורש של מדיניות הפרטיות ותנאי השימוש, והאישור
 * נשמר עם חותמת זמן על הפנייה — כדי שיהיה תיעוד להסכמה לדיוור.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * האם להציג את החלון בעמוד הנוכחי.
 *
 * @return bool
 */
function mv_newsletter_needed() {
	// דף הנחיתה של האירוע עומד בפני עצמו ויש בו חלון משלו.
	$needed = ! is_page( 'marathon' ) && ! is_page( array( 'privacy', 'terms', 'accessibility' ) );

	/**
	 * כיבוי או הפעלה של חלון ההרשמה לעדכונים.
	 *
	 * @param bool $needed האם להציג.
	 */
	return (bool) apply_filters( 'mv_newsletter_needed', $needed );
}

/**
 * כתובת עמוד תנאי השימוש.
 *
 * @return string
 */
function mv_terms_url() {
	$page = get_page_by_path( 'terms' );
	return $page ? (string) get_permalink( $page ) : home_url( '/terms/' );
}

/**
 * החלון עצמו.
 */
function mv_render_newsletter_popup() {
	if ( ! mv_newsletter_needed() ) {
		return;
	}
	?>
	<div class="mv-modal mv-news" id="mv-news" role="dialog" aria-modal="true" aria-labelledby="mv-news-title" hidden>
		<div class="mv-modal-backdrop" data-mv-news-close></div>
		<div class="mv-modal-card" role="document">
			<button type="button" class="mv-modal-x" data-mv-news-close aria-label="סגירת החלון">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
			</button>

			<span class="mv-news-ico" aria-hidden="true"><?php mv_icon( 'sparkle', 26 ); ?></span>
			<h2 class="mv-modal-title" id="mv-news-title">רוצים לדעת מה חדש במערכת<span class="mv-dot" aria-hidden="true">?</span></h2>
			<p class="mv-modal-sub">אנחנו משחררים יכולות חדשות כל הזמן — סוכנים, אוטומציות וכלים לשיתופי פעולה. משאירים מייל ומקבלים עדכון קצר בכל פעם שמשהו חדש עולה לאוויר.</p>

			<form class="mv-form mv-news-form" method="post" novalidate
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
				data-mv-endpoint="<?php echo esc_url( rest_url( 'metavchim/v1/lead' ) ); ?>">
				<input type="hidden" name="action" value="mv_demo_lead">
				<input type="hidden" name="mv_form" value="news">
				<input type="hidden" name="mv_source" value="">

				<p class="mv-hp" aria-hidden="true">
					<label for="mv-news-website">אל תמלאו שדה זה</label>
					<input type="text" id="mv-news-website" name="mv_website" tabindex="-1" autocomplete="off">
				</p>

				<p class="mv-field">
					<label for="mv-news-name">שם <span aria-hidden="true">*</span></label>
					<input type="text" id="mv-news-name" name="mv_name" required autocomplete="name" maxlength="120">
				</p>
				<p class="mv-field">
					<label for="mv-news-email">דוא"ל <span aria-hidden="true">*</span></label>
					<input type="email" id="mv-news-email" name="mv_email" required autocomplete="email" maxlength="120" dir="ltr">
				</p>
				<p class="mv-field">
					<label for="mv-news-phone">טלפון <span class="mv-field-opt">(לא חובה)</span></label>
					<input type="tel" id="mv-news-phone" name="mv_phone" autocomplete="tel" inputmode="tel" maxlength="30" dir="ltr">
				</p>

				<p class="mv-consent-line">
					<input type="checkbox" id="mv-news-ok" name="mv_consent" value="1" required>
					<label for="mv-news-ok">
						קראתי ואני מאשר/ת את <a href="<?php echo esc_url( mv_privacy_url() ); ?>" target="_blank" rel="noopener">מדיניות הפרטיות</a>
						ואת <a href="<?php echo esc_url( mv_terms_url() ); ?>" target="_blank" rel="noopener">תנאי השימוש</a>,
						וקבלת עדכונים ותכנים שיווקיים על מערכת מתווכים. אפשר להסיר את ההרשמה בכל רגע.
					</label>
				</p>
				<span class="mv-form-error" id="mv-news-err" role="alert"></span>

				<?php mv_turnstile_widget(); ?>

				<button type="submit" class="mv-form-submit">רשמו אותי לעדכונים</button>
			</form>

			<div class="mv-news-done" hidden>
				<span class="mv-news-ico" aria-hidden="true"><?php mv_icon( 'check', 26 ); ?></span>
				<p class="mv-modal-title">נרשמתם<span class="mv-dot" aria-hidden="true">.</span></p>
				<p class="mv-modal-sub">כל יכולת חדשה תגיע אליכם למייל. אפשר להסיר את ההרשמה בכל הודעה.</p>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'mv_render_newsletter_popup', 8 );
