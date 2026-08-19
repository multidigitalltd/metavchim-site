<?php
/**
 * Site header: skip link + sticky frosted nav pill.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="mv-page" dir="rtl">

	<a class="skip-link screen-reader-text" href="#main">דילוג לתוכן המרכזי</a>

	<header class="mv-topbar">
		<div class="mv-nav-pill">
			<a class="mv-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="מתווכים — עמוד הבית">
				<?php mv_logo_svg( 25 ); ?>
				<span class="mv-brand-name">מתווכים<span class="mv-dot" aria-hidden="true">.</span></span>
			</a>

			<nav class="mv-nav" id="mv-nav" aria-label="ניווט ראשי">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mv-nav-list',
						'fallback_cb'    => 'mv_primary_menu_fallback',
						'depth'          => 1,
					)
				);
				?>
			</nav>

			<div class="mv-nav-actions">
				<a class="mv-login" href="<?php echo esc_url( mv_login_url_app() ); ?>">התחברות</a>
				<a class="mv-btn-dark" href="#demo">תיאום הדגמה חינם</a>
			</div>

			<button type="button" class="mv-nav-toggle" aria-expanded="false" aria-controls="mv-nav">
				<span class="screen-reader-text">פתיחת תפריט</span>
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
		</div>
	</header>
