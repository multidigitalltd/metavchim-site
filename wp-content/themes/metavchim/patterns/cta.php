<?php
/**
 * Title: מתווכים — קריאה לפעולה
 * Slug: metavchim/cta
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: כרטיס סגירה כהה עם זוהר ירוק תחתון ושני כפתורים.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="cta" class="mv-cta-sec" aria-labelledby="mv-cta-title">
  <div class="mv-cta mv-on-dark">
    <div class="mv-cta-glow" aria-hidden="true"></div>
    <h2 class="mv-h2" id="mv-cta-title">נראה לך את ההתאמות שכבר יושבות במאגר שלך<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-lede">פותחים חשבון ומתחילים — מסלול שתפים חינם לתמיד, נכסים וקונים ללא הגבלה.</p>
    <div class="mv-btn-row">
      <a class="mv-btn-green" href="<?php echo esc_url( mv_signup_url() ); ?>">פתיחת חשבון חינם</a>
      <a class="mv-btn-ghost" href="#demo">דברו איתנו</a>
    </div>
  </div>
</section>
<!-- /wp:html -->
