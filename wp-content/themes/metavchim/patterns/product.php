<?php
/**
 * Title: מתווכים — הדגמת המערכת (טאבים)
 * Slug: metavchim/product
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: ארבעה טאבים נגישים המציגים את מסכי המערכת: התאמות, שיחה נכנסת, רשת שותפים ודוחות משרד.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section id="product" class="mv-sec mv-product-sec" aria-labelledby="mv-product-title">
  <div class="mv-wrap">
    <h2 class="mv-h2" id="mv-product-title">מסך אחד, ארבעה מבטים על אותה עסקה<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-lede">כל מה שהיה מפוזר בין ווטסאפ, אקסל ופנקס — במקום אחד.</p>

    <div class="mv-tabs" role="tablist" aria-label="מסכי המערכת">
      <button type="button" class="mv-tab" id="mv-tab-0" role="tab" aria-selected="true" aria-controls="mv-panel-0">התאמות</button>
      <button type="button" class="mv-tab" id="mv-tab-1" role="tab" aria-selected="false" aria-controls="mv-panel-1" tabindex="-1">שיחה נכנסת</button>
      <button type="button" class="mv-tab" id="mv-tab-2" role="tab" aria-selected="false" aria-controls="mv-panel-2" tabindex="-1">רשת שותפים</button>
      <button type="button" class="mv-tab" id="mv-tab-3" role="tab" aria-selected="false" aria-controls="mv-panel-3" tabindex="-1">דוחות משרד</button>
    </div>

    <div class="mv-panel-frame">
      <div class="mv-panel mv-on-dark">

        <div class="mv-tabpanel" id="mv-panel-0" role="tabpanel" aria-labelledby="mv-tab-0">
          <div class="mv-t-match">
            <div class="mv-prop">
              <div class="mv-prop-photo">תמונות הנכס</div>
              <h3 class="mv-prop-title">כצנלסון 44, גבעתיים</h3>
              <div class="mv-prop-meta">4 חדרים · קומה 6 · מעלית · חניה</div>
              <div class="mv-prop-price">₪3,150,000</div>
              <div class="mv-prop-tags"><span>בלעדיות</span><span>כניסה גמישה</span><span>דף נחיתה פעיל</span></div>
            </div>
            <div>
              <div class="mv-buyers-head">
                <h3 class="mv-buyers-title">קונים מתאימים</h3>
                <span class="mv-buyers-sub">מדורג לפי תקציב, אזור, חדרים ומצב מימון</span>
              </div>
              <div class="mv-buyers">
                <div class="mv-buyer">
                  <span class="mv-bscore" aria-label="ציון התאמה 92">92</span>
                  <div class="mv-buyer-body">
                    <p class="mv-buyer-name">משפחת אלמוג</p>
                    <div class="mv-buyer-meta">תקציב 3.2M · מימון מאושר · חיפוש 6 שבועות</div>
                  </div>
                  <span class="mv-chip-solid">שליחת הצעה</span>
                </div>
                <div class="mv-buyer">
                  <span class="mv-bscore is-tint" aria-label="ציון התאמה 84">84</span>
                  <div class="mv-buyer-body">
                    <p class="mv-buyer-name">דנה כהן</p>
                    <div class="mv-buyer-meta">תקציב 3.0M · גמישה באזור · לא ראתה מעלית</div>
                  </div>
                  <span class="mv-chip-line">שליחת הצעה</span>
                </div>
                <div class="mv-buyer is-net">
                  <span class="mv-bscore is-tint" aria-label="ציון התאמה 81">81</span>
                  <div class="mv-buyer-body">
                    <p class="mv-buyer-name">קונה ממשרד שותף</p>
                    <div class="mv-buyer-meta">פרטים מלאים ייחשפו לאחר אישור שני הצדדים</div>
                  </div>
                  <span class="mv-chip-green">בקשת חשיפה</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mv-tabpanel" id="mv-panel-1" role="tabpanel" aria-labelledby="mv-tab-1" hidden>
          <div class="mv-t-call">
            <div>
              <div class="mv-call-status"><span class="mv-recdot-lg"></span>שיחה פעילה · 02:14 · <span class="mv-ltr">052-4419087</span></div>
              <div class="mv-chat">
                <div class="mv-bub">שלום, ראיתי אצלכם דירה בגבעתיים. אני מחפש 4 חדרים באזור.</div>
                <div class="mv-bub is-me">מה טווח התקציב, ויש משכנתא מאושרת?</div>
                <div class="mv-bub">עד 3.2 מיליון, יש אישור עקרוני מהבנק. חשוב לי קומה גבוהה עם מעלית, ואפשר להיכנס רק בקיץ.</div>
                <div class="mv-typing"><span></span>ממשיך לתמלל…</div>
              </div>
            </div>
            <div class="mv-autocard">
              <div class="mv-autocard-title">כרטיס קונה נבנה אוטומטית</div>
              <div class="mv-autocard-rows">
                <div class="mv-arow"><span class="mv-arow-k">אזור</span><span class="mv-arow-v">גבעתיים</span></div>
                <div class="mv-arow"><span class="mv-arow-k">חדרים</span><span class="mv-arow-v">4</span></div>
                <div class="mv-arow"><span class="mv-arow-k">תקציב</span><span class="mv-arow-v mv-ltr">עד ₪3.2M</span></div>
                <div class="mv-arow"><span class="mv-arow-k">מימון</span><span class="mv-arow-v is-green">אישור עקרוני</span></div>
                <div class="mv-arow"><span class="mv-arow-k">מועד כניסה</span><span class="mv-arow-v">קיץ</span></div>
              </div>
              <span class="mv-autocard-cta" role="presentation">אישור ושמירה במאגר</span>
              <div class="mv-autocard-note">3 נכסים מתאימים כבר ממתינים</div>
            </div>
          </div>
        </div>

        <div class="mv-tabpanel" id="mv-panel-2" role="tabpanel" aria-labelledby="mv-tab-2" hidden>
          <div class="mv-t-net">
            <div>
              <h3 class="mv-t-net-title">משרדים שותפים</h3>
              <div class="mv-t-net-sub">אתה בוחר עם מי לשתף, ומה נחשף בשלב הראשון.</div>
              <div class="mv-partners">
                <div class="mv-partner">
                  <span class="mv-pavatar" aria-hidden="true">רל</span>
                  <div class="mv-partner-body"><p class="mv-partner-name">רוזן לוין נדל"ן</p><div class="mv-partner-meta">גבעתיים · 8 נכסים רלוונטיים</div></div>
                  <span class="mv-badge-active">פעיל</span>
                </div>
                <div class="mv-partner">
                  <span class="mv-pavatar" aria-hidden="true">אב</span>
                  <div class="mv-partner-body"><p class="mv-partner-name">אבני דרך</p><div class="mv-partner-meta">רמת גן · 5 נכסים רלוונטיים</div></div>
                  <span class="mv-badge-active">פעיל</span>
                </div>
                <div class="mv-partner is-pending">
                  <span class="mv-pavatar" aria-hidden="true">שק</span>
                  <div class="mv-partner-body"><p class="mv-partner-name">שחר קרן נכסים</p><div class="mv-partner-meta">תל אביב · בקשה ממתינה</div></div>
                  <span class="mv-badge-approve" role="presentation">אישור</span>
                </div>
              </div>
            </div>
            <div class="mv-shared">
              <div class="mv-shared-head">
                <h3 class="mv-shared-title">נכס אצל שותף</h3>
                <span class="mv-badge-active">חשיפה חלקית</span>
              </div>
              <div class="mv-shared-rows">
                <div class="mv-srow"><span class="mv-srow-k">אזור</span><span class="mv-srow-v">מרכז גבעתיים</span></div>
                <div class="mv-srow"><span class="mv-srow-k">חדרים</span><span class="mv-srow-v">4</span></div>
                <div class="mv-srow"><span class="mv-srow-k">מחיר</span><span class="mv-srow-v mv-ltr">₪3,150,000</span></div>
                <div class="mv-srow"><span class="mv-srow-k">כתובת מלאה</span><span class="mv-locked">נחשף לאחר אישור</span></div>
                <div class="mv-srow"><span class="mv-srow-k">פרטי בעלים</span><span class="mv-locked">נחשף לאחר אישור</span></div>
              </div>
              <div class="mv-fee"><span class="mv-srow-k">חלוקת עמלה מוצעת</span><span class="mv-fee-v">50 / 50</span></div>
              <div class="mv-shared-ctas">
                <span class="mv-shared-cta" role="presentation">בקשת חשיפה מלאה</span>
                <span class="mv-shared-alt" role="presentation">שיחה עם המשרד</span>
              </div>
            </div>
          </div>
        </div>

        <div class="mv-tabpanel" id="mv-panel-3" role="tabpanel" aria-labelledby="mv-tab-3" hidden>
          <div>
            <div class="mv-kpis">
              <div class="mv-kpi"><div class="mv-kpi-k">לידים החודש</div><div class="mv-kpi-v">148</div></div>
              <div class="mv-kpi"><div class="mv-kpi-k">סיורים שנקבעו</div><div class="mv-kpi-v">37</div></div>
              <div class="mv-kpi"><div class="mv-kpi-k">הצעות שנשלחו</div><div class="mv-kpi-v">21</div></div>
              <div class="mv-kpi is-green"><div class="mv-kpi-k">עסקאות שנסגרו</div><div class="mv-kpi-v">6</div></div>
            </div>
            <div class="mv-charts">
              <div class="mv-chart">
                <h3 class="mv-chart-title">לידים לפי מקור</h3>
                <div class="mv-bars" role="img" aria-label="גרף עמודות: שיחות נכנסות הן מקור הלידים הגדול ביותר, אחריהן דפי נחיתה, רשת שותפים והמלצות">
                  <div class="mv-bar"><i style="height:64%"></i><span class="mv-bar-label">דפי נחיתה</span></div>
                  <div class="mv-bar"><i style="height:100%"></i><span class="mv-bar-label">שיחות נכנסות</span></div>
                  <div class="mv-bar"><i style="height:46%"></i><span class="mv-bar-label">רשת שותפים</span></div>
                  <div class="mv-bar"><i style="height:32%"></i><span class="mv-bar-label">המלצות</span></div>
                </div>
              </div>
              <div class="mv-chart">
                <h3 class="mv-chart-title">ביצועי סוכנים</h3>
                <div class="mv-agents">
                  <div><div class="mv-agent-head"><span>אורית לוי</span><span>3 עסקאות</span></div><div class="mv-agent-meter"><span style="width:82%"></span></div></div>
                  <div><div class="mv-agent-head"><span>יואב מזרחי</span><span>2 עסקאות</span></div><div class="mv-agent-meter"><span class="is-mid" style="width:58%"></span></div></div>
                  <div><div class="mv-agent-head"><span>נועה בר</span><span>1 עסקה</span></div><div class="mv-agent-meter"><span class="is-low" style="width:34%"></span></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
