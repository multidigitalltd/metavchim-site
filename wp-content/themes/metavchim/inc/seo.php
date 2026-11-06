<?php
/**
 * Lightweight SEO output — only when no dedicated SEO plugin is active,
 * so the theme stays compatible with Yoast / RankMath / SEOPress / AIOSEO.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a known SEO plugin already handles meta output.
 *
 * @return bool
 */
function mv_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' );
}

/**
 * Basic Open Graph + Twitter card tags.
 */
function mv_og_tags() {
	if ( mv_seo_plugin_active() || is_admin() ) {
		return;
	}

	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description', 'display' );
	$url   = home_url( add_query_arg( array(), '' ) );

	if ( is_singular() ) {
		$post = get_queried_object();
		$url  = get_permalink( $post );
		if ( has_excerpt( $post ) ) {
			$desc = get_the_excerpt( $post );
		}
	}

	printf( '<meta property="og:type" content="%s">' . "\n", is_front_page() ? 'website' : 'article' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name', 'display' ) ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:locale" content="he_IL">' . "\n" );
	if ( $desc ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $desc ) ) );
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $desc ) ) );
	}
	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
}
add_action( 'wp_head', 'mv_og_tags', 5 );

/**
 * Organization + WebSite structured data on the front page only.
 */
function mv_schema() {
	if ( mv_seo_plugin_active() || ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type' => 'Organization',
				'@id'   => home_url( '/#organization' ),
				'name'  => get_bloginfo( 'name', 'display' ),
				'url'   => home_url( '/' ),
				'logo'  => MV_THEME_URI . '/assets/img/logo-mark.svg',
			),
			array(
				'@type'      => 'WebSite',
				'@id'        => home_url( '/#website' ),
				'name'       => get_bloginfo( 'name', 'display' ),
				'url'        => home_url( '/' ),
				'inLanguage' => 'he-IL',
				'publisher'  => array( '@id' => home_url( '/#organization' ) ),
			),
		),
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'mv_schema', 6 );
