<?php
/**
 * ערכת אייקונים אחידה לתבנית.
 *
 * קו אחד, פינות מעוגלות ומשקל 1.8 — אותה שפה עיצובית של האייקונים
 * שבסקשני העיצוב המקוריים. הצבע נשלט ב-CSS דרך currentColor, כך שאותו
 * אייקון עובד על רקע בהיר וכהה.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * גוף האייקון (ללא תגית ה-svg), לפי שם.
 *
 * @param string $name שם האייקון.
 * @return string
 */
function mv_icon_path( $name ) {
	$icons = array(
		'building'  => '<path d="M4 20V7.5L12 4l8 3.5V20"/><path d="M9.5 20v-4.5a2.5 2.5 0 0 1 5 0V20"/>',
		'users'     => '<circle cx="9" cy="8.5" r="3"/><path d="M3.5 19c0-2.9 2.5-4.8 5.5-4.8s5.5 1.9 5.5 4.8"/><path d="M16.5 6.6a3 3 0 0 1 0 5.8M18 19c0-2.2-.9-3.9-2.4-4.5"/>',
		'match'     => '<path d="M4 7h5l3 5 3-5h5"/><path d="M4 17h5l3-5"/><path d="m17.5 4.5 2.5 2.5-2.5 2.5M17.5 14.5 20 17l-2.5 2.5"/><path d="M15 17h5"/>',
		'upload'    => '<path d="M12 16V4"/><path d="m7.5 8.5 4.5-4.5 4.5 4.5"/><path d="M4 15v3.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V15"/>',
		'history'   => '<path d="M4 7h16M4 12h16M4 17h9"/><circle cx="18" cy="17" r="2.4"/>',
		'bell'      => '<path d="M18 9a6 6 0 1 0-12 0c0 4.2-1.5 5.5-1.5 5.5h15S18 13.2 18 9Z"/><path d="M10.3 18a2 2 0 0 0 3.4 0"/>',
		'calendar'  => '<rect x="4" y="5.5" width="16" height="14.5" rx="2.5"/><path d="M4 10h16M8.5 3.5v4M15.5 3.5v4"/>',
		'mic'       => '<rect x="9.2" y="3" width="5.6" height="10.5" rx="2.8"/><path d="M5.5 11a6.5 6.5 0 0 0 13 0M12 17.5V21"/>',
		'card'      => '<rect x="3.5" y="5" width="17" height="14" rx="2.5"/><path d="M7.5 10h5M7.5 14h8"/>',
		'sparkle'   => '<path d="M12 3.5 13.8 9 19 10.8 13.8 12.6 12 18l-1.8-5.4L5 10.8 10.2 9 12 3.5Z"/><path d="M18.5 16.5 19.2 18.5 21 19.2 19.2 19.9 18.5 22 17.8 19.9 16 19.2 17.8 18.5 18.5 16.5Z"/>',
		'page'      => '<path d="M6 3.5h7.5L19 9v11.5H6z"/><path d="M13.5 3.5V9H19"/><path d="M9 13h6M9 16.5h4"/>',
		'offer'     => '<rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><path d="M7.5 9h9M7.5 12.5h6"/><path d="m14.5 16.5 2 2 3.5-3.5"/>',
		'eye'       => '<path d="M2.8 12S6.4 6 12 6s9.2 6 9.2 6-3.6 6-9.2 6-9.2-6-9.2-6Z"/><circle cx="12" cy="12" r="2.6"/>',
		'funnel'    => '<path d="M4 5h16l-6 7v6l-4 2v-8L4 5Z"/>',
		'partial'   => '<path d="M2.8 12S6.4 6 12 6c1.6 0 3 .5 4.2 1.2"/><path d="M21.2 12s-2.2 3.7-6 5.3"/><path d="M4 4l16 16"/>',
		'handshake' => '<path d="M8 12.5 5.5 10 9 6.5h6L18.5 10 16 12.5"/><path d="m9.5 14 2 2 2-2 2 2 2-2"/><path d="M3.5 10h2M18.5 10h2"/>',
		'log'       => '<rect x="4" y="3.5" width="16" height="17" rx="2.5"/><path d="M8 8.5h8M8 12h8M8 15.5h5"/>',
		'workspace' => '<rect x="3.5" y="5" width="17" height="12" rx="2.5"/><path d="M8 20h8M12 17v3"/><path d="M8 10.5h8"/>',
		'timer'     => '<circle cx="12" cy="13" r="7.5"/><path d="M12 9v4l2.5 2M9.5 2.5h5"/>',
		'chart'     => '<path d="M4 19V13M9.3 19V8M14.7 19v-8M20 19V5"/>',
		'usercog'   => '<circle cx="9.5" cy="8" r="3.2"/><path d="M3.5 19c0-3 2.7-5 6-5"/><circle cx="17" cy="16.5" r="2.6"/><path d="M17 12.6v1.3M17 19.1v1.3M13.7 14.6l1.1.7M19.2 17.7l1.1.7M20.3 14.6l-1.1.7M14.8 17.7l-1.1.7"/>',
		'split'     => '<path d="M12 4v6"/><path d="M12 10 6.5 14v6M12 10l5.5 4v6"/><circle cx="12" cy="3.5" r="1.6"/>',
		'target'    => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3.6"/><circle cx="12" cy="12" r="0.9" fill="currentColor" stroke="none"/>',
		'coins'     => '<ellipse cx="12" cy="6.5" rx="7" ry="3"/><path d="M5 6.5v5c0 1.7 3.1 3 7 3s7-1.3 7-3v-5"/><path d="M5 11.5v5c0 1.7 3.1 3 7 3s7-1.3 7-3v-5"/>',
		'lock'      => '<rect x="4.5" y="10" width="15" height="10" rx="2.5"/><path d="M8.5 10V7.5a3.5 3.5 0 0 1 7 0V10"/>',
		'activity'  => '<path d="M3.5 12.5h4L10 7l4 10 2.5-4.5h4"/>',
		'scale'     => '<path d="M12 4v16M7 20h10"/><path d="M4 9h16M6 9l-2.5 5h5L6 9ZM18 9l-2.5 5h5L18 9Z"/>',
		'download'  => '<path d="M12 4v11"/><path d="m7.5 10.5 4.5 4.5 4.5-4.5"/><path d="M4 19h16"/>',
		'whatsapp'  => '<path d="M4 20.2l1.3-4A8 8 0 1 1 8 18.8l-4 1.4Z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5"/>',
		'mentor'    => '<circle cx="12" cy="8" r="3.3"/><path d="M5.5 20c0-3.4 2.9-5.6 6.5-5.6s6.5 2.2 6.5 5.6"/><path d="m18.5 2.5.9 2 2 .9-2 .9-.9 2-.9-2-2-.9 2-.9.9-2Z"/>',
		'forum'     => '<path d="M4 5.5h11a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H8l-4 3v-10a2 2 0 0 1 2-2Z" stroke-linejoin="round"/><path d="M17.5 9H19a2 2 0 0 1 2 2v8l-3-2.4"/>',
		'phone'     => '<path d="M6.5 3.5h3l1.5 4-2 1.5a11 11 0 0 0 6 6l1.5-2 4 1.5v3a2 2 0 0 1-2.2 2C11.4 19 5 12.6 4.5 5.7A2 2 0 0 1 6.5 3.5Z"/>',
		'check'     => '<path d="M6 12.5 10 16.5 18 8"/>',
		'facebook'  => '<rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><path d="M15 8.2h-1.6c-.9 0-1.4.5-1.4 1.4V20"/><path d="M9.6 12.4h4.6"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="3.8"/><path d="M16.9 7.2h.01"/>',
		'youtube'   => '<rect x="2.8" y="6" width="18.4" height="12" rx="3.6"/><path d="m10.4 9.6 4.6 2.4-4.6 2.4V9.6Z" stroke-linejoin="round"/>',
		'linkedin'  => '<rect x="3.5" y="3.5" width="17" height="17" rx="4"/><path d="M8 10.6V16"/><path d="M8 7.6h.01"/><path d="M11.8 16v-3.1a1.9 1.9 0 0 1 3.8 0V16"/><path d="M11.8 10.6V16"/>',
		'tiktok'    => '<path d="M14 3.6v10.2a3.5 3.5 0 1 1-3.1-3.5"/><path d="M14 3.6c.4 2.3 2 3.7 4.3 3.9"/>',
		'link'      => '<path d="M10.5 13.5a3.5 3.5 0 0 0 5 0l2.5-2.5a3.5 3.5 0 0 0-5-5l-1 1"/><path d="M13.5 10.5a3.5 3.5 0 0 0-5 0L6 13a3.5 3.5 0 0 0 5 5l1-1"/>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['check'];
}

/**
 * הדפסת אייקון.
 *
 * @param string $name  שם האייקון.
 * @param int    $size  גודל בפיקסלים.
 */
function mv_icon( $name, $size = 22 ) {
	printf(
		'<svg class="mv-ico" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true" focusable="false">%2$s</svg>',
		absint( $size ),
		mv_icon_path( $name ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- מרקאפ קבוע של התבנית.
	);
}
