<?php
/**
 * Front page: renders the homepage sections from the page content itself
 * (installed as editable block content). Falls back to the bundled section
 * patterns when no static front page has been set up yet.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="mv-home">
	<?php
	if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	} else {
		mv_render_default_sections();
	}
	?>
</main>
<?php
get_footer();
