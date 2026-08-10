<?php
/**
 * Reusable template output helpers.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline brand logo SVG (avoids an extra HTTP request in the header/footer).
 *
 * @param int    $size   Square size in px.
 * @param string $accent Accent color for the second bracket.
 * @param string $main   Main stroke color.
 */
function mv_logo_svg( $size = 25, $accent = '#3FBF63', $main = '#0E100F' ) {
	printf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 48 48" fill="none" aria-hidden="true" focusable="false"><path d="M28.5 6.5h7a6 6 0 0 1 6 6v23a6 6 0 0 1-6 6h-7" stroke="%2$s" stroke-width="5.2" stroke-linecap="round"/><path d="M19.5 6.5h-7a6 6 0 0 0-6 6v23a6 6 0 0 0 6 6h7" stroke="%3$s" stroke-width="5.2" stroke-linecap="round"/><rect x="19.4" y="19.4" width="9.2" height="9.2" rx="2.4" fill="%2$s"/></svg>',
		absint( $size ),
		esc_attr( $main ),
		esc_attr( $accent )
	);
}

/**
 * Primary menu fallback: the anchor links from the approved design.
 */
function mv_primary_menu_fallback() {
	$links = array(
		'#network'  => 'שיתופי פעולה',
		'#product'  => 'המערכת',
		'#voice'    => 'סוכן קולי',
		'#security' => 'אבטחה',
		'#start'    => 'הטמעה',
		'#plans'    => 'מסלולים',
	);
	$home  = is_front_page() ? '' : esc_url( home_url( '/' ) );
	echo '<ul class="mv-nav-list">';
	foreach ( $links as $anchor => $label ) {
		printf(
			'<li><a href="%s%s">%s</a></li>',
			$home, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
			esc_attr( $anchor ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Footer legal-menu fallback: link to the installed legal pages when present.
 */
function mv_footer_menu_fallback() {
	$pages = array(
		'terms'         => 'תנאי שימוש',
		'privacy'       => 'פרטיות',
		'accessibility' => 'הצהרת נגישות',
	);
	echo '<ul class="mv-footer-list">';
	foreach ( $pages as $slug => $label ) {
		$page = get_page_by_path( $slug );
		$url  = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Accessibility toolbar markup (ת"י 5568 / WCAG 2.2 AA).
 * Preferences persist in localStorage; behavior lives in assets/js/main.js.
 */
function mv_a11y_toolbar() {
	$actions = array(
		'fontplus'   => 'הגדלת טקסט',
		'fontminus'  => 'הקטנת טקסט',
		'contrast'   => 'ניגודיות גבוהה',
		'invert'     => 'היפוך צבעים',
		'grayscale'  => 'גווני אפור',
		'underline'  => 'הדגשת קישורים בקו',
		'readable'   => 'פונט קריא',
		'noanim'     => 'עצירת אנימציות',
		'headings'   => 'הבלטת כותרות',
		'links'      => 'הבלטת קישורים',
		'guide'      => 'סרגל קריאה',
	);

	$statement = get_page_by_path( 'accessibility' );
	?>
	<div class="mv-a11y" id="mv-a11y">
		<button type="button" class="mv-a11y-btn" id="mv-a11y-open" aria-haspopup="dialog" aria-expanded="false" aria-controls="mv-a11y-panel">
			<span class="screen-reader-text">פתיחת תפריט נגישות</span>
			<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><circle cx="12" cy="4.5" r="2" fill="currentColor"/><path d="M4.5 8.2c2.4.6 4.9.9 7.5.9s5.1-.3 7.5-.9M12 9.1v4.2m0 0-2.6 6.2M12 13.3l2.6 6.2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>
		<div class="mv-a11y-panel" id="mv-a11y-panel" role="dialog" aria-modal="false" aria-label="תפריט נגישות" hidden>
			<div class="mv-a11y-head">
				<span>נגישות</span>
				<button type="button" class="mv-a11y-close" id="mv-a11y-close"><span class="screen-reader-text">סגירת תפריט נגישות</span><span aria-hidden="true">&#215;</span></button>
			</div>
			<div class="mv-a11y-grid">
				<?php foreach ( $actions as $key => $label ) : ?>
					<button type="button" class="mv-a11y-act" data-a11y="<?php echo esc_attr( $key ); ?>" aria-pressed="false"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="mv-a11y-reset" data-a11y="reset">איפוס הגדרות נגישות</button>
			<?php if ( $statement ) : ?>
				<a class="mv-a11y-statement" href="<?php echo esc_url( get_permalink( $statement ) ); ?>">הצהרת נגישות</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="mv-reading-guide" id="mv-reading-guide" aria-hidden="true"></div>
	<?php
}
