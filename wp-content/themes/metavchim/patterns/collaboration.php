<?php
/**
 * Title: מתווכים — עמוד רשת המשרדים
 * Slug: metavchim/collaboration
 * Categories: metavchim
 * Viewport Width: 1400
 * Description: עמוד פנימי מלא על רשת שיתופי הפעולה בין משרדי התיווך — הירו, שני כיווני רווח, המחשה חיה, מספרים וגרפים, זרימת שישה שלבים, כלי שיתוף, שאלות נפוצות, הצטרפות חינם וקריאה לפעולה.
 *
 * @package Metavchim
 */

?>
<!-- wp:html -->
<section class="mv-c-hero mv-on-dark" aria-labelledby="mv-c-hero-title">
  <div class="mv-c-glow" aria-hidden="true"></div>
  <div class="mv-c-grid-bg" aria-hidden="true"></div>
  <div class="mv-c-hero-in">
    <a class="mv-c-back" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      חזרה לדף הבית
    </a>
    <div class="mv-c-hero-grid">
      <div>
        <p class="mv-c-label"><i aria-hidden="true"></i>רשת המשרדים</p>
        <h1 class="mv-c-h1" id="mv-c-hero-title">רשת שיתופי הפעולה<br>היחידה למתווכים בישראל<span class="mv-dot" aria-hidden="true">.</span></h1>
        <p class="mv-c-hero-p">היום כל משרד יושב לבד על נכסים שאין להם קונה ועל קונים שאין להם נכס. ברשת של מתווכים. המאגרים מוצלבים אוטומטית בין משרדים שבחרתם לעבוד יחד — בלי לחשוף פרט אחד לפני שהסכמתם, ועם עמלה שסגורה בכתב מראש.</p>
        <div class="mv-c-btns mv-c-hero-btns">
          <a class="mv-c-btn-green" href="<?php echo esc_url( mv_signup_url() ); ?>">פתיחת חשבון</a>
          <a class="mv-c-btn-ghost" href="#flow">איך זה עובד, שלב שלב</a>
        </div>
      </div>
      <div class="mv-c-stats">
        <div class="mv-c-stat">
          <div class="mv-c-stat-v">×4</div>
          <div class="mv-c-stat-t">כמות הנכסים שקונה שלך רואה, כשארבעה משרדים באזור מחוברים</div>
        </div>
        <div class="mv-c-stat">
          <div class="mv-c-stat-v">אפס</div>
          <div class="mv-c-stat-t">פרטים מזהים שנחשפים לפני אישור של שני הצדדים</div>
        </div>
        <div class="mv-c-stat">
          <div class="mv-c-stat-v">בכתב</div>
          <div class="mv-c-stat-t">חלוקת העמלה מתועדת במערכת לפני השיחה הראשונה</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="mv-c-sec" aria-labelledby="mv-c-dirs-title">
  <div class="mv-c-wrap">
    <h2 class="mv-c-h2" id="mv-c-dirs-title">שני כיווני רווח, מאותו חיבור אחד<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-c-lede">אתה גם נותן וגם מקבל — ובשני המקרים העסקה נשארת שלך.</p>
    <div class="mv-c-dirs">
      <div class="mv-c-dir">
        <span class="mv-c-dir-ico" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.4" stroke="currentColor" stroke-width="1.9"/><path d="M3.5 19.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><path d="M16 9.5h5M18.5 7v5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></span>
        <h3 class="mv-c-dir-title">הקונה שלך, הנכס שלהם</h3>
        <p class="mv-c-dir-text">במקום להגיד "אין לי כרגע מה להציע לך", אתה מציע לקונה שלך נכס מהרשת. אתה נשאר המתווך שלו, מוביל את הסיור ואת המשא ומתן, ומקבל את חלקך בעמלה.</p>
        <ul class="mv-c-dir-list">
          <li>· הקונה לא מגיע למשרד אחר ולא מחפש לבד</li>
          <li>· רשימת הנכסים שלך מתרחבת בלי לגייס בלעדיות חדשה</li>
          <li>· מי שמביא את הקונה מוגן — זה רשום במערכת</li>
        </ul>
      </div>
      <div class="mv-c-dir">
        <span class="mv-c-dir-ico" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 20V9.5L12 4l8 5.5V20" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="M9.5 20v-5a2.5 2.5 0 0 1 5 0v5" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg></span>
        <h3 class="mv-c-dir-title">הנכס שלך, הקונים שלהם</h3>
        <p class="mv-c-dir-text">הבלעדיות שלך נבדקת מול כל הקונים של המשרדים השותפים — קונים מאומתים עם תקציב ומצב מימון ידועים, ולא פניות מזדמנות מלוחות פרסום.</p>
        <ul class="mv-c-dir-list">
          <li>· הנכס נחשף לעשרות קונים בלי לפרסם אותו לכל העולם</li>
          <li>· מוכר מרוצה: אתה מראה לו טווח קונים רחב יותר</li>
          <li>· זמן מדף מתקצר, בלי לוותר על העמלה</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section id="live" class="mv-c-sec" aria-labelledby="mv-c-live-title">
  <div class="mv-c-live mv-on-dark">
    <div class="mv-c-live-glow" aria-hidden="true"></div>
    <div class="mv-c-live-grid">
      <div>
        <p class="mv-c-label"><i aria-hidden="true"></i>ככה זה נראה בפועל</p>
        <h2 class="mv-c-live-h2" id="mv-c-live-title">הקונה שלך מוצא נכס אצל השותף<span class="mv-dot" aria-hidden="true">.</span></h2>
        <p class="mv-c-live-p">ההצלבה רצה לשני הכיוונים כל הזמן. מימין המאגר שלך, משמאל של השותף — והנקודות הן הבקשות שעוברות ביניהם דרך מנוע ההתאמות.</p>
        <div class="mv-c-legend">
          <div><i aria-hidden="true"></i>הקונים שלך יוצאים לחיפוש ברשת</div>
          <div><i aria-hidden="true"></i>הנכסים של השותף חוזרים אליך כהתאמה</div>
        </div>
      </div>

      <div class="mv-c-stage" role="img" aria-label="המחשה: הקונים שלך מימין ונכסי המשרד השותף משמאל, מוצלבים דרך מנוע ההתאמות במרכז עם ציון 87. הבקשות עוברות בין שני הצדדים עד שההתאמה מאושרת והסיור נקבע">
        <div class="mv-c-pool" aria-hidden="true">
          <div class="mv-c-pool-head">
            <span class="mv-c-pool-badge">את</span>
            <span class="mv-c-pool-title">הקונים שלך</span>
          </div>
          <div class="mv-c-pool-list">
            <div class="mv-c-item"><div class="mv-c-item-n">משפחת אלמוג</div><div class="mv-c-item-m">4 חד' · גבעתיים</div></div>
            <div class="mv-c-item"><div class="mv-c-item-n">דנה כהן</div><div class="mv-c-item-m">3.5 חד' · רמת גן</div></div>
            <div class="mv-c-item"><div class="mv-c-item-n">יובל ואורן</div><div class="mv-c-item-m">5 חד' · גבעתיים</div></div>
          </div>
        </div>

        <div class="mv-c-core-wrap" aria-hidden="true">
          <span class="mv-c-track"></span>
          <span class="mv-c-track is-b"></span>
          <span class="mv-c-dot"></span>
          <span class="mv-c-dot is-b"></span>
          <div class="mv-c-core">
            <div>
              <div class="mv-c-core-v">87</div>
              <div class="mv-c-core-t">מנוע<br>ההתאמות</div>
            </div>
          </div>
        </div>

        <div class="mv-c-pool is-partner" aria-hidden="true">
          <div class="mv-c-pool-head">
            <span class="mv-c-pool-badge">רל</span>
            <span class="mv-c-pool-title">נכסים אצל השותף</span>
          </div>
          <div class="mv-c-pool-list">
            <div class="mv-c-item"><div class="mv-c-item-n">מרכז גבעתיים</div><div class="mv-c-item-m">4 חד' · 3.15M</div></div>
            <div class="mv-c-item"><div class="mv-c-item-n">אזור בורוכוב</div><div class="mv-c-item-m">3.5 חד' · 2.85M</div></div>
            <div class="mv-c-item"><div class="mv-c-item-n">שדרות ירושלים</div><div class="mv-c-item-m">5 חד' · 4.1M</div></div>
          </div>
        </div>

        <div class="mv-c-stages" aria-hidden="true">
          <div class="mv-c-stages-in">
            <div>התאמה נמצאה · נשלחה בקשת חשיפה</div>
            <div>אושר משני הצדדים · עמלה 50/50</div>
            <div>סיור נקבע ביומן המשותף</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="impact" class="mv-c-sec" aria-labelledby="mv-c-impact-title">
  <div class="mv-c-wrap">
    <h2 class="mv-c-h2" id="mv-c-impact-title">מה זה עושה למספרים של המשרד<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-c-impact-lede">מודל להמחשה, על משרד עם 20 נכסים ו‑45 קונים פעילים, המחובר לשלושה משרדים באזור.</p>
    <p class="mv-c-note">ההשוואה מתארת את פוטנציאל ההתאמות במאגר המורחב — לא הבטחה לתוצאה. בהדגמה נריץ את החישוב על הנתונים האמיתיים שלך.</p>

    <div class="mv-c-kpis">
      <div class="mv-c-kpi">
        <div class="mv-c-kpi-k">נכסים שקונה שלך רואה</div>
        <div class="mv-c-kpi-row"><span class="mv-c-kpi-v">×4</span><span class="mv-c-kpi-d">20 ← 80</span></div>
      </div>
      <div class="mv-c-kpi">
        <div class="mv-c-kpi-k">קונים לכל נכס בבלעדיות</div>
        <div class="mv-c-kpi-row"><span class="mv-c-kpi-v">×3.6</span><span class="mv-c-kpi-d">45 ← 160</span></div>
      </div>
      <div class="mv-c-kpi">
        <div class="mv-c-kpi-k">התאמות פוטנציאליות בחודש</div>
        <div class="mv-c-kpi-row"><span class="mv-c-kpi-v">+38</span><span class="mv-c-kpi-d">9 ← 47</span></div>
      </div>
      <div class="mv-c-kpi is-dark">
        <div class="mv-c-kpi-k">זמן ממוצע מדף למכירה</div>
        <div class="mv-c-kpi-row"><span class="mv-c-kpi-v">−31%</span><span class="mv-c-kpi-d">74 ← 51 יום</span></div>
      </div>
    </div>

    <div class="mv-c-charts">
      <div class="mv-c-chart">
        <div class="mv-c-chart-head">
          <h3 class="mv-c-chart-title">עסקאות שנסגרו ברבעון</h3>
          <span class="mv-c-chart-sub">לבד מול מחובר לרשת</span>
        </div>
        <div class="mv-c-key">
          <span><i aria-hidden="true"></i>לבד</span>
          <span class="is-green"><i aria-hidden="true"></i>ברשת המשרדים</span>
        </div>
        <div class="mv-c-bars" role="img" aria-label="גרף עמודות של עסקאות שנסגרו לפי רבעון: רבעון 1 — 4 לבד מול 7 ברשת; רבעון 2 — 5 מול 9; רבעון 3 — 4 מול 10; רבעון 4 — 6 מול 12">
          <div class="mv-c-q">
            <div class="mv-c-q-bars"><div class="mv-c-bar anim" style="height:34%"><b>4</b></div><div class="mv-c-bar is-net anim" style="height:58%"><b>7</b></div></div>
            <span class="mv-c-q-label">רבעון 1</span>
          </div>
          <div class="mv-c-q">
            <div class="mv-c-q-bars"><div class="mv-c-bar anim" style="height:42%"><b>5</b></div><div class="mv-c-bar is-net anim" style="height:75%"><b>9</b></div></div>
            <span class="mv-c-q-label">רבעון 2</span>
          </div>
          <div class="mv-c-q">
            <div class="mv-c-q-bars"><div class="mv-c-bar anim" style="height:34%"><b>4</b></div><div class="mv-c-bar is-net anim" style="height:84%"><b>10</b></div></div>
            <span class="mv-c-q-label">רבעון 3</span>
          </div>
          <div class="mv-c-q">
            <div class="mv-c-q-bars"><div class="mv-c-bar anim" style="height:50%"><b>6</b></div><div class="mv-c-bar is-net anim" style="height:100%"><b>12</b></div></div>
            <span class="mv-c-q-label">רבעון 4</span>
          </div>
        </div>
        <div class="mv-c-total">
          <span class="mv-c-total-k">סך העסקאות בשנה</span>
          <span class="mv-c-total-v">19 <i aria-hidden="true">←</i> <b>38</b></span>
        </div>
      </div>

      <div class="mv-c-rev mv-on-dark">
        <div class="mv-c-rev-glow" aria-hidden="true"></div>
        <div class="mv-c-rev-in">
          <h3 class="mv-c-rev-title">הכנסה מעמלות<span class="mv-dot" aria-hidden="true">.</span></h3>
          <div class="mv-c-rev-sub">מודל שנתי · עמלה ממוצעת 2% לצד, כולל עסקאות משותפות בחלוקה</div>

          <svg class="mv-c-rev-svg" viewBox="0 0 320 150" role="img" aria-label="גרף קווים: ההכנסה מעמלות בעבודה לבד כמעט אינה משתנה לאורך השנה, בעוד שההכנסה במצב מחובר לרשת עולה בהתמדה מרבעון לרבעון">
            <line x1="0" y1="140" x2="320" y2="140" stroke="rgba(255,255,255,.12)" stroke-width="1"/>
            <line x1="0" y1="94" x2="320" y2="94" stroke="rgba(255,255,255,.07)" stroke-width="1"/>
            <line x1="0" y1="48" x2="320" y2="48" stroke="rgba(255,255,255,.07)" stroke-width="1"/>
            <polyline class="mv-c-rev-line anim" points="310,124 232,116 154,120 76,108 10,102" fill="none" stroke="#D8DED6" stroke-opacity=".45" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="520"/>
            <polyline class="mv-c-rev-line is-net anim" points="310,118 232,92 154,66 76,42 10,20" fill="none" stroke="#70EE91" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="520"/>
            <polygon class="mv-c-rev-area anim" points="310,118 232,92 154,66 76,42 10,20 10,140 310,140" fill="url(#mvcRevFill)"/>
            <defs><linearGradient id="mvcRevFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#70EE91" stop-opacity=".28"/><stop offset="1" stop-color="#70EE91" stop-opacity="0"/></linearGradient></defs>
            <g class="mv-c-rev-dots anim">
              <circle class="mv-c-rev-halo" cx="10" cy="20" r="5" fill="#70EE91"/>
              <circle class="mv-c-rev-dot" cx="10" cy="20" r="5" fill="#70EE91"/>
              <circle cx="10" cy="102" r="4" fill="#D8DED6" fill-opacity=".5"/>
            </g>
          </svg>

          <div class="mv-c-rev-x" aria-hidden="true"><span>ר1</span><span>ר2</span><span>ר3</span><span>ר4</span></div>

          <div class="mv-c-rev-rows">
            <div class="mv-c-rev-row">
              <span class="mv-c-rev-row-k">לבד, כמו היום</span>
              <span class="mv-c-rev-row-v">₪612,000</span>
            </div>
            <div class="mv-c-rev-row is-net">
              <span class="mv-c-rev-row-k">מחובר לרשת</span>
              <span class="mv-c-rev-row-v">₪1,140,000</span>
            </div>
            <div class="mv-c-rev-gap">
              <b>+86%</b>
              <span>פער שנתי במודל</span>
            </div>
          </div>
          <p class="mv-c-rev-note">גם אם רק שליש מההתאמות החדשות נסגרות — ההצטרפות מחזירה את עצמה מהעסקה הראשונה.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="flow" class="mv-c-sec" aria-labelledby="mv-c-flow-title">
  <div class="mv-c-wrap">
    <h2 class="mv-c-h2" id="mv-c-flow-title">איך זה עובד, שלב שלב<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-c-lede">שישה שלבים, ובכל אחד מהם אתה זה שמחליט.</p>

    <div class="mv-c-flow">
      <div class="mv-c-step">
        <span class="mv-c-step-n" aria-hidden="true">1</span>
        <div>
          <h3 class="mv-c-step-title">בוחרים עם מי לעבוד</h3>
          <p class="mv-c-step-text">מזמינים משרד או מאשרים בקשה שהגיעה. לכל שותף מגדירים אזורים וסוגי נכסים שבהם השיתוף חל. אפשר לעצור שיתוף בכל רגע.</p>
        </div>
        <div class="mv-c-mock" aria-hidden="true">
          <div class="mv-c-prow">
            <span class="mv-c-pav">רל</span>
            <div class="mv-c-prow-body"><div class="mv-c-prow-n">רוזן לוין נדל"ן</div><div class="mv-c-prow-m">גבעתיים, רמת גן · דירות</div></div>
            <span class="mv-c-tag-ok">שותף פעיל</span>
          </div>
          <div class="mv-c-prow">
            <span class="mv-c-pav is-muted">שק</span>
            <div class="mv-c-prow-body"><div class="mv-c-prow-n">שחר קרן נכסים</div><div class="mv-c-prow-m">תל אביב · ביקש להתחבר</div></div>
            <span class="mv-c-tag-btn">אישור</span>
          </div>
        </div>
      </div>

      <div class="mv-c-step">
        <span class="mv-c-step-n" aria-hidden="true">2</span>
        <div>
          <h3 class="mv-c-step-title">המערכת מצליבה ברקע</h3>
          <p class="mv-c-step-text">כל נכס חדש נבדק מול הקונים של כל השותפים, וכל קונה חדש מול הנכסים שלהם. אתה מקבל התאמה עם ציון והסבר מה תואם — ומה לא.</p>
        </div>
        <div class="mv-c-mock" aria-hidden="true">
          <div class="mv-c-match">
            <span class="mv-c-match-score">87</span>
            <div>
              <div class="mv-c-match-n">התאמה חוצה משרדים</div>
              <div class="mv-c-match-m">הקונה שלך · נכס אצל רוזן לוין</div>
            </div>
          </div>
          <div class="mv-c-kv">
            <div><span>אזור</span><b class="is-ok">תואם</b></div>
            <div><span>תקציב</span><b class="is-ok">תואם · פער 50 אלף</b></div>
            <div><span>מועד כניסה</span><b class="is-na">לא נבדק</b></div>
          </div>
        </div>
      </div>

      <div class="mv-c-step">
        <span class="mv-c-step-n" aria-hidden="true">3</span>
        <div>
          <h3 class="mv-c-step-title">רואים חשיפה חלקית בלבד</h3>
          <p class="mv-c-step-text">בשלב הזה שני הצדדים רואים רק את מה שצריך כדי להחליט: אזור, חדרים, טווח מחיר ומצב מימון. כתובת, שם ופרטי קשר נעולים.</p>
        </div>
        <div class="mv-c-mock is-pad" aria-hidden="true">
          <div class="mv-c-kv is-lg">
            <div><span>אזור</span><b>מרכז גבעתיים</b></div>
            <div><span>חדרים</span><b>4</b></div>
            <div><span>מחיר</span><b class="mv-ltr">₪3,150,000</b></div>
            <div><span>כתובת מלאה</span><span class="mv-c-locked">נעול</span></div>
            <div><span>פרטי המוכר</span><span class="mv-c-locked">נעול</span></div>
            <div><span>תמונות</span><span class="mv-c-locked">נעול</span></div>
          </div>
        </div>
      </div>

      <div class="mv-c-step">
        <span class="mv-c-step-n" aria-hidden="true">4</span>
        <div>
          <h3 class="mv-c-step-title">מציעים עמלה ומבקשים אישור</h3>
          <p class="mv-c-step-text">מי שרוצה להתקדם שולח בקשת חשיפה עם הצעת חלוקת עמלה. הצד השני מאשר, דוחה או מציע חלוקה אחרת — והכול נשמר במערכת כהסכמה מתועדת.</p>
        </div>
        <div class="mv-c-mock is-pad" aria-hidden="true">
          <div class="mv-c-req-title">בקשת חשיפה · ממתינה לאישור</div>
          <div class="mv-c-fee">
            <span>חלוקת עמלה מוצעת</span>
            <b>50 / 50</b>
          </div>
          <div class="mv-c-acts">
            <span class="mv-c-act-ok">אישור</span>
            <span class="mv-c-act-alt">הצעה אחרת</span>
            <span class="mv-c-act-no">דחייה</span>
          </div>
        </div>
      </div>

      <div class="mv-c-step">
        <span class="mv-c-step-n" aria-hidden="true">5</span>
        <div>
          <h3 class="mv-c-step-title">הפרטים נפתחים לשני הצדדים</h3>
          <p class="mv-c-step-text">ברגע שיש אישור דו־צדדי נפתחים הכתובת, התמונות ופרטי הקשר — לשני המשרדים בלבד. נפתח חלל עבודה משותף לעסקה: הודעות, מסמכים ויומן סיורים.</p>
        </div>
        <div class="mv-c-mock is-pad is-ok" aria-hidden="true">
          <div class="mv-c-checks">
            <div><i>✓</i>המשרד שלך אישר</div>
            <div><i>✓</i>רוזן לוין נדל"ן אישרו</div>
            <div class="is-open"><i>3</i>כצנלסון 44 · פרטים מלאים נפתחו</div>
          </div>
          <div class="mv-c-ws">חלל עבודה משותף נפתח · 2 משרדים · עמלה 50/50</div>
        </div>
      </div>

      <div class="mv-c-step is-dark">
        <span class="mv-c-step-n" aria-hidden="true">6</span>
        <div>
          <h3 class="mv-c-step-title">סוגרים, והמערכת מסדרת את החשבון</h3>
          <p class="mv-c-step-text">בסגירת העסקה המערכת מחשבת את חלוקת העמלה לפי מה שהוסכם, מתעדת מי הביא את הקונה ומי את הנכס, ומייצרת סיכום לשני המשרדים.</p>
        </div>
        <div class="mv-c-mock is-dark" aria-hidden="true">
          <div class="mv-c-split">
            <div><span>עמלה בעסקה</span><b>₪63,000</b></div>
            <div><span>המשרד שלך · צד הקונה</span><b class="is-green">₪31,500</b></div>
            <div><span>רוזן לוין · צד הנכס</span><b>₪31,500</b></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="mv-c-sec" aria-labelledby="mv-c-tools-title">
  <div class="mv-c-wrap">
    <h2 class="mv-c-h2" id="mv-c-tools-title">מה עוד יש בכלי השיתוף<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p class="mv-c-lede">כל הפונקציות שנבנו סביב עבודה בין משרדים.</p>
    <div class="mv-c-tools">
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6.5h16M4 12h16M4 17.5h9" stroke="#0E100F" stroke-width="1.7" stroke-linecap="round"/><circle cx="18" cy="17.5" r="2.6" fill="#3FBF63"/></svg>
        <h3 class="mv-c-tool-title">יומן חשיפות</h3>
        <p class="mv-c-tool-text">מי ביקש, מי אישר, מה נחשף ומתי. תיעוד מלא של כל שיתוף — שימושי גם אם צריך להוכיח מי הביא את הלקוח.</p>
      </div>
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4.5" width="16" height="15" rx="3" stroke="#0E100F" stroke-width="1.7"/><path d="M8 10h8M8 14h5" stroke="#3FBF63" stroke-width="1.7" stroke-linecap="round"/></svg>
        <h3 class="mv-c-tool-title">הסכם שיתוף בכתב</h3>
        <p class="mv-c-tool-text">תנאי חלוקת העמלה, תוקף השיתוף וההגדרות מול כל שותף — שמורים במערכת וחתומים דיגיטלית משני הצדדים.</p>
      </div>
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 17.5V6.5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H8.5L4 21.5z" stroke="#0E100F" stroke-width="1.7" stroke-linejoin="round"/><circle cx="12" cy="11.5" r="2.2" fill="#3FBF63"/></svg>
        <h3 class="mv-c-tool-title">חלל עבודה לעסקה</h3>
        <p class="mv-c-tool-text">הודעות בין שני המשרדים, מסמכים, מועדי סיור והתקדמות — במקום אחד ולא בשרשור ווטסאפ שנעלם.</p>
      </div>
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.2" stroke="#0E100F" stroke-width="1.7"/><path d="M12 7.5v5l3.2 2" stroke="#3FBF63" stroke-width="1.7" stroke-linecap="round"/></svg>
        <h3 class="mv-c-tool-title">תוקף לשיתוף</h3>
        <p class="mv-c-tool-text">אפשר להגביל חשיפה בזמן: 30 יום ואם לא התקדם — הפרטים נסגרים חזרה אוטומטית.</p>
      </div>
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19v-6M9.3 19V8M14.7 19v-9M20 19V5" stroke="#0E100F" stroke-width="1.7" stroke-linecap="round"/><circle cx="20" cy="5" r="2.4" fill="#3FBF63"/></svg>
        <h3 class="mv-c-tool-title">דוח שיתופי פעולה</h3>
        <p class="mv-c-tool-text">כמה התאמות הגיעו מכל שותף, כמה נסגרו וכמה הכניסו. ככה יודעים עם מי כדאי להעמיק ועם מי פחות.</p>
      </div>
      <div class="mv-c-tool">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 5 5.5v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10v-5L12 3Z" stroke="#0E100F" stroke-width="1.7" stroke-linejoin="round"/><path d="m9 11.5 2.2 2.2L15.5 9.5" stroke="#3FBF63" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h3 class="mv-c-tool-title">ניתוק בלחיצה</h3>
        <p class="mv-c-tool-text">מפסיקים שיתוף עם משרד — והחשיפות הפתוחות נסגרות מיד. המאגר שלך חוזר להיות פרטי לגמרי.</p>
      </div>
    </div>
  </div>
