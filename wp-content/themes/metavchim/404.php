<?php
/**
 * 404 template.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="mv-content-page">
	<article class="mv-article">
		<header class="mv-article-head">
			<h1 class="mv-article-title">404 — העמוד לא נמצא<span class="mv-dot" aria-hidden="true">.</span></h1>
		</header>
		<div class="mv-article-body">
			<p>הכתובת שהגעת אליה לא קיימת. אפשר לחזור <a href="<?php echo esc_url( home_url( '/' ) ); ?>">לעמוד הבית</a>.</p>
		</div>
	</article>
</main>
<?php
get_footer();
