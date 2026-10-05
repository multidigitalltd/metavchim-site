<?php
/**
 * Standard page template (legal pages, accessibility statement, etc.).
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="mv-content-page">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article <?php post_class( 'mv-article' ); ?>>
			<header class="mv-article-head">
				<h1 class="mv-article-title"><?php the_title(); ?><span class="mv-dot" aria-hidden="true">.</span></h1>
			</header>
			<div class="mv-article-body">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	}
	?>
</main>
<?php
get_footer();