</section>

<section class="mv-c-sec" aria-labelledby="mv-c-faq-title">
  <div class="mv-c-faq mv-on-dark">
    <div class="mv-c-faq-grid">
      <div>
        <p class="mv-c-faq-pill">שאלות שחוזרות</p>
        <h2 class="mv-c-faq-h2" id="mv-c-faq-title">"רגע, אני מוסר את הלקוחות שלי<span class="mv-dot" aria-hidden="true">?</span>"</h2>
        <p class="mv-c-faq-p">לא. זו השאלה הראשונה של כל מתווך, ולכן כל המערכת בנויה סביב התשובה הזאת.</p>
      </div>
      <div class="mv-c-qas">
        <div class="mv-c-qa">
          <h3 class="mv-c-qa-q">מי הבעלים של הליד?</h3>
          <p class="mv-c-qa-a">אתה. הליד רשום על המשרד שלך ונשאר שלך גם אחרי חשיפה. השותף מקבל גישה לעסקה הספציפית, לא ללקוח.</p>
        </div>
        <div class="mv-c-qa">
          <h3 class="mv-c-qa-q">מה אם השותף יעקוף אותי?</h3>
          <p class="mv-c-qa-a">כל חשיפה מתועדת עם חתימה של שני הצדדים על תנאי העמלה. יש רשומה חד־משמעית מי הביא את מי ומתי.</p>
        </div>
        <div class="mv-c-qa">
          <h3 class="mv-c-qa-q">אני חייב לשתף הכול?</h3>
          <p class="mv-c-qa-a">לא. אפשר לשתף נכסים מסוימים בלבד, אזורים מסוימים, או להשתתף רק בכיוון אחד — לקבל בלי לתת.</p>
        </div>
        <div class="mv-c-qa">
          <h3 class="mv-c-qa-q">והמוכר שלי — הוא יודע?</h3>
          <p class="mv-c-qa-a">אתה מחליט. יש נוסח מוכן להסבר למוכר על החשיפה המורחבת, וברוב המקרים זה בדיוק מה שהוא רוצה לשמוע.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="join" class="mv-c-sec" aria-labelledby="mv-c-join-title">
  <div class="mv-c-join">
    <div class="mv-c-join-grid">
      <div>
        <p class="mv-c-join-pill">ההצטרפות לרשת — בחינם</p>
        <h2 class="mv-c-join-h2" id="mv-c-join-title">נכנסים לרשת בלי לשלם<span class="mv-dot" aria-hidden="true">.</span></h2>
        <p class="mv-c-join-p">מעלים למערכת כמה נכסים וכמה קונים שרוצים — ללא הגבלה. גם ההצטרפות לרשת המשרדים היא בחינם, כולל פרסום של מספר לידים לרשת. מעבר לכמות החינמית עוברים למסלול בתשלום, ורק אז.</p>
        <div class="mv-c-btns mv-c-join-btns">
          <a class="mv-c-btn-green" href="<?php echo esc_url( mv_signup_url() ); ?>">פתיחת חשבון — בחינם</a>
          <a class="mv-c-btn-line" href="<?php echo esc_url( home_url( '/#plans' ) ); ?>">כל המסלולים</a>
        </div>
      </div>

      <div class="mv-c-limits">
        <div class="mv-c-limit">
          <span class="mv-c-limit-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M4 20V9.5L12 4l8 5.5V20" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/></svg></span>
          <div class="mv-c-limit-body">
            <div class="mv-c-limit-n">נכסים במערכת</div>
            <div class="mv-c-limit-m">כל הבלעדיות והנכסים שלך, בלי תקרה</div>
          </div>
          <span class="mv-c-limit-tag">ללא הגבלה</span>
        </div>
        <div class="mv-c-limit">
          <span class="mv-c-limit-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.4" stroke="currentColor" stroke-width="1.9"/><path d="M3.5 19.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><path d="M16 9.5h5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></span>
          <div class="mv-c-limit-body">
            <div class="mv-c-limit-n">קונים ולידים במערכת</div>
            <div class="mv-c-limit-m">כל המאגר שלך, כולל תמלול והתאמות פנימיות</div>
          </div>
          <span class="mv-c-limit-tag">ללא הגבלה</span>
        </div>
        <div class="mv-c-limit">
          <span class="mv-c-limit-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M14 4h3a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-3M10 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h3" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/><rect x="10.5" y="10.5" width="3" height="3" rx="1" fill="currentColor"/></svg></span>
          <div class="mv-c-limit-body">
            <div class="mv-c-limit-n">חיבור לרשת המשרדים</div>
            <div class="mv-c-limit-m">שותפים, הצלבות והסכמי עמלה</div>
          </div>
          <span class="mv-c-limit-tag">בחינם</span>
        </div>
        <div class="mv-c-limit is-cap">
          <span class="mv-c-limit-ico" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none"><path d="M12 3v13M7 11l5 5 5-5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.5 20h15" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></span>
          <div class="mv-c-limit-body">
            <div class="mv-c-limit-n">פרסום לידים לרשת</div>
            <div class="mv-c-limit-m">מספר לידים בחינם. מעבר לזה — מסלול בתשלום</div>
          </div>
          <span class="mv-c-limit-tag">כאן ההגבלה</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="cta" class="mv-c-cta-sec" aria-labelledby="mv-c-cta-title">
  <div class="mv-c-cta mv-on-dark">
    <div class="mv-c-cta-glow" aria-hidden="true"></div>
    <h2 id="mv-c-cta-title">נראה לך אילו משרדים באזור שלך כבר ברשת<span class="mv-dot" aria-hidden="true">.</span></h2>
    <p>הדגמה של 20 דקות על הנתונים שלך. בלי מצגת.</p>
    <div class="mv-c-btns">
      <a class="mv-c-btn-green" href="#demo">קביעת הדגמה</a>
      <a class="mv-c-btn-ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>">חזרה לדף הבית</a>
    </div>
  </div>
</section>
<!-- /wp:html -->
