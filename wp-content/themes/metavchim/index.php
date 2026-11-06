<?php
/**
 * Generic fallback template.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="mv-content-page">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class( 'mv-article' ); ?>>
				<header class="mv-article-head">
					<?php if ( is_singular() ) : ?>
						<h1 class="mv-article-title"><?php the_title(); ?><span class="mv-dot" aria-hidden="true">.</span></h1>
					<?php else : ?>
						<h2 class="mv-article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>
				</header>
				<div class="mv-article-body">
					<?php is_singular() ? the_content() : the_excerpt(); ?>
				</div>
			</article>
			<?php
		}
		the_posts_pagination();
		?>
	<?php else : ?>
		<article class="mv-article">
			<header class="mv-article-head">
				<h1 class="mv-article-title">לא נמצא תוכן<span class="mv-dot" aria-hidden="true">.</span></h1>
			</header>
			<div class="mv-article-body">
				<p>העמוד שחיפשת לא נמצא. אפשר לחזור <a href="<?php echo esc_url( home_url( '/' ) ); ?>">לעמוד הבית</a>.</p>
			</div>
		</article>
	<?php endif; ?>
</main>
<?php
get_footer();
