<?php
/**
 * Title: מתווכים — סוכן קולי
 * Slug: metavchim/voice
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: כרטיס לבן מפוצל: פיצ'רים של הסוכן הקולי מול הדגמה כהה עם מיקרופון מונפש ופקודות מתחלפות.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="voice" class="mv-sec mv-voice-sec" aria-labelledby="mv-voice-title">
  <div class="mv-voice-card">
    <div>
      <p class="mv-pill-tint">סוכן קולי<span class="mv-soon">בקרוב</span></p>
      <h2 class="mv-h2" id="mv-voice-title">אומרים למערכת מה לעשות. היא עושה<span class="mv-dot" aria-hidden="true">.</span></h2>
      <p class="mv-lede">בין סיור לסיור, ביד אחת על ההגה, אין זמן לטפסים. הסוכן הקולי יבין עברית מדוברת ויבצע את הפעולה בפועל במערכת — בלי הקלדה אחת. היכולת בפיתוח מתקדם ותושק בקרוב.</p>
      <div class="mv-checks">
        <div class="mv-check"><span class="mv-check-ico" aria-hidden="true">✓</span><span class="mv-check-text">הכנסת נכס חדש בדיבור, כולל חדרים, קומה ומחיר</span></div>
        <div class="mv-check"><span class="mv-check-ico" aria-hidden="true">✓</span><span class="mv-check-text">תיאום סיורים ופגישות ישירות ביומן, עם הודעה ללקוח</span></div>
        <div class="mv-check"><span class="mv-check-ico" aria-hidden="true">✓</span><span class="mv-check-text">תזכורות ופולואפ לכל מי שראה נכס או קיבל הצעה</span></div>
        <div class="mv-check"><span class="mv-check-ico" aria-hidden="true">✓</span><span class="mv-check-text">שאלות על המאגר: "מי מחפש 4 חדרים בגבעתיים?"</span></div>
      </div>
    </div>

    <div class="mv-voice-demo mv-on-dark" role="img" aria-label="הדגמת הסוכן הקולי: המערכת מקשיבה לפקודה מדוברת בעברית ומבצעת אותה — קובעת סיור ביומן, יוצרת כרטיס נכס ושולחת תזכורות">
      <div class="mv-voice-glow" aria-hidden="true"></div>
      <div aria-hidden="true">
        <div class="mv-voice-top">
          <div class="mv-mic">
            <span class="mv-mic-ring"></span>
            <span class="mv-mic-ring"></span>
            <span class="mv-mic-ring"></span>
            <span class="mv-mic-core"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 3a2.6 2.6 0 0 1 2.6 2.6v5.2a2.6 2.6 0 0 1-5.2 0V5.6A2.6 2.6 0 0 1 12 3Z" fill="#0A0C0B"/><path d="M5.6 11a6.4 6.4 0 0 0 12.8 0M12 17.4V21" stroke="#0A0C0B" stroke-width="1.9" stroke-linecap="round"/></svg></span>
          </div>
          <div class="mv-voice-listen">
            <div class="mv-voice-label">מקשיב</div>
            <div class="mv-wave"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
          </div>
        </div>

        <div class="mv-says">
          <div class="mv-say">"תקבע סיור מחר בארבע עם משפחת אלמוג בכצנלסון 44"<span class="mv-caret">|</span></div>
          <div class="mv-say">"תכניס נכס חדש: ויצמן 9, 3 וחצי חדרים, קומה 2, שתי מיליון תשע"<span class="mv-caret">|</span></div>
          <div class="mv-say">"תשלח תזכורת לכל מי שראה את הדירה בוויצמן השבוע"<span class="mv-caret">|</span></div>
        </div>

        <div class="mv-acts">
          <div class="mv-act">
            <span class="mv-act-ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><rect x="4" y="5.5" width="16" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M8 3.5v4M16 3.5v4M4 10h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
            <div class="mv-act-body">
              <p class="mv-act-title">סיור נקבע · מחר 16:00</p>
              <div class="mv-act-meta">הוזן ליומן · הודעת אישור נשלחה ללקוח</div>
            </div>
            <span class="mv-done">בוצע</span>
          </div>
          <div class="mv-act">
            <span class="mv-act-ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4 20V9.5L12 4l8 5.5V20" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
            <div class="mv-act-body">
              <p class="mv-act-title">כרטיס נכס נוצר · ויצמן 9</p>
              <div class="mv-act-meta">דף נחיתה פורסם · 2 קונים מתאימים נמצאו</div>
            </div>
            <span class="mv-done">בוצע</span>
          </div>
          <div class="mv-act">
            <span class="mv-act-ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M4 6.5h16v11H4z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
            <div class="mv-act-body">
              <p class="mv-act-title">7 תזכורות נשלחו</p>
              <div class="mv-act-meta">לכל מי שראה את הנכס · מתועד בכרטיס הלקוח</div>
            </div>
            <span class="mv-done">בוצע</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
