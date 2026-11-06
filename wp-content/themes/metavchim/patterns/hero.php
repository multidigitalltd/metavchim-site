<?php
/**
 * Title: מתווכים — Hero
 * Slug: metavchim/hero
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: סקשן פתיחה כהה עם כותרת ראשית, כפתורי CTA והדמיית מסך המערכת.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section class="mv-hero mv-on-dark" aria-labelledby="mv-hero-title">
  <div class="mv-hero-glow" aria-hidden="true"></div>
  <div class="mv-hero-aurora" aria-hidden="true"></div>
  <div class="mv-hero-gridbg" aria-hidden="true"></div>

  <div class="mv-hero-inner">
    <div class="mv-hero-copy">
      <h1 class="mv-h1" id="mv-hero-title">המערכת עושה את העבודה.<br>אתם סוגרים <span class="mv-hero-hl">עסקאות</span><span class="mv-dot" aria-hidden="true">.</span></h1>
      <p class="mv-hero-sub">קליטת לידים, תיוק שיחות, התאמות, תזכורות, הצעות והסכמים — הכול רץ מעצמו ברקע, ומגיע אליכם מוכן. מה שנשאר לכם הוא מה שבאמת מכניס כסף: לפגוש לקוחות ולסגור עסקאות — לבד או עם משרד שותף מהרשת.</p>
      <div class="mv-btn-row">
        <a class="mv-btn-green" href="<?php echo esc_url( mv_signup_url() ); ?>">פתיחת חשבון חינם</a>
        <a class="mv-btn-ghost" href="#network">איך עובדים שיתופי הפעולה</a>
      </div>
      <ul class="mv-hero-trust">
        <li><?php mv_icon( 'check', 15 ); ?>מסלול שתפים — חינם לתמיד</li>
        <li><?php mv_icon( 'check', 15 ); ?>נכסים וקונים ללא הגבלה</li>
        <li><?php mv_icon( 'check', 15 ); ?>בלי התקנה — מהדפדפן ומהנייד</li>
      </ul>
    </div>

    <div class="mv-hero-device" role="img" aria-label="הדמיית המערכת בנייד: לוח הבקרה מציג ארבע התאמות חדשות ושבע משימות להיום, ומסביבו פעולות שהמערכת ביצעה לבד — שיחה שתומללה לכרטיס, התאמה בציון 92%, סיור שנקבע ביומן, תזכורות שנשלחו והסכם שנחתם דיגיטלית.">
      <div class="mv-phone" aria-hidden="true">
        <div class="mv-phone-notch"></div>
        <div class="mv-phone-screen">
          <div class="mv-ph-top">
            <span class="mv-ph-hello">בוקר טוב, דנה</span>
            <span class="mv-ph-ava"><?php mv_logo_svg( 15, '#70EE91', '#FFFFFF' ); ?></span>
          </div>

          <div class="mv-ph-kpis">
            <div class="mv-ph-kpi">
              <span class="mv-ph-kpi-v">4</span>
              <span class="mv-ph-kpi-t">התאמות חדשות</span>
            </div>
            <div class="mv-ph-kpi">
              <span class="mv-ph-kpi-v">7</span>
              <span class="mv-ph-kpi-t">משימות להיום</span>
            </div>
          </div>

          <p class="mv-ph-label">המערכת עשתה בשבילך</p>
          <ul class="mv-ph-feed">
            <li>
              <span class="mv-ph-ico is-green"><?php mv_icon( 'mic', 15 ); ?></span>
              <span class="mv-ph-row"><strong>שיחה תומללה</strong><em>נפתח כרטיס קונה · גבעתיים</em></span>
              <span class="mv-ph-ok"><?php mv_icon( 'check', 12 ); ?></span>
            </li>
            <li>
              <span class="mv-ph-ico is-violet"><?php mv_icon( 'match', 15 ); ?></span>
              <span class="mv-ph-row"><strong>התאמה 92%</strong><em>ויצמן 9 · משפחת אלמוג</em></span>
              <span class="mv-ph-ok"><?php mv_icon( 'check', 12 ); ?></span>
            </li>
            <li>
              <span class="mv-ph-ico is-blue"><?php mv_icon( 'calendar', 15 ); ?></span>
              <span class="mv-ph-row"><strong>סיור נקבע · מחר 16:00</strong><em>אישור נשלח ללקוח</em></span>
              <span class="mv-ph-ok"><?php mv_icon( 'check', 12 ); ?></span>
            </li>
            <li>
              <span class="mv-ph-ico is-green"><?php mv_icon( 'signature', 15 ); ?></span>
              <span class="mv-ph-row"><strong>הסכם בלעדיות נחתם</strong><em>נשמר בכרטיס הנכס</em></span>
              <span class="mv-ph-ok"><?php mv_icon( 'check', 12 ); ?></span>
            </li>
          </ul>

          <div class="mv-ph-tabs">
            <span class="is-on"><?php mv_icon( 'gauge', 16 ); ?></span>
            <span><?php mv_icon( 'building', 16 ); ?></span>
            <span><?php mv_icon( 'users', 16 ); ?></span>
            <span><?php mv_icon( 'whatsapp', 16 ); ?></span>
            <span><?php mv_icon( 'chart', 16 ); ?></span>
          </div>
        </div>
      </div>

      <div class="mv-chips" aria-hidden="true">
        <span class="mv-chip mv-chip-1"><?php mv_icon( 'whatsapp', 15 ); ?>ליד מווטסאפ נקלט</span>
        <span class="mv-chip mv-chip-2"><?php mv_icon( 'bell', 15 ); ?>7 תזכורות יצאו</span>
        <span class="mv-chip mv-chip-3"><?php mv_icon( 'handshake', 15 ); ?>שת״פ נסגר בעמלה מוסכמת</span>
        <span class="mv-chip mv-chip-4"><?php mv_icon( 'offer', 15 ); ?>הצעה נשלחה ונפתחה</span>
      </div>
    </div>
  </div>

  <div class="mv-shot" role="img" aria-label="הדמיית מסך המערכת: רשימת התאמות פתוחות עם ציוני התאמה ותמלול שיחה נכנסת בזמן אמת">
    <div class="mv-shot-frame">
      <div class="mv-shot-app" aria-hidden="true">
        <div class="mv-shot-side">
          <div class="mv-shot-side-brand">
            <svg width="22" height="22" viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M28.5 6.5h7a6 6 0 0 1 6 6v23a6 6 0 0 1-6 6h-7" stroke="#fff" stroke-width="5.4" stroke-linecap="round"/><path d="M19.5 6.5h-7a6 6 0 0 0-6 6v23a6 6 0 0 0 6 6h7" stroke="#70EE91" stroke-width="5.4" stroke-linecap="round"/><rect x="19.4" y="19.4" width="9.2" height="9.2" rx="2.4" fill="#70EE91"/></svg>
            <span>מתווכים<span style="color:#70EE91">.</span></span>
          </div>
          <div class="mv-shot-side-nav">
            <span>לוח בקרה</span>
            <span class="is-active">התאמות · 12</span>
            <span>נכסים</span>
            <span>קונים</span>
            <span>לידים</span>
            <span>שיתופי פעולה</span>
          </div>
        </div>
        <div class="mv-shot-main">
          <div class="mv-shot-head">
            <div class="mv-shot-title">התאמות פתוחות</div>
            <span class="mv-badge-green">4 חדשות היום</span>
            <span class="mv-shot-filter">מסונן: גבעתיים · 4 חד'</span>
          </div>
          <div class="mv-match-grid">
            <div class="mv-mcard">
              <div class="mv-mcard-top"><span class="mv-mcard-label">התאמה פנימית</span><span class="mv-mscore">92</span></div>
              <div class="mv-mcard-name">כצנלסון 44</div>
              <div class="mv-mcard-meta">משפ' אלמוג · 4 חד' · 3.15M</div>
              <div class="mv-meter"><span style="width:92%"></span></div>
            </div>
            <div class="mv-mcard is-partner">
              <div class="mv-mcard-top"><span class="mv-mcard-label">משרד שותף</span><span class="mv-mscore is-tint">87</span></div>
              <div class="mv-mcard-name">מרכז גבעתיים</div>
              <div class="mv-mcard-meta">נדרש אישור דו‑צדדי</div>
              <div class="mv-meter"><span class="is-mid" style="width:87%"></span></div>
            </div>
            <div class="mv-mcard">
              <div class="mv-mcard-top"><span class="mv-mcard-label">התאמה פנימית</span><span class="mv-mscore is-tint">78</span></div>
              <div class="mv-mcard-name">ויצמן 9</div>
              <div class="mv-mcard-meta">דנה כהן · 3.5 חד' · 2.9M</div>
              <div class="mv-meter"><span class="is-low" style="width:78%"></span></div>
            </div>
          </div>
          <div class="mv-live">
            <div class="mv-live-head"><span class="mv-recdot"></span>שיחה נכנסת מתומללת</div>
            <div class="mv-live-row">
              <div class="mv-wave"><span></span><span></span><span></span><span></span><span></span><span></span></div>
              <div class="mv-live-quote">"…4 חדרים בגבעתיים, עד 3.2, קומה גבוהה עם מעלית"</div>
              <span class="mv-live-result">נבנה כרטיס קונה</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mv-shot-fade" aria-hidden="true"></div>
  </div>
</section>
<!-- /wp:html -->
