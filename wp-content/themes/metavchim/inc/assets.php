<?php
/**
 * Conditional, lean asset loading.
 *
 * עקרונות: אין jQuery, אין ספריות צד ג', CSS/JS נטענים רק בעמודים
 * שצריכים אותם, JS נטען עם defer, ופונטים קריטיים נטענים ב-preload.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current request renders the "אודות" page.
 *
 * @return bool
 */
function mv_is_event_page() {
	return is_page( 'marathon' );
}

/**
 * Whether the current request renders the "אודות" page.
 *
 * @return bool
 */
function mv_is_about_page() {
	return is_page( 'about' );
}

/**
 * Whether the current request renders the collaboration page.
 *
 * @return bool
 */
function mv_is_collab_page() {
	return is_page( 'collaboration' );
}

/**
 * Enqueue front-end styles and scripts.
 */
function mv_enqueue_assets() {
	// Base styles — header, footer, typography, accessibility toolbar.
	wp_enqueue_style(
		'mv-main',
		MV_THEME_URI . '/assets/css/main.css',
		array(),
		MV_THEME_VERSION
	);

	// Homepage sections — loaded only where they are rendered.
	if ( is_front_page() ) {
		wp_enqueue_style(
			'mv-home',
			MV_THEME_URI . '/assets/css/home.css',
			array( 'mv-main' ),
			MV_THEME_VERSION
		);
	}

	// Event landing page — a self-contained design, loaded only there.
	if ( mv_is_event_page() ) {
		wp_enqueue_style(
			'mv-marathon',
			MV_THEME_URI . '/assets/css/marathon.css',
			array( 'mv-main' ),
			MV_THEME_VERSION
		);
	}

	// About page sections — loaded only on that page.
	if ( mv_is_about_page() ) {
		wp_enqueue_style(
			'mv-about',
			MV_THEME_URI . '/assets/css/about.css',
			array( 'mv-main' ),
			MV_THEME_VERSION
		);
	}

	// Collaboration page sections — loaded only on that page.
	if ( mv_is_collab_page() ) {
		wp_enqueue_style(
			'mv-collab',
			MV_THEME_URI . '/assets/css/collab.css',
			array( 'mv-main' ),
			MV_THEME_VERSION
		);
	}

	// Site-wide behavior: nav toggle, scroll reveal, accessibility toolbar.
	wp_enqueue_script(
		'mv-main',
		MV_THEME_URI . '/assets/js/main.js',
		array(),
		MV_THEME_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	// Event landing page behaviour.
	if ( mv_is_event_page() ) {
		wp_enqueue_script(
			'mv-marathon',
			MV_THEME_URI . '/assets/js/marathon.js',
			array(),
			MV_THEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	// Demo tabs exist only on the homepage.
	if ( is_front_page() ) {
		wp_enqueue_script(
			'mv-home',
			MV_THEME_URI . '/assets/js/home.js',
			array(),
			MV_THEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'mv_enqueue_assets' );

/**
 * The homepage is hand-tuned HTML sections styled entirely by the theme
 * CSS, so core block styles are dead weight there. Other pages keep the
 * core stylesheets so user-added blocks (columns, gallery…) render fine.
 */
function mv_dequeue_core_styles() {
	if ( ! is_front_page() && ! mv_is_collab_page() && ! mv_is_about_page() && ! mv_is_event_page() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'core-block-supports' );
}
add_action( 'wp_enqueue_scripts', 'mv_dequeue_core_styles', 20 );

/**
 * Preload the two font weights used above the fold (body 400, headings 900).
 */
function mv_preload_fonts() {
	$fonts = array( 'almoni-regular-aaa.woff', 'almoni-ultrabold-aaa.woff' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff" crossorigin>' . "\n",
			esc_url( MV_THEME_URI . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'mv_preload_fonts', 2 );

/**
 * Favicon fallback when no Site Icon is set in the Customizer.
 */
function mv_favicon_fallback() {
	if ( ! has_site_icon() ) {
		printf(
			'<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
			esc_url( MV_THEME_URI . '/assets/img/favicon.svg' )
		);
	}
}
add_action( 'wp_head', 'mv_favicon_fallback', 2 );
