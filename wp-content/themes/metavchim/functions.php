<?php
/**
 * Metavchim theme bootstrap.
 *
 * טעינת רכיבי התבנית. כל לוגיקה יושבת בקבצי inc ממוקדים
 * כדי לשמור על קוד קצר, קריא וללא כפילויות (תקן Multi Digital).
 *
 * @package Metavchim
 */

defined( 'ABSPATH' ) || exit;

define( 'MV_THEME_VERSION', '1.18.2' );
define( 'MV_THEME_DIR', get_template_directory() );
define( 'MV_THEME_URI', get_template_directory_uri() );

require MV_THEME_DIR . '/inc/setup.php';
require MV_THEME_DIR . '/inc/assets.php';
require MV_THEME_DIR . '/inc/icons.php';
require MV_THEME_DIR . '/inc/features.php';
require MV_THEME_DIR . '/inc/template-tags.php';
require MV_THEME_DIR . '/inc/seo.php';
require MV_THEME_DIR . '/inc/dashboard.php';
require MV_THEME_DIR . '/inc/numbers.php';
require MV_THEME_DIR . '/inc/social.php';
require MV_THEME_DIR . '/inc/clients.php';
require MV_THEME_DIR . '/inc/plans.php';
require MV_THEME_DIR . '/inc/leads.php';
require MV_THEME_DIR . '/inc/turnstile.php';
require MV_THEME_DIR . '/inc/newsletter.php';
require MV_THEME_DIR . '/inc/consent.php';
require MV_THEME_DIR . '/inc/event.php';
require MV_THEME_DIR . '/inc/install-content.php';
