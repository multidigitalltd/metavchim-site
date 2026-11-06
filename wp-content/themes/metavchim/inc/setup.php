<?php
/**
 * Theme setup: supports, menus, and core cleanup for performance.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation menus.
 */
function mv_theme_setup() {
	load_theme_textdomain( 'metavchim', MV_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'תפריט ראשי', 'metavchim' ),
			'footer'  => __( 'תפריט פוטר', 'metavchim' ),
		)
	);
}
add_action( 'after_setup_theme', 'mv_theme_setup' );

/**
 * Register the theme's block pattern category.
 */
function mv_register_pattern_category() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'metavchim',
			array( 'label' => __( 'מתווכים — סקשנים', 'metavchim' ) )
		);
	}
}
add_action( 'init', 'mv_register_pattern_category' );

/**
 * Core cleanup: remove emoji scripts, generator tag and other head bloat
 * that adds HTTP requests / render-blocking resources on every page.
 */
function mv_head_cleanup() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'mv_head_cleanup' );

/**
 * Disable XML-RPC (hardening; the site does not use it).
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Native lazy-loading for images and iframes (Core Web Vitals).
 */
add_filter( 'wp_lazy_loading_enabled', '__return_true' );
