<?php
/**
 * Full-bleed template for the "רשת המשרדים" page.
 *
 * The page content is the installed section markup, so it is rendered
 * without the article wrapper and title used by the standard page template.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="mv-collab mv-reveal">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>
<?php
get_footer();
