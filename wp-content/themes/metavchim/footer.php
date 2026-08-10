<?php
/**
 * Site footer + accessibility toolbar.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;
?>
	<footer class="mv-footer">
		<div class="mv-footer-inner">
			<div class="mv-brand mv-footer-brand">
				<?php mv_logo_svg( 20 ); ?>
				<span class="mv-brand-name mv-footer-brand-name">מתווכים<span class="mv-dot" aria-hidden="true">.</span></span>
			</div>
			<span class="mv-ltr">metavchim.co.il</span>
			<nav class="mv-footer-nav" aria-label="קישורים משפטיים">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'mv-footer-list',
						'fallback_cb'    => 'mv_footer_menu_fallback',
						'depth'          => 1,
					)
				);
				?>
			</nav>
			<span class="mv-copy">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?></span>
		</div>
	</footer>

	<?php mv_a11y_toolbar(); ?>

</div><!-- .mv-page -->
<?php wp_footer(); ?>
</body>
</html>
