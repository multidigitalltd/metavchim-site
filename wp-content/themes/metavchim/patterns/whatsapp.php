<?php
/**
 * Title: מתווכים — הסוכן בווטסאפ
 * Slug: metavchim/whatsapp
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: אזור כהה ורחב לסוכן הווטסאפ, עם הדגמת שיחה מונפשת: הקמת נכס, התאמות, קביעת סיור ותזכורות.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="whatsapp" class="mv-sec mv-wa-sec" aria-labelledby="mv-wa-title">
  <div class="mv-wa-card mv-on-dark">
    <div class="mv-wa-glow" aria-hidden="true"></div>
    <div class="mv-wa-mesh" aria-hidden="true"></div>

    <div class="mv-wa-grid">
      <div class="mv-wa-copy">
        <p class="mv-wa-pill"><?php mv_icon( 'whatsapp', 17 ); ?>סוכן בווטסאפ</p>
        <h2 class="mv-h2" id="mv-wa-title">כותבים הודעה<span class="mv-wa-dot" aria-hidden="true">.</span> המערכת כבר עשתה<span class="mv-wa-dot" aria-hidden="true">.</span></h2>
        <p class="mv-wa-lede">בלי אפליקציה, בלי טפסים ובלי ללמוד מערכת חדשה. אותו ווטסאפ שאתם כבר בתוכו כל היום — רק שעכשיו בצד השני יושב סוכן שמקים נכסים, מוצא התאמות, קובע פגישות ולא שוכח אף לקוח.</p>

        <div class="mv-wa-feats">
          <div class="mv-wa-feat">
            <span class="mv-wa-feat-ico"><?php mv_icon( 'building', 20 ); ?></span>
            <div>
              <p class="mv-wa-feat-title">מקים נכס מהודעה</p>
              <p class="mv-wa-feat-text">כותבים או מקליטים — הכרטיס נפתח מלא, עם חדרים, קומה ומחיר.</p>
            </div>
          </div>
          <div class="mv-wa-feat">
            <span class="mv-wa-feat-ico"><?php mv_icon( 'match', 20 ); ?></span>
            <div>
              <p class="mv-wa-feat-title">מוצא התאמות מיד</p>
              <p class="mv-wa-feat-text">מי מהקונים שלכם — ושל הרשת — מתאים לנכס, עם ציון התאמה.</p>
            </div>
          </div>
          <div class="mv-wa-feat">
            <span class="mv-wa-feat-ico"><?php mv_icon( 'calendar', 20 ); ?></span>
            <div>
              <p class="mv-wa-feat-title">קובע סיורים ופגישות</p>
              <p class="mv-wa-feat-text">נכנס ליומן, והלקוח מקבל אישור בלי שתכתבו לו מילה.</p>
            </div>
          </div>
          <div class="mv-wa-feat">
            <span class="mv-wa-feat-ico"><?php mv_icon( 'bell', 20 ); ?></span>
            <div>
              <p class="mv-wa-feat-title">מזכיר ועושה פולואפ</p>
              <p class="mv-wa-feat-text">מי ראה ולא חזר, למי מגיעה תזכורת היום — והוא שולח אותה.</p>
            </div>
          </div>
        </div>

        <p class="mv-wa-note"><?php mv_icon( 'lock', 15 ); ?>כל מה שקורה בצ'אט נשמר בכרטיס במערכת — השיחה היא רק הדרך, המאגר נשאר שלכם.</p>
      </div>

      <div class="mv-wa-demo">
        <div class="mv-wa-phone" role="img" aria-label="הדגמת שיחה בווטסאפ עם הסוכן: מבקשים להוסיף נכס והוא נפתח ככרטיס במערכת, הסוכן מחזיר שלוש התאמות עם ציון, קובע סיור ביומן ושולח אישור ללקוח, ומוציא תזכורות לכל מי שראה את הנכס.">
          <div class="mv-wa-top">
            <span class="mv-wa-avatar" aria-hidden="true"><?php mv_logo_svg( 18, '#70EE91', '#FFFFFF' ); ?></span>
            <div class="mv-wa-who">
              <p class="mv-wa-name">מתווכים · הסוכן</p>
              <p class="mv-wa-status"><span class="mv-wa-live" aria-hidden="true"></span>מקוון · מגיב תוך שניות</p>
            </div>
            <span class="mv-wa-call" aria-hidden="true"><?php mv_icon( 'phone', 17 ); ?></span>
          </div>

          <div class="mv-wa-log" aria-hidden="true">
            <span class="mv-wa-day">היום</span>

            <div class="mv-wa-msg is-me">
              <p class="mv-wa-text">תוסיף נכס: ויצמן 9 גבעתיים, 4 חדרים, קומה 3, 2.9 מיליון</p>
              <span class="mv-wa-meta">09:41<span class="mv-wa-ticks">✓✓</span></span>
            </div>

            <div class="mv-wa-msg is-bot">
              <p class="mv-wa-text">נפתח כרטיס נכס<span class="mv-wa-dot">.</span></p>
              <div class="mv-wa-mini">
                <span class="mv-wa-mini-ico"><?php mv_icon( 'building', 16 ); ?></span>
                <div class="mv-wa-mini-body">
                  <p class="mv-wa-mini-title">ויצמן 9, גבעתיים</p>
                  <p class="mv-wa-mini-meta">4 חדרים · קומה 3 · 2.9 מיליון</p>
                </div>
                <span class="mv-wa-tag">נוצר</span>
              </div>
              <span class="mv-wa-meta">09:41</span>
            </div>

            <div class="mv-wa-msg is-bot">
              <p class="mv-wa-text">בדקתי מול המאגר ומול הרשת — 3 קונים מתאימים<span class="mv-wa-dot">.</span></p>
              <div class="mv-wa-mini">
                <span class="mv-wa-mini-ico"><?php mv_icon( 'match', 16 ); ?></span>
                <div class="mv-wa-mini-body">
                  <p class="mv-wa-mini-title">משפחת אלמוג</p>
                  <p class="mv-wa-mini-meta">מחפשים 4 חדרים בגבעתיים · תקציב 3.0</p>
                </div>
                <span class="mv-wa-score">94%</span>
              </div>
              <div class="mv-wa-mini">
                <span class="mv-wa-mini-ico"><?php mv_icon( 'match', 16 ); ?></span>
                <div class="mv-wa-mini-body">
                  <p class="mv-wa-mini-title">רון ומיכל</p>
                  <p class="mv-wa-mini-meta">גבעתיים או רמת גן · עד 3.1</p>
                </div>
                <span class="mv-wa-score">88%</span>
              </div>
              <span class="mv-wa-meta">09:41</span>
            </div>

            <div class="mv-wa-msg is-me">
              <p class="mv-wa-text">תקבע סיור עם אלמוג מחר ב‑16:00</p>
              <span class="mv-wa-meta">09:42<span class="mv-wa-ticks">✓✓</span></span>
            </div>

            <div class="mv-wa-msg is-bot">
              <p class="mv-wa-text">נקבע<span class="mv-wa-dot">.</span> שלחתי להם אישור בהודעה</p>
              <div class="mv-wa-mini">
                <span class="mv-wa-mini-ico"><?php mv_icon( 'calendar', 16 ); ?></span>
                <div class="mv-wa-mini-body">
                  <p class="mv-wa-mini-title">סיור · מחר 16:00</p>
                  <p class="mv-wa-mini-meta">נכנס ליומן · תזכורת שעה לפני</p>
                </div>
                <span class="mv-wa-tag">בוצע</span>
              </div>
              <span class="mv-wa-meta">09:42</span>
            </div>

            <div class="mv-wa-msg is-me">
              <p class="mv-wa-text">תשלח תזכורת לכל מי שראה את הנכס ולא חזר</p>
              <span class="mv-wa-meta">09:43<span class="mv-wa-ticks">✓✓</span></span>
            </div>

            <div class="mv-wa-msg is-bot">
              <p class="mv-wa-text">יצאו 7 תזכורות, וכל אחת מתועדת בכרטיס הלקוח<span class="mv-wa-dot">.</span></p>
              <div class="mv-wa-mini">
                <span class="mv-wa-mini-ico"><?php mv_icon( 'bell', 16 ); ?></span>
                <div class="mv-wa-mini-body">
                  <p class="mv-wa-mini-title">7 תזכורות נשלחו</p>
                  <p class="mv-wa-mini-meta">2 כבר ענו · מחכים לך במערכת</p>
                </div>
                <span class="mv-wa-tag">בוצע</span>
              </div>
              <span class="mv-wa-meta">09:43</span>
            </div>

            <div class="mv-wa-typing" hidden>
              <span></span><span></span><span></span>
            </div>
          </div>

          <div class="mv-wa-input" aria-hidden="true">
            <span class="mv-wa-field">הודעה לסוכן…</span>
            <span class="mv-wa-send"><?php mv_icon( 'mic', 17 ); ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
