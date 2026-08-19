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
			<nav class="mv-footer-nav" aria-label="קישורים בפוטר">
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
			<a class="mv-footer-docs" href="<?php echo esc_url( mv_app_url( 'docs' ) ); ?>">תיעוד ומדריכים</a>
			<span class="mv-copy">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?></span>
			<span class="mv-credit">פיתוח: <strong>Multi Digital</strong></span>
		</div>
	</footer>

	<a class="mv-sticky-cta" href="<?php echo esc_url( mv_signup_url() ); ?>">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M7 12.5 10.5 16 17 8.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
		הצטרפות חינם
	</a>

	<?php mv_a11y_toolbar(); ?>

</div><!-- .mv-page -->
<?php wp_footer(); ?>
</body>
</html>
