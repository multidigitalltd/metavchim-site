<?php
/**
 * Standalone template for the event landing page.
 *
 * דף נחיתה עומד בפני עצמו: בלי תפריט האתר, בלי הפוטר ובלי הכפתורים
 * הדביקים — רק התוכן של הדף, עם סרגל הנגישות ובאנר ההסכמה שנדרשים
 * בכל עמוד באתר.
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'mv-lp' ); ?>>
<a class="skip-link screen-reader-text" href="#register">דילוג לטופס ההרשמה</a>
<?php
while ( have_posts() ) {
	the_post();
	the_content();
}

mv_a11y_toolbar();
wp_footer();
?>
</body>
</html>
