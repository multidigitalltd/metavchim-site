<?php
/**
 * Title: מתווכים — הליווי האישי
 * Slug: metavchim/companion
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: שלושה כרטיסים גדולים: סוכן בווטסאפ, מנטור AI אישי ופורום אנונימי למתווכים.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="companion" class="mv-sec mv-comp-sec" aria-labelledby="mv-comp-title">
  <div class="mv-wrap">
    <div class="mv-sec-head">
      <p class="mv-pill-tint"><?php mv_icon( 'sparkle', 15 ); ?>לא רק מערכת</p>
      <h2 class="mv-h2" id="mv-comp-title">יש לך צוות שלם מאחורי הטלפון<span class="mv-dot" aria-hidden="true">.</span></h2>
    </div>

    <div class="mv-comp-grid">

      <article class="mv-comp mv-comp-wa">
        <div class="mv-comp-top">
          <span class="mv-comp-ico"><?php mv_icon( 'whatsapp', 26 ); ?></span>
          <h3 class="mv-comp-title">הסוכן בווטסאפ</h3>
        </div>
        <p class="mv-comp-text">הכול נעשה מהצ'אט. כותבים הודעה — והמערכת מבצעת.</p>
        <ul class="mv-comp-list">
          <li><?php mv_icon( 'check', 15 ); ?>מוסיף נכס או קונה</li>
          <li><?php mv_icon( 'check', 15 ); ?>שולף התאמות</li>
          <li><?php mv_icon( 'check', 15 ); ?>קובע סיור</li>
          <li><?php mv_icon( 'check', 15 ); ?>שולח הצעה ללקוח</li>
        </ul>
        <div class="mv-chat" aria-hidden="true">
          <span class="mv-chat-me">תוסיף 4 חדרים בגבעתיים, 2.9</span>
          <span class="mv-chat-bot">נוסף<span class="mv-chat-dot">.</span> 3 קונים מתאימים במאגר</span>
        </div>
      </article>

      <article class="mv-comp mv-comp-ai">
        <div class="mv-comp-top">
          <span class="mv-comp-ico"><?php mv_icon( 'mentor', 26 ); ?></span>
          <h3 class="mv-comp-title">מנטור AI אישי</h3>
        </div>
        <p class="mv-comp-text">קובע איתך יעדים, עוקב אחריהם ודוחף קדימה.</p>
        <ul class="mv-comp-list">
          <li><?php mv_icon( 'check', 15 ); ?>יעד שבועי לפי הביצועים שלך</li>
          <li><?php mv_icon( 'check', 15 ); ?>תזכורת למי לחזור היום</li>
          <li><?php mv_icon( 'check', 15 ); ?>עידוד כשסוגרים, וכיוון כשתקוע</li>
        </ul>
        <div class="mv-goal" aria-hidden="true">
          <div class="mv-goal-row"><span>יעד השבוע</span><strong>7 / 10 פגישות</strong></div>
          <div class="mv-goal-bar"><span></span></div>
          <p class="mv-goal-msg">עוד 3 והשבוע הזה שלך<span class="mv-chat-dot">.</span></p>
        </div>
      </article>

      <article class="mv-comp mv-comp-forum">
        <div class="mv-comp-top">
          <span class="mv-comp-ico"><?php mv_icon( 'forum', 26 ); ?></span>
          <h3 class="mv-comp-title">פורום המתווכים</h3>
        </div>
        <p class="mv-comp-text">מתייעצים באנונימיות מלאה עם מי שכבר היה שם.</p>
        <ul class="mv-comp-list">
          <li><?php mv_icon( 'check', 15 ); ?>שאלות על עסקאות, עמלות והסכמים</li>
          <li><?php mv_icon( 'check', 15 ); ?>בלי שם ובלי משרד</li>
          <li><?php mv_icon( 'check', 15 ); ?>תשובות ממתווכים ותיקים</li>
        </ul>
        <div class="mv-forum" aria-hidden="true">
          <span class="mv-forum-q">"מוכר דורש לרדת בעמלה באמצע — מה עושים?"</span>
          <span class="mv-forum-a">12 תשובות · אנונימי</span>
        </div>
      </article>

    </div>
  </div>
</section>
<!-- /wp:html -->
