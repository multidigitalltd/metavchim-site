<?php
/**
 * Title: מתווכים — כל היכולות
 * Slug: metavchim/capabilities
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: מפת היכולות של המערכת — אייקון, שם והסבר קצר לכל יכולת, מסודר לפי תחומים.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="capabilities" class="mv-sec mv-caps-sec" aria-labelledby="mv-caps-title">
  <div class="mv-wrap">
    <div class="mv-sec-head">
      <h2 class="mv-h2" id="mv-caps-title">כל מה שהמערכת עושה בשבילך<span class="mv-dot" aria-hidden="true">.</span></h2>
      <p class="mv-lede">מהרגע שנכס או קונה נכנסים למאגר — ועד סגירת העסקה והחשבון מול השותף.</p>
    </div>

<?php foreach ( mv_feature_groups() as $group ) : ?>
    <div class="mv-cap-group">
      <h3 class="mv-cap-group-title"><?php echo esc_html( $group['title'] ); ?><span class="mv-cap-count"><?php echo esc_html( (string) count( $group['items'] ) ); ?></span></h3>
      <div class="mv-caps">
<?php foreach ( $group['items'] as $item ) : ?>
        <div class="mv-cap"><span class="mv-cap-ico"><?php mv_icon( $item['icon'], 21 ); ?></span><h4 class="mv-cap-title"><?php echo esc_html( $item['title'] ); ?></h4><p class="mv-cap-text"><?php echo esc_html( $item['text'] ); ?></p></div>
<?php endforeach; ?>
      </div>
    </div>
<?php endforeach; ?>

  </div>
</section>
<!-- /wp:html -->
