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
				<?php mv_social_icons(); ?>
			<?php if ( mv_ga_id() ) : ?>
				<button type="button" class="mv-consent-link" data-mv-consent-open>הגדרות פרטיות</button>
			<?php endif; ?>
			<span class="mv-copy">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?></span>
			<span class="mv-credit">פיתוח: <strong>Multi Digital</strong></span>
		</div>
	</footer>

	<div class="mv-sticky">
		<a class="mv-sticky-cta" href="#demo">
			<?php mv_icon( 'calendar', 17 ); ?>
			תיאום הדגמה חינם
		</a>
		<a class="mv-sticky-alt" href="<?php echo esc_url( mv_signup_url() ); ?>">פתיחת חשבון</a>
	</div>

	<?php mv_a11y_toolbar(); ?>

</div><!-- .mv-page -->
<?php wp_footer(); ?>
</body>
</html>
